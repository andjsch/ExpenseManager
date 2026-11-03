<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Register;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Override;

class RegisterFirstUser extends Register
{
    #[Override]
    public function mount(): void
    {
        if (Filament::auth()->check() || User::count() !== 0) {
            redirect()->action(Login::class);
        }

        $this->callHook('beforeFill');

        $this->form->fill();

        $this->callHook('afterFill');
    }

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function handleRegistration(#[SensitiveParameter] array $data): Model
    {
        return $this->getUserModel()::create([
            ...$data,
            'is_admin' => true
        ]);
    }

    #[Override]
    protected function getNameFormComponent(): Component
    {
        return Grid::make(2)
            ->schema([
                TextInput::make('fname')
                    ->label(__('app.user_fname'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('lname')
                    ->label(__('app.user_lname'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus()
            ]);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('app.username'))
            ->required()
            ->unique($this->getUserModel());
    }

    #[Override]
    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }
}
