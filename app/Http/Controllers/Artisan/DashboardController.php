<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\CommandeItem;

class DashboardController extends Controller
{
    public function index()
    {
        $artisan = auth()->user()->artisan;

        $totalProducts = $artisan->products()->count();
        $activeProducts = $artisan->products()->where('is_active', true)->count();
        $lowStockProducts = $artisan->products()->where('stock', '<=', 5)->count();

        $orderStats = CommandeItem::query()
            ->where('artisan_id', $artisan->id)
            ->selectRaw('COUNT(DISTINCT commande_id) as total_orders')
            ->selectRaw('COALESCE(SUM(subtotal), 0) as total_revenue')
            ->first();

        $pendingOrders = CommandeItem::query()
            ->where('artisan_id', $artisan->id)
            ->whereHas('commande', fn ($query) => $query->where('status', 'en_attente'))
            ->distinct('commande_id')
            ->count('commande_id');

        $recentProducts = $artisan->products()
            ->with('category')
            ->latest()
            ->limit(5)
            ->get();

        $recentOrderItems = CommandeItem::query()
            ->where('artisan_id', $artisan->id)
            ->with(['commande.user', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        $monthlyRevenue = CommandeItem::query()
            ->where('artisan_id', $artisan->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('subtotal');

        $kpis = [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'low_stock_products' => $lowStockProducts,
            'total_orders' => (int) ($orderStats->total_orders ?? 0),
            'total_revenue' => (float) ($orderStats->total_revenue ?? 0),
            'pending_orders' => $pendingOrders,
            'monthly_revenue' => (float) $monthlyRevenue,
        ];

        return view('artisan.dashboard', compact('kpis', 'recentProducts', 'recentOrderItems', 'artisan'));
    }
}
