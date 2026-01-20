<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'project_id',
        'title',
        'value'
    ];
    public $timestamps = false;
}
