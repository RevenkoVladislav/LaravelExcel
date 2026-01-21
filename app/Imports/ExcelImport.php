<?php

namespace App\Imports;

use App\Builder\ProjectRowBuilder;
use App\Factory\ProjectFactory;
use App\Models\Project;
use App\Models\Task;
use App\Models\Type;
use App\Services\ImportFailureService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class ExcelImport extends BaseExcelImport implements WithHeadingRow
{
    private ProjectRowBuilder $rowBuilder;
    private ProjectFactory $projectFactory;
    public function __construct(Task $task)
    {
        parent::__construct($task);
        $this->rowBuilder = app(ProjectRowBuilder::class);
        $this->projectFactory = app(ProjectFactory::class);
    }
    /**
     * Проходимся по строкам в загруженном файле
     * Если поле - наименование - пустое, то продолжить (нужно чтобы в бд не попали пустые строки)
     *
     * Формируем DTO через билдер
     * Создаем фабрику, передав данные из DTO
     *
     * Защита от непредвиденных ошибок с логированием
     */
    public function collection(Collection $collection): void
    {
        try {
            foreach ($collection as $row) {
                if (!isset($row['naimenovanie'])) {
                    continue;
                }

                $dto = $this->rowBuilder->build($row);
                $project = $this->projectFactory->create($dto);
            }
        } catch (\Throwable $exception) {
            Log::error('Excel import failed', [
                'exception' => $exception,
            ]);
            throw $exception;
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
            'data_sozdaniia' => 'required|numeric',
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
    protected function attributeMap(): array
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
