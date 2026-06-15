<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\KpiStatsWidget;
use App\Filament\Widgets\MonthlyRevenueChart;
use App\Filament\Widgets\PendingArtisansWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationGroup = 'Principal';

    protected static ?string $navigationLabel = 'Tableau de bord';

    protected static ?string $title = 'Tableau de bord';

    public function getWidgets(): array
    {
        return [
            KpiStatsWidget::class,
            MonthlyRevenueChart::class,
            RecentOrdersWidget::class,
            PendingArtisansWidget::class,
        ];
    }
}
