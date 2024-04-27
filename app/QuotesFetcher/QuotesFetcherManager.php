<?php

namespace App\QuotesFetcher;

use Illuminate\Support\Manager;

class QuotesFetcherManager extends Manager
{
    /**
     * Get the name of the driver used when none is provided.
     *
     * @return string The driver name.
     */
    public function getDefaultDriver(): string
    {
        return 'kanye';
    }

    /**
     * Create a driver for fetching a quote from Kanye West.
     *
     * @return QuotesFetcherInterface The driver resonsible for fetching the quote.
     */
    public function createKanyeDriver(): QuotesFetcherInterface
    {
        return new KanyeQuotesFetcher();
    }
}
