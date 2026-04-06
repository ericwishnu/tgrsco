<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductViewsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalViews = (int) Product::query()->sum('views_count');
        $activeProducts = (int) Product::query()->where('is_active', true)->count();

        $topProduct = Product::query()
            ->where('is_active', true)
            ->orderByDesc('views_count')
            ->first();

        return [
            Stat::make('Total Product Views', number_format($totalViews))
                ->description('Across all products')
                ->color('success'),

            Stat::make('Active Products', number_format($activeProducts))
                ->description('Currently visible in catalog')
                ->color('info'),

            Stat::make('Top Viewed Product', $topProduct?->name ?: 'No data yet')
                ->description('Views: ' . number_format((int) ($topProduct?->views_count ?? 0)))
                ->color('warning'),
        ];
    }
}
