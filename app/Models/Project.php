<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
