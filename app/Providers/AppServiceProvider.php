<?php

namespace App\Providers;

use App\Http\Middleware\LibrarianMiddleware;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;  
use App\Helpers\NepaliCalendar;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Route::aliasMiddleware('librarian', LibrarianMiddleware::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Blade::directive('bs', function ($expression) {
            return "<?php echo \\App\\Helpers\\NepaliCalendar::format($expression); ?>";
        });
 
        Blade::directive('bsDate', function ($expression) {
            return "<?php echo \\App\\Helpers\\NepaliCalendar::format($expression, 'd M Y BS'); ?>";
        });
 
        Blade::directive('bsNow', function () {
            return "<?php
                \$__t = \\App\\Helpers\\NepaliCalendar::today();
                echo \$__t['day'].' '.\$__t['month_name'].' '.\$__t['year'].' B.S.';
            ?>";
        });
 
        Blade::directive('bsYear', function ($expression) {
            return "<?php echo \\App\\Helpers\\NepaliCalendar::format($expression, 'Y'); ?>";
        });
        // In AppServiceProvider.php
Blade::directive('bsPast', function ($days) {
    return "<?php 
        \$pastAd = now()->subDays($days);
        echo \\App\\Helpers\\NepaliCalendar::format(\$pastAd, 'd M Y BS'); 
    ?>";
});
    }
}
