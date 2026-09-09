<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded]
class Bill extends Model
{
    protected function casts(): array {
        return [
            'guarantee' => 'bool',
            'date' => 'date',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
    }
}
