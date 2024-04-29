<?php

namespace App\Http\Controllers;

use App\QuotesFetcher\QuotesFetcher;
use App\QuotesFetcher\ThirdPartyAPIUnavailableException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class QuotesController extends Controller {
    /**
     * Get 5 Kanye West quotes and return them in a JSON response, from the cache if available.
     */
    public function getKanyeQuotes(): JsonResponse
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

    /**
     * Reset the cache of Kanye West quotes and return the new quotes in a JSON response.
     */
    public function resetKanyeQuotes(): JsonResponse
    {
        Cache::forget('quotes.kanye.endpoint');
        return $this->getKanyeQuotes();
    }
}
