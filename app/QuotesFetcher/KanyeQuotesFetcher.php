<?php

namespace App\QuotesFetcher;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class KanyeQuotesFetcher implements QuotesFetcherInterface
{
    /**
     * Fetch and return a quote of Kanye West.
     *
     * @return string The quote of Kanye West.
     */
    public function fetch(): string
    {
        $client = new Client(['base_uri' => 'https://api.kanye.rest']);
        $response = $client->get('');
        return $this->extractQuoteFromResponse($response);
    }

    /**
     * Fetch and return multiple quotes of Kanye West asynchronously.
     *
     * @param int $numQuotes The number of quotes to fetch.
     * @return array An array of quotes of Kanye West.
     */
    public function fetchMany(int $numQuotes): array
    {
        $client = new Client(['base_uri' => 'https://api.kanye.rest']);

        $promises = [];
        for ($i = 0; $i < $numQuotes; $i++) {
            $promises[] = $client->getAsync('');
        }

        $results = Promise\Utils::settle($promises)->wait();

        $quotes = [];
        foreach ($results as $result) {
            if ($result['state'] === 'fulfilled') {
                $response = $result['value'];
                $quotes[] = $this->extractQuoteFromResponse($response);
            } else {
                // Handle failed promises
            }
        }

        return $quotes;
    }

    private function extractQuoteFromResponse($response): string
    {
        return json_decode($response->getBody()->getContents(), true)['quote'];
    }
}
