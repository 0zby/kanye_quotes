<?php

namespace App\QuotesFetcher;

use Illuminate\Support\ServiceProvider;

class QuotesFetcherServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('quotesfetcher', function ($app) {
            return new QuotesFetcherManager($app);
        });
    }
}
