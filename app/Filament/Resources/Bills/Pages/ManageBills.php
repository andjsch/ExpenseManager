<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use App\Filament\Resources\Bills\Widgets\ExpenseTypesOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ManageRecords;

class ManageBills extends ManageRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = BillResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ExpenseTypesOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function updatedTableFilters(): void
    {
        $this->dispatch('table-filter-updated', $this->tableFilters);
    }
}
