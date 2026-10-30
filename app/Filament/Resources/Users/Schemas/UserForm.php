<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                            ->required(),
                        TextInput::make('fname')
                            ->required(),
                        TextInput::make('lname')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Section::make('Password & Access')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->saved(fn (?string $state): bool => filled($state))
                            ->live()
                            ->required(fn (string $operation, ?string $state) => $operation === 'create' || filled($state)),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->saved(false)
                            ->live()
                            ->required(fn (string $operation, ?string $state, Get $get) => $operation === 'create' || filled($get('password'))),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
