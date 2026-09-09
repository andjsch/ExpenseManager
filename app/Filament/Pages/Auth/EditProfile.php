<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as DefaultEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Override;
use SensitiveParameter;

class EditProfile extends DefaultEditProfile
{

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponents(),
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    protected function getNameFormComponents(): Component
    {
        return Grid::make()
            ->components([
                TextInput::make('fname')
                    ->label(__('auth.fname'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('lname')
                    ->label(__('auth.lname'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
            ]);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('auth.username'))
            ->required()
            ->autocomplete()
            ->autofocus();
    }
}
