<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'description',
        'date',
        'amount',
        'type',
        'guarantee',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];

    protected $attributes = [
        'guarantee' => false
    ];

    public function typeO()
    {
        return $this->hasOne(ExpenseType::class, 'id', 'type');
    }
}
