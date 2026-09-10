<?php

namespace App\Filament\Resources\Bills;

use App\Filament\Resources\Bills\Pages\ManageBills;
use App\Models\Bill;
use App\Models\ExpenseType;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\QueryBuilder\Constraints\DateConstraint;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'description';

    public static function getModelLabel(): string
    {
        return __('app.expense');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.expenses');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('description')
                    ->label(__('app.expense_description'))
                    ->maxLength(70)
                    ->autosize()
                    ->belowContent(
                        Text::make('`${($state ? $state.length : 0)} / 70`')->js()
                    )
                    ->required(),
                DatePicker::make('date')
                    ->label(__('app.expense_date'))
                    ->required(),
                TextInput::make('amount')
                    ->label(__('app.expense_amount'))
                    ->required()
                    ->numeric()
                    ->step(0.01),
                Select::make('expense_type_id')
                    ->label(__('app.expense_type'))
                    ->options(ExpenseType::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Toggle::make('guarantee')
                    ->label(__('app.expense_guarantee'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort(fn (Builder $query) => $query->orderByDesc('date'))
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('description')
                    ->label(__('app.expense_description'))
                    ->icon(fn (Bill $record): ?Heroicon => $record->guarantee ? Heroicon::OutlinedCheckBadge : null)
                    ->iconPosition(IconPosition::After)
                    ->iconColor('success')
                    ->tooltip(fn (Bill $record): ?string => $record->guarantee ? __('app.expense_guarantee') : null)
                    ->searchable(),
                TextColumn::make('expenseType.name')
                    ->label(__('app.expense_type_short'))
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('app.expense_amount'))
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('app.expense_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->stackedOnMobile()
            ->defaultPaginationPageOption(50)
            ->filters([
                Filter::make('date')
                    ->schema([
                        Select::make('month')
                            ->label('Month')
                            ->options(function (): array {
                                return collect(range(1, 12))
                                    ->mapWithKeys(fn (int $month): array => [
                                        $month => now()->setMonth($month)->setDay(1)->getTranslatedMonthName(),
                                    ])
                                    ->all();
                            })
                            ->default(fn () => now()->month),
                        TextInput::make('year')
                            ->label('Year')
                            ->numeric()
                            ->default(fn () => now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['year'], fn (Builder $query) => $query->whereYear('date', $data['year']))
                            ->when($data['month'], fn (Builder $query) => $query->whereMonth('date', $data['month']));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $month = $data['month'] ? now()->setMonth((int) $data['month'])->setDay(1)->getTranslatedMonthName() : null;

                        return str("{$month} {$data['year']}")->trim();
                    })
                    ->columns(2)
                    ->default(),
                SelectFilter::make('expense_type_id')
                    ->label(__('app.expense_type'))
                    ->options(ExpenseType::query()->pluck('name', 'id'))
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->deferFilters(false)
            ->recordActions([
                EditAction::make()->hiddenLabel(true),
                DeleteAction::make()->hiddenLabel(true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBills::route('/'),
        ];
    }
}
