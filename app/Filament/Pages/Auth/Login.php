<?php

namespace App\Filament\Pages\Auth;

use App\Models\Ledger;
use App\Models\User;
use Filament\Auth\Pages\Login as DefaultLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;
use Override;
use SensitiveParameter;

class Login extends DefaultLogin
{
    #[Override]
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if (User::count() === 0 && Ledger::count() === 0) {
            redirect()->action(RegisterFirstUser::class);
        }

        $this->form->fill();
    }

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

    #[Override]
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    #[Override]
    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }
}
