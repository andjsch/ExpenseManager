<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded([])]
class ExpenseType extends Model
{

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function total(?int $month, ?int $year)
    {
        return $this->bills()
            ->when($month !== null, fn (Builder $query) => $query->whereMonth('date', '=', $month))
            ->when($year !== null, fn (Builder $query) => $query->whereYear('date', '=', $year))
            ->sum('amount');
    }
}
