<?php

namespace App\Filament\Resources\Bills\Widgets;

use App\Models\ExpenseType;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ExpenseTypesOverview extends TableWidget
{
    use InteractsWithPageFilters;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ExpenseType::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('total')
                    ->label(__('app.expense_total'))
                    ->state(fn (ExpenseType $record) => $record->total($this->tableFilters['date']['month'], $this->tableFilters['date']['year']))
                    ->alignEnd()
                    ->money('EUR')
            ])
            ->recordClasses(['row-py-sm'])
            ->paginated(false)
            ->heading(null)
            ->contentFooter(null);
    }

    #[On('table-filter-updated')]
    public function updatedFilters(array $data): void
    {
        $this->tableFilters = $data;
    }
}
