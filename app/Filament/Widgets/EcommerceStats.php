<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EcommerceStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Orders',
                Order::count()
            )
                ->description('All orders')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make(
                'Customers',
                User::where('role', 'customer')->count()
            )
                ->description('Registered customers')
                ->icon('heroicon-o-users'),

            Stat::make(
                'Products',
                Product::count()
            )
                ->description('Catalog products')
                ->icon('heroicon-o-cube'),

            Stat::make(
                'Pending Reviews',
                Review::where('is_approved', false)->count()
            )
                ->description('Awaiting moderation')
                ->icon('heroicon-o-star'),
        ];
    }
}
