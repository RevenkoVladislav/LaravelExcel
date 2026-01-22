<?php

namespace App\Imports;

use App\Builder\PaymentRowBuilder;
use App\Builder\ProjectDynamicRowBuilder;
use App\Factory\PaymentFactory;
use App\Factory\ProjectFactory;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\BeforeSheet;

class ExcelDynamicImport extends BaseExcelImport implements WithStartRow, WithEvents
{
    use RegistersEventListeners;

    private ProjectFactory $projectFactory;
    private ProjectDynamicRowBuilder $rowDynamicBuilder;
    private PaymentFactory $paymentFactory;
    private PaymentRowBuilder $paymentBuilder;
    public function __construct(Task $task)
    {
        parent::__construct($task);
        $this->rowDynamicBuilder = app(ProjectDynamicRowBuilder::class);
        $this->paymentBuilder = app(PaymentRowBuilder::class);
        $this->projectFactory = app(ProjectFactory::class);
        $this->paymentFactory = app(PaymentFactory::class);
    }
    protected const STATIC_ROW = 12;
    private array $dynamicHeaders = [];

    /**
     * Получаем названия из таблицы Types
     *
     * Проходимся по строкам в загруженном файле (начинаем со второй из-за WithStartRow)
     * Если поле - наименование ($row[1] - пустое, то продолжить (нужно чтобы в бд не попали пустые строки)
     *
     * Формируем DTO через builder передав ему статические данные
     * Создаем фабрику передав данные из DTO
     *
     * Если у нас не пустой массив dynamic данных то
     * Формируем DTO по платежам, передаем в него динамические данные
     * id проекта
     * И заголовки динамических строк
     *
     * Синхронизируем данне PaymentFactory
     * Защита от непредвиденных ошибок с логированием
     */
    public function collection(Collection $collection): void
    {
        try {

            foreach ($collection as $row) {
                if (!isset($row[1])) {
                    continue;
                }

                $rowData = $this->getRowsMap($row);

                $projectDto = $this->rowDynamicBuilder->build($rowData['static']);
                $project = $this->projectFactory->create($projectDto);


                if (!empty($rowData['dynamic'])) {
                    $paymentsDtos = $this->paymentBuilder->build(
                        $rowData['dynamic'],
                        $project->id,
                        $this->dynamicHeaders
                    );

                    $this->paymentFactory->sync($paymentsDtos);
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
    protected function attributeMap(): array
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
            $rules[$key] = 'nullable|numeric';
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
