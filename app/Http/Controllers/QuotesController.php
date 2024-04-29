<?php

namespace App\Http\Controllers;

use App\QuotesFetcher\QuotesFetcher;
use App\QuotesFetcher\ThirdPartyAPIUnavailableException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class QuotesController extends Controller {
    public function getKanyeQuotes()
    {
        try {
            $quotes = Cache::rememberForever(
                'quotes.kanye.endpoint',
                fn () => QuotesFetcher::driver('kanye')->fetchMany(5)
            );
        } catch (ThirdPartyAPIUnavailableException $exception) {
            return response()->json([
                'error' => 'Third-party API is unavailable'],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return response()->json($quotes);
    }

    public function resetKanyeQuotes()
    {
        Cache::forget('quotes.kanye.endpoint');
        return $this->getKanyeQuotes();
    }
}
