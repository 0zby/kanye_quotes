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

    public function fetchMany(int $numQuotes): array;
}
