<?php

namespace App\QuotesFetcher;

use Exception;

class ThirdPartyAPIUnavailableException extends Exception
{
    public function __construct($message = 'Third-party API is unavailable', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
