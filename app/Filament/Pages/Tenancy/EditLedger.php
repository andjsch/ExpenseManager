<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Ledger;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

class EditLedger extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return __('app.ledger_edit');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('app.ledger_name'))
                    ->required(),
            ]);
    }

    public function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            DeleteAction::make()
                ->record($this->tenant)
                ->modalHeading(fn (Ledger $record) => __('app.ledger_delete_heading', ['name' => $record->name]))
                ->modalDescription(__('app.ledger_delete_description'))
                ->successRedirectUrl(route('filament.expenses.auth.login'))
        ];
    }
}
