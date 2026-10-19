<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatusOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Pending',
                Order::where('order_status', 'pending')->count()
            )
                ->icon('heroicon-o-clock'),

            Stat::make(
                'Processing',
                Order::whereIn('order_status', [
                    'confirmed',
                    'processing',
                    'packed',
                ])->count()
            )
                ->icon('heroicon-o-arrow-path'),

            Stat::make(
                'Shipped',
                Order::where('order_status', 'shipped')->count()
            )
                ->icon('heroicon-o-truck'),

            Stat::make(
                'Delivered',
                Order::where('order_status', 'delivered')->count()
            )
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
