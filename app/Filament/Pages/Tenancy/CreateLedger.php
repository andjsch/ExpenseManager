<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\ExpenseType;
use App\Models\Ledger;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Schema;

class CreateLedger extends RegisterTenant
{
    public static function getLabel(): string
    {
        return __('app.ledger_create');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Callout::make(__('app.ledger_callout_heading'))
                    ->description(__('app.ledger_callout_description'))
                    ->info(),
                TextInput::make('name')
                    ->label(__('app.ledger_name'))
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): Ledger
    {
        $ledger = Ledger::create($data);

        $ledger->users()->attach(auth()->user());

        // create first expense type for ledger
        $ledger->expenseTypes()->save(ExpenseType::make(['name' => __('app.installer_exp_type_general')]));

        return $ledger;
    }
}
