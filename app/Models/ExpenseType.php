<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class ExpenseType extends Model
{
    public function total($month, $year)
    {
        if($month != 0)
            return Bill::where('type', $this->id)->whereMonth('date', '=', $month)->whereYear('date', '=', $year)->sum('amount');
        else
            return Bill::where('type', $this->id)->whereYear('date', '=', $year)->sum('amount');
    }
}
