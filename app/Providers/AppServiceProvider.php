<?php

namespace App\Providers;

use App\Services\NotificationService;
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
        View::composer('components.sidebar', function ($view) {
            $notificationService = app(NotificationService::class);

            // call API
            $response = $notificationService->unreadCount();
            // dd($response);die;
            if (isset($response['success']) && $response['success']) {
                $count = $response['data']['count'];
            }

            $view->with('unreadCount', $count);
        });

    }


}
