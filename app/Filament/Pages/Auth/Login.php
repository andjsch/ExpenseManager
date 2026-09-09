<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as DefaultLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Override;
use SensitiveParameter;

class Login extends DefaultLogin
{

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
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

    #[Override]
    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }
}
