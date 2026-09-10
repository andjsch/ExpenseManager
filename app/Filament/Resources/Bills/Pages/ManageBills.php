<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use App\Filament\Resources\Bills\Widgets\ExpenseTypesOverview;
use App\Filament\Resources\Bills\Widgets\TotalOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ManageBills extends ManageRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = BillResource::class;

    protected array $cachedSidebarWidgetsSchemaComponents;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getSidebarWidgets(): array {
        return [
            TotalOverview::class,
            ExpenseTypesOverview::class,
        ];
    }

    protected function handleTableFilterUpdates(): void
    {
        parent::handleTableFilterUpdates();
        $this->dispatch('table-filter-updated', $this->tableFilters);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
            ])
            ->components([
                Grid::make(1)
                    ->schema($this->getSidebarWidgetsConfiguration()),
                EmbeddedTable::make()
                    ->columnSpan(['lg' => 2]),
            ]);
    }

    public function getSidebarWidgetsConfiguration(): array {
        if (! isset($this->cachedSidebarWidgetsSchemaComponents)) {
            $this->cachedSidebarWidgetsSchemaComponents = $this->getWidgetsSchemaComponents(
                $this->getSidebarWidgets()
            );
        }

        return $this->cachedSidebarWidgetsSchemaComponents;
    }
}
