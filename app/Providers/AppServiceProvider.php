<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Event;
use App\Events\ProspectSubmitted;
use App\Events\InvoiceGenerated;
use App\Events\ManuscriptAccepted;
use App\Events\ManuscriptRejected;
use App\Events\PaymentSuccessful;
use App\Events\ProjectStageUpdated;
use App\Listeners\SendAuthorNotifications;

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
        Paginator::useBootstrapFive();
        
        Event::listen(ProspectSubmitted::class, [SendAuthorNotifications::class, 'handle']);
        Event::listen(InvoiceGenerated::class, [SendAuthorNotifications::class, 'handle']);
        Event::listen(ManuscriptAccepted::class, [SendAuthorNotifications::class, 'handle']);
        Event::listen(ManuscriptRejected::class, [SendAuthorNotifications::class, 'handle']);
        Event::listen(PaymentSuccessful::class, [SendAuthorNotifications::class, 'handle']);
        Event::listen(ProjectStageUpdated::class, [SendAuthorNotifications::class, 'handle']);
    }
}
