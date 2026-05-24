<?php

namespace App\Providers;

use App\Models\CreativityType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function (ViewInstance $view): void {
            $types = collect();

            if (Schema::hasTable('creativity_types')) {
                $types = CreativityType::query()->orderBy('name')->get();
            }

            $view->with('menuTypes', $types);
        });
    }
}
