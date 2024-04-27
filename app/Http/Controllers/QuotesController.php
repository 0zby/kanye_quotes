<?php

namespace App\Http\Controllers;

use \App\QuotesFetcher\QuotesFetcher;

class QuotesController extends Controller {
    public function getKanyeQuotes()
    {
        $quotes = [];

        for ($i = 0; $i < 5; $i++) {
            $quotes[] = QuotesFetcher::driver('kanye')->fetch();
        }

        return response()->json($quotes);
    }
}
