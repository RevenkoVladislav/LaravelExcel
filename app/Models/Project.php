<?php

namespace App\Models;

use App\Enums\ImportType;
use App\Http\Resources\Payment\PaymentResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = [
        'type_id',
        'title',
        'creation_date',
        'contracted_date',
        'deadline',
        'is_chain',
        'is_on_time',
        'has_outsource',
        'has_investors',
        'workers_count',
        'services_count',
        'payment_first_step',
        'payment_second_step',
        'payment_third_step',
        'payment_fourth_step',
        'comment',
        'efficiency_value',
        'import_type',
    ];

    protected $casts = [
        'creation_date' => 'date',
        'contracted_date' => 'date',
        'deadline' => 'date',
        ];
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'project_id', 'id');
    }

    /**
     * аксессор, который смотри тип импорта
     * если статичный - то считает 4 поля и возвращает сумму
     * если динамичный то суммирует валюту через отношение
     */
    public function getTotalPaymentsAttribute(): float
    {
        if ($this->import_type === 'static') {
            return
                ($this->payment_first_step ?? 0) +
                ($this->payment_second_step ?? 0) +
                ($this->payment_third_step ?? 0) +
                ($this->payment_fourth_step ?? 0);
            }

        return $this->payments->sum('value');
    }

    /**
     * Метод для формирования массива с деталями по платежам для передачи на фронт с учетом типа импорта
     * Если динамический импорт то отдаем PaymentResource который преобразуем впростой массив через resolve
     *
     * Для статического импорта формируем коллекцию по всем 4 этап платежа
     * Применяем фильтер, берем только значения value и преобразуем в обычный массив и отдаем на фронт
     */
    public function paymentsForView(): array
    {
        //динамичный импорт
        if ($this->import_type === ImportType::DYNAMIC->value) {
            return PaymentResource::collection($this->payments)->resolve();
        }

        //статичный импорт
        return collect([
            ['title' => 'Этап 1', 'value' => $this->payment_first_step],
            ['title' => 'Этап 2', 'value' => $this->payment_second_step],
            ['title' => 'Этап 3', 'value' => $this->payment_third_step],
            ['title' => 'Этап 4', 'value' => $this->payment_fourth_step],
        ])->filter(fn ($property) => $property['value'])->values()->toArray();
    }
}
