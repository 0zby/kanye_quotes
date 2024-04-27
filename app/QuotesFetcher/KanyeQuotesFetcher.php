<?php

namespace App\QuotesFetcher;

class KanyeQuotesFetcher implements QuotesFetcherInterface
{
    /**
     * Fetch and return a quote of Kanye West.
     *
     * @return string The quote of Kanye West.
     */
    public function fetch(): string
    {
        return 'Something Kanye said...';
    }
}
