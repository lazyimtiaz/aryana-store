<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $navigationCategories = Category::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with([
                    'children' => function ($query) {
                        $query
                            ->where('is_active', true)
                            ->orderBy('sort_order');
                    },
                ])
                ->orderBy('sort_order')
                ->get();

            $view->with('navigationCategories', $navigationCategories);
        });
    }
}
