<?php

namespace App\Imports;

use App\Factory\ProjectFactory;
use App\Models\FailedRow;
use App\Models\Project;
use App\Models\Task;
use App\Models\Type;
use App\Services\ImportFailureService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    public function __construct(
        private Task $task,
    ) {}

    /**
     * Получаем названия из таблицы Types
     *
     * Проходимся по строкам в загруженном файле
     * Если поле - наименование - пустое, то продолжить (нужно чтобы в бд не попали пустые строки)
     *
     * Проходимся в цикле и вызываем метод make у фабрики для создания экземпляра класса по каждой строке
     * Передаем массив уникальных ключей и массив всех значений в метод updateOrCreate
     *
     * Защита от непредвиденных ошибок с логированием
     */
    public function collection(Collection $collection): void
    {
        try {
            $typesMap = $this->getTypesMap(Type::all());

            foreach ($collection as $row) {
                if (!isset($row['naimenovanie'])) {
                    continue;
                }

                $projectFactory = ProjectFactory::make($typesMap, $row);

                Project::updateOrCreate(
                    $projectFactory->getUniqueKeys(),
                    $projectFactory->getValues()
                );
            }
        } catch (\Throwable $exception) {
            Log::error('Excel import failed', [
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }

    /**
     * Получаем массив данных из таблицы Types
     * Где ключ - title из таблицы Types, а значение id из таблицы Types
     */
    private function getTypesMap($types): array
    {
        $map = [];
        foreach ($types as $type) {
            $map[$type->title] = $type->id;
        }

        return $map;
    }

    /**
     * Собираем все ошибки в массив $errorsMap
     *
     * Получаем все аттрибуты в $attributes для корректной записи в БД
     * В первом цикле получает значение аттрибута в котором произошла ошибка
     * Если он есть в кастомном массиве значений то берем его иначе берем системное название
     *
     * Проходимся циклом по каждому объекту Failure
     * Проходимся по всем ошибкам и формируем массив, который попадет в errorsMap
     * В массив попадают аттрибут ошибки, строка где была ошибка, сообщение об ошибке
     *
     * Если массив errorsMap не пустой то проводим массовую вставку в бд через сервис
     */
    public function onFailure(Failure ...$failures): void
    {
        $errorsMap = [];
        $attributes = $this->attributeMap();

        foreach ($failures as $failure){
            $attributeKey = $failure->attribute();
            $readableAttribute = $attributes[$attributeKey] ?? $attributeKey;

            foreach ($failure->errors() as $error) {
                $errorsMap[] = [
                    'key' => $readableAttribute,
                    'row' => $failure->row(),
                    'message' => "Row - {$failure->row()}: поле «{$readableAttribute}»: $error",
                    'task_id' => $this->task->id,
                ];
            }
        }

        if (!empty($errorsMap)) {
            ImportFailureService::insertFailedRows($this->task, $errorsMap);
        }
    }

    /**
     * правила валидации для excel import
     */
    public function rules(): array
    {
        return [
            'tip' => 'required|string',
            'naimenovanie' => 'required|string',
            'data_sozdaniia' => 'required|string',
            'podpisanie_dogovora' => 'required|numeric',
            'dedlain' => 'nullable|numeric',
            'setevik' => 'nullable|string',
            'nalicie_autsorsinga' => 'nullable|string',
            'nalicie_investorov' => 'nullable|string',
            'sdaca_v_srok' => 'nullable|string',
            'vlozenie_v_pervyi_etap' => 'nullable|integer',
            'vlozenie_vo_vtoroi_etap' => 'nullable|integer',
            'vlozenie_v_tretii_etap' => 'nullable|integer',
            'vlozenie_v_cetvertyi_etap' => 'nullable|integer',
            'kolicestvo_ucastnikov' => 'nullable|integer',
            'kolicestvo_uslug' => 'nullable|integer',
            'kommentarii' => 'nullable|string',
            'znacenie_effektivnosti' => 'nullable|numeric',
        ];
    }

    /**
     * Получаем корректные названия аттрибутов для записи в бд
     */
    private function attributeMap(): array
    {
        return [
            'tip' => 'Тип',
            'naimenovanie' => 'Наименование',
            'data_sozdaniia' => 'Дата создания',
            'podpisanie_dogovora' => 'Подписание договора',
            'dedlain' => 'Дедлайн',
            'setevik' => 'Сетевик',
            'nalicie_autsorsinga' => 'Наличие аутсорсинга',
            'nalicie_investorov' => 'Наличие инвесторов',
            'sdaca_v_srok' => 'Сдача в срок',
            'vlozenie_v_pervyi_etap' => 'Вложение в первый этап',
            'vlozenie_vo_vtoroi_etap' => 'Вложение во второй этап',
            'vlozenie_v_tretii_etap' => 'Вложение в третий этап',
            'vlozenie_v_cetvertyi_etap' => 'Вложение в четвертый этап',
            'kolicestvo_ucastnikov' => 'Количество участников',
            'kolicestvo_uslug' => 'Количество услуг',
            'kommentarii' => 'Комментарий',
            'znacenie_effektivnosti' => 'Значение эффективности',
        ];
    }
}
