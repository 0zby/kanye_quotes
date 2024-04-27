<?php

namespace App\QuotesFetcher;

use Illuminate\Support\Facades\Facade;

class QuotesFetcher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quotesfetcher';
    }
}
