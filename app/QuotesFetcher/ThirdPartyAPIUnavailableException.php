<?php

namespace App\QuotesFetcher;

use Exception;
use Illuminate\Http\Response;

class ThirdPartyAPIUnavailableException extends Exception
{
    public function __construct($message = 'Third-party API is unavailable', $code = Response::HTTP_SERVICE_UNAVAILABLE, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
