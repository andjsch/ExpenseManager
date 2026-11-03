<?php

namespace App\Filament\Forms\Components;

use App\Dictionaries\Currencies;
use Filament\Forms\Components\Select;

class CurrencySelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $currencies =
            collect(Currencies::items())
                ->pluck(fn (array $value) => Currencies::label($value), 'code');

        $this->options($currencies);

        $this->searchable();
    }
}
