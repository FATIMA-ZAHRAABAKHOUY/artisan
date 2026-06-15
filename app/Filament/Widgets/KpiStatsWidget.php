<?php

namespace App\Filament\Widgets;

use App\Models\Artisan;
use App\Models\Commande;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KpiStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Commande::query()
            ->where('status', 'livree')
            ->sum('total_ttc');

        $revenueInK = number_format($totalRevenue / 1000, 0, ',', ' ');

        return [
            Stat::make('Clients', User::query()->where('role', 'client')->count())
                ->description('Comptes clients actifs')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Artisans vérifiés', Artisan::query()->where('is_verified', true)->count())
                ->description('Profils artisans validés')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Commandes livrées', Commande::query()->where('status', 'livree')->count())
                ->description('Commandes finalisées')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Revenus totaux', $revenueInK.' K MAD')
                ->description('Chiffre d\'affaires livré')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
