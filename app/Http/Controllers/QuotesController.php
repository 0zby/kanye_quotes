<?php

namespace App\Http\Controllers;

use \App\QuotesFetcher\QuotesFetcher;
use Illuminate\Support\Facades\Cache;

class QuotesController extends Controller {
    public function getKanyeQuotes()
    {
        $quotes = Cache::rememberForever('quotes.kanye.endpoint', fn () => QuotesFetcher::driver('kanye')->fetchMany(5));
        return response()->json($quotes);
    }
}
