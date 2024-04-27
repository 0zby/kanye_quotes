<?php

namespace App\QuotesFetcher;

use GuzzleHttp\Client;

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
        $data = json_decode($response->getBody()->getContents(), true)['quote'];
        return $data;
    }
}
