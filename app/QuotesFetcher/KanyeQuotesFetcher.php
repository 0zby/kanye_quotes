<?php

namespace App\QuotesFetcher;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KanyeQuotesFetcher implements QuotesFetcherInterface
{
    /**
     * Fetch and return a quote of Kanye West.
     *
     * @return string The quote of Kanye West.
     * @throws ThirdPartyAPIUnavailableException If the third-party API is unavailable.
     */
    public function fetch(): string
    {
        $response = Http::get('https://api.kanye.rest');

        if (! $response->ok()) {
            Log::error('Failed to fetch Kanye West quote.', (array) $response);
            throw new ThirdPartyAPIUnavailableException();
        }

        return $this->extractQuoteFromResponse($response);
    }

    /**
     * Fetch and return multiple quotes of Kanye West asynchronously.
     *
     * @param int $numQuotes The number of quotes to fetch.
     * @return array<string> An array of quotes of Kanye West.
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
            if (! $response->ok()) {
                Log::error('Failed to fetch Kanye West quote.', (array) $response);
                throw new ThirdPartyAPIUnavailableException();
            }

            $quotes[] = $this->extractQuoteFromResponse($response);
        }

        return $quotes;
    }

    /**
     * Extract the quote from the response.
     *
     * @param Response $response The response from the API.
     * @return string The quote.
     */
    private function extractQuoteFromResponse(Response $response): string
    {
        return $response->json('quote');
    }
}
