<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        TextInput::make('username')
                            ->label(__('app.username'))
                            ->required(),
                        TextInput::make('fname')
                            ->label(__('app.user_fname'))
                            ->required(),
                        TextInput::make('lname')
                            ->label(__('app.user_lname'))
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Fieldset::make('Password & Access')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_admin')
                            ->label(__('app.user_admin')),
                        TextInput::make('password')
                            ->label(__('app.user_password'))
                            ->hint(__('app.user_password_leave_empty'))
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->saved(fn (?string $state): bool => filled($state))
                            ->live()
                            ->required(fn (string $operation, ?string $state) => $operation === 'create' || filled($state)),
                        TextInput::make('password_confirmation')
                            ->label(__('app.user_password_confirmation'))
                            ->password()
                            ->saved(false)
                            ->live()
                            ->required(fn (string $operation, ?string $state, Get $get) => $operation === 'create' || filled($get('password'))),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
