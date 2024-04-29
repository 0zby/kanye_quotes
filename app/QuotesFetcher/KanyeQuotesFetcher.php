<?php

namespace App\QuotesFetcher;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KanyeQuotesFetcher implements QuotesFetcherInterface
{
    /**
     * Fetch and return a quote of Kanye West.
     *
     * @return string The quote of Kanye West.
     */
    public function fetch(): string
    {
        $response = Http::get('https://api.kanye.rest');
        return $this->extractQuoteFromResponse($response);
    }

    /**
     * Fetch and return multiple quotes of Kanye West asynchronously.
     *
     * @param int $numQuotes The number of quotes to fetch.
     * @return array An array of quotes of Kanye West.
     * @throws ThirdPartyAPIUnavailableException If the third-party API is unavailable.
     */
    public function fetchMany(int $numQuotes): array
    {
        $responses = Http::pool(function (Pool $pool) use ($numQuotes) {
            for ($i = 0; $i < $numQuotes; $i++) {
                $pool->get('https://api.kanye.rest');
            }
        });

        $quotes = [];
        foreach ($responses as $response) {
            if ($response->ok()) {
                $quotes[] = $this->extractQuoteFromResponse($response);
            } else {
                Log::error('Failed to fetch Kanye West quote.', (array) $response);
                throw new ThirdPartyAPIUnavailableException();
            }
        }

        return $quotes;
    }

    private function extractQuoteFromResponse($response): string
    {
        return $response->json('quote');
    }
}
