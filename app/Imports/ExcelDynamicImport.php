<?php

namespace App\Imports;

use App\Factory\ProjectDynamicFactory;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Type;
use App\Services\ImportFailureService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Validators\Failure;

class ExcelDynamicImport implements ToCollection, WithValidation, SkipsOnFailure, WithStartRow, WithEvents
{
    use RegistersEventListeners;

    public function __construct(
        private Task $task,
    ) {}

    protected const STATIC_ROW = 12;
    private array $dynamicHeaders = [];

    /**
     * Получаем названия из таблицы Types
     *
     * Проходимся по строкам в загруженном файле (начинаем со второй из-за WithStartRow)
     * Если поле - наименование ($row[1] - пустое, то продолжить (нужно чтобы в бд не попали пустые строки)
     *
     * Получаем массив из статических и динамических свойств Excel файла
     * Проходимся в цикле и вызываем метод make у фабрики для создания экземпляра класса по каждой строке для статических данных
     * Передаем массив уникальных ключей и массив всех значений в метод updateOrCreate
     *
     * Если не пустой массив - $rowData['dynamic'] то проходимся по нему циклом
     * Через ивент получим заголовки и отфильтруем нулевые заголовки через функцию getRowsMap
     * Создадим или обновим запись в таблице Payment
     *
     * Защита от непредвиденных ошибок с логированием
     */
    public function collection(Collection $collection): void
    {
        try {
            $typesMap = $this->getTypesMap(Type::all());

            foreach ($collection as $row) {
                if (!isset($row[1])) {
                    continue;
                }

                $rowData = $this->getRowsMap($row);

                $projectFactory = ProjectDynamicFactory::make($typesMap, $rowData['static']);

                $project = Project::updateOrCreate(
                    $projectFactory->getUniqueKeys(),
                    $projectFactory->getValues()
                );

                if (!isset($rowData['dynamic'])) {
                    continue;
                }

                foreach ($rowData['dynamic'] as $key => $item) {
                    Payment::updateOrCreate([
                        'project_id' => $project->id,
                        'title' => $this->dynamicHeaders[$key],
                        'value' => $item,
                    ]);
                }
            }
        } catch (\Throwable $exception) {
            Log::error('Excel import failed', [
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }

    /**
     * Вспомогательный метод для формирования массивов статических и динамических данных при импорте excel
     * Объявляем два массива
     * Проходим строку разбиваем на ключ => значение
     * Если есть значение то сверяем - оно больше ограничивающей константы ?
     * Если болье - ложим в массив с динамическими свойствами
     * Если меньше - ложим в массив со статическими свойствами
     *
     * Возвращаем массив содержащий два массива - static и dynamic
     */
    private function getRowsMap($row)
    {
        $static = [];
        $dynamic = [];

        foreach ($row as $key => $value) {
            if ($value) {
                $key > self::STATIC_ROW ? $dynamic[$key] = $value : $static[$key] = $value;
            }
        }

        return [
            'static' => $static,
            'dynamic' => $dynamic,
        ];
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
     * Присоединяем динамические значения
     */
    public function rules(): array
    {
        return array_replace([
            '0' => 'required|string',
            '1' => 'required|string',
            '2' => 'required|numeric',
            '9' => 'required|numeric',
            '7' => 'nullable|numeric',
            '3' => 'nullable|string',
            '5' => 'nullable|string',
            '6' => 'nullable|string',
            '8' => 'nullable|string',
            '4' => 'nullable|integer',
            '10' => 'nullable|integer',
            '11' => 'nullable|string',
            '12' => 'nullable|numeric',
        ], $this->getDynamicRules());
    }

    /**
     * Получаем корректные названия аттрибутов для записи в бд
     * Присоединяем массив с динамическими значениями
     */
    private function attributeMap(): array
    {
        return array_replace([
            '0' => 'Тип',
            '1' => 'Наименование',
            '2' => 'Дата создания',
            '9' => 'Подписание договора',
            '7' => 'Дедлайн',
            '3' => 'Сетевик',
            '5' => 'Наличие аутсорсинга',
            '6' => 'Наличие инвесторов',
            '8' => 'Сдача в срок',
            '4' => 'Количество участников',
            '10' => 'Количество услуг',
            '11' => 'Комментарий',
            '12' => 'Значение эффективности',
        ], $this->getDynamicAttributes());
    }

    /**
     * Пропускаем первый ряд и начинаем со второго
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * getSheet - Возвращает объект листа Laravel Excel. (Это обертка над основным объектом библиотеки PhpSpreadsheet)
     * getDelegate - дает доступ к делегату, т.е к низкоуровневым функциям которые не реализованы в самом пакете (т.е к toArray)
     * toArray - преобразуем в массив
     * [0] - берем только первую строку т.е заголовки
     *
     * Проходим по всем элементам массива $headers функцией array_filter
     * С помощью ARRAY_FILTER_USE_BOTH передаем в анонимную функцию value и key
     * Фильтруем только если key больше ограничительной константы и не пустое значение value
     * На выходе получаем отфильтрованный массив где остались колонки которые идут после статичных и имеют значение
     */
    public function beforeSheet(BeforeSheet $event): void
    {
        $headers = $event->getSheet()->getDelegate()->toArray()[0];

        $this->dynamicHeaders = array_filter(
            $headers,
            fn ($value, $key) => $key > self::STATIC_ROW && !empty($value),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Функция для валидации динамической части Excel импорта.
     * Формируем массив rules
     * Проходим циклом по всем нашим динамическим заголовками dynamicHeaders
     * В массив rules записываем валидацию для новых полей
     */
    private function getDynamicRules(): array
    {
        $rules = [];
        foreach ($this->dynamicHeaders as $key => $value) {
            $rules[$key] = 'required|numeric';
        }

        return $rules;
    }

    /**
     * Аналогично методу rules, только для аттрибутов
     * Формируем массив с аттрибутами,
     * проходим циклом по dynamicHeaders
     * формируем аттрибуты по ключу и присваиваем значение
     * Возвращаем сформированный массив.
     */
    private function getDynamicAttributes(): array
    {
        $attributes = [];
        foreach ($this->dynamicHeaders as $key => $value) {
            $attributes[$key] = $value;
        }

        return $attributes;
    }
}
