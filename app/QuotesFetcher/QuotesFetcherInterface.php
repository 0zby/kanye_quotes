<?php

namespace App\QuotesFetcher;

interface QuotesFetcherInterface
{
    /**
     * Fetch a quote from an external resource and return it.
     *
     * @return string The quote from the external resource.
     */
    public function fetch(): string;

    /**
     * Fetch multiple quotes from an external resource and return them.
     *
     * @param int $numQuotes The number of quotes to fetch.
     * @return array<string> An array of quotes from the external resource.
     */
    public function fetchMany(int $numQuotes): array;
}
