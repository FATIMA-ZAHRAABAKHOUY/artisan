<?php

namespace App\Filament\Widgets;

use App\Models\Commande;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenus mensuels';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn (int $offset) => now()->subMonths($offset)->startOfMonth());

        $labels = $months->map(fn (Carbon $month) => $month->translatedFormat('M Y'))->all();

        $data = $months->map(function (Carbon $month) {
            return Commande::query()
                ->where('status', 'livree')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_ttc');
        })->all();

        return [
            'datasets' => [
                [
                    'label' => 'Revenus (MAD)',
                    'data' => $data,
                    'backgroundColor' => '#2C3E7A',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
