<?php

namespace App\Providers;

use App\Models\SalePost;
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
        \Illuminate\Support\Facades\View::composer('admin.layout', function ($view) {
            $count = \App\Models\SalePost::where('status', false)->count();
            $view->with('pendingPostsCount', $count);
        });
    }
}