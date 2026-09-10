<?php

namespace App\Filament\Resources\Bills\Widgets;

use App\Models\Bill;
use Filament\Infolists\Components\TextEntry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Livewire\Attributes\On;

class TotalOverview extends StatsOverviewWidget
{
    public array $tableFilters = [];

    public ?int $month = null;

    public ?int $year = null;

    public ?float $total = null;

    public ?float $totalPreviousPeriod = null;

    protected function getStats(): array
    {
        $this->month = $this->tableFilters['date']['month'];
        $this->year = $this->tableFilters['date']['year'];

        return [
            Stat::make(__('app.expense_total'), $this->getTotalString())
                ->description($this->getDifference()['amountString'])
                ->descriptionIcon($this->getDifference()['icon'])
                ->descriptionColor($this->getDifference()['color'])
                ->chart($this->getChartData())
                ->color('primary'),
        ];
    }

    private function getTotal(): float
    {
        if (! $this->total) {
            $this->total = $this->baseQuery($this->month, $this->year)->sum('amount');
        }

        return $this->total;
    }

    private function getTotalString(): string
    {
        return Number::currency($this->getTotal(), 'EUR', config('app.locale'), 2);
    }

    private function getTotalPreviousPeriod(): ?string
    {
        if (! $this->totalPreviousPeriod) {
            $month = $this->month;
            $year = $this->year;

            if ($month === 1 && $year !== null) {
                $month = 12;
                $year -= 1;
            } elseif ($month !== 1 && $year !== null) {
                $month -= 1;
            } elseif ($month === null && $year !== null) {
                $year -= 1;
            }

            if ($month === null && $year === null) return null;

            $this->totalPreviousPeriod = $this->baseQuery($month, $year)->sum('amount');
        }

        return $this->totalPreviousPeriod;
    }

    private function getDifference(): array
    {
        $amount = $this->getTotal() - $this->getTotalPreviousPeriod();

        return [
            'amount' => $amount,
            'amountString' => ($amount < 0 ? '' : '+') . Number::currency($amount, 'EUR', config('app.locale'), 2),
            'icon' => $amount < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up',
            'color' => $amount < 0 ? 'success' : 'danger',
        ];
    }

    private function baseQuery(?int $month, ?int $year): Builder
    {
        $expenseTypeId = $this->tableFilters['expense_type_id']['value'];

        return Bill::query()
            ->when($expenseTypeId !== null, fn (Builder $query) => $query->where('expense_type_id', $expenseTypeId))
            ->when($month !== null, fn (Builder $query) => $query->whereMonth('date', '=', $month))
            ->when($year !== null, fn (Builder $query) => $query->whereYear('date', '=', $year));
    }

    private function getChartData(): array {
        if ($this->month && $this->year) {
            return collect(range(1, 12))
                ->map(fn (int $day) => $this->baseQuery($this->month, $this->year)->whereDay('date', $day)->sum('amount'))
                ->toArray();
        } elseif ($this->year) {
            return [
                $this->baseQuery(1, $this->year)->sum('amount'),
                $this->baseQuery(2, $this->year)->sum('amount'),
                $this->baseQuery(3, $this->year)->sum('amount'),
                $this->baseQuery(4, $this->year)->sum('amount'),
                $this->baseQuery(5, $this->year)->sum('amount'),
                $this->baseQuery(6, $this->year)->sum('amount'),
                $this->baseQuery(7, $this->year)->sum('amount'),
                $this->baseQuery(8, $this->year)->sum('amount'),
                $this->baseQuery(9, $this->year)->sum('amount'),
                $this->baseQuery(11, $this->year)->sum('amount'),
                $this->baseQuery(12, $this->year)->sum('amount'),
            ];
        }

        return [];
    }

    #[On('table-filter-updated')]
    public function updatedFilters(array $data): void
    {
        // convert empty strings to null values
        $data = collect($data)->dot()->map(function ($data) {
            return $data === '' ? null : $data;
        })->undot()->toArray();

        $this->total = null;
        $this->totalPreviousPeriod = null;

        $this->tableFilters = $data;
    }
}
