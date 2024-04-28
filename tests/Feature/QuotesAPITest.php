<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class QuotesAPITest extends TestCase
{
    /**
     * Test the Kanye endpoint is able to retreive an array of 5 quotes.
     */
    public function test_five_kanye_quotes_are_retreived(): void
    {
        $response = $this->withoutMiddleware()
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonIsArray();
        $response->assertJsonCount(5);
    }

    /**
     * Test that after patching to the refresh endpoint, we start receiving new quotes.
     */
    public function test_quotes_are_refreshed(): void
    {
        // Hit the fetch endpoint to form quote cache.
        $initialResponse = $this->withoutMiddleware()->get(route('quotes.kanye.get'));
        $initialQuotes = json_decode($initialResponse->getContent(), true);

        // Hit the reset endpoint to force new quotes to be fetched.
        $this->withoutMiddleware()->patch(route('quotes.kanye.reset'));

        // Hit the fetch endpoint again to see new results.
        $updatedResponse = $this->withoutMiddleware()->get(route('quotes.kanye.get'));
        $updatedQuotes = json_decode($updatedResponse->getContent(), true);

        $this->assertNotSame($updatedQuotes, $initialQuotes);
    }

    /**
     * Test the Kanye endpoint is inaccessible when using an invalid token.
     */
    public function test_getting_kanye_quotes_without_valid_token_is_impossible(): void
    {
        $response = $this->withoutToken()
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test the Kanye endpoint is accessible when using a valid token.
     */
    public function test_getting_kanye_quotes_with_valid_token_is_possible(): void
    {
        $response = $this->withToken(env('API_TOKEN'), 'Bearer')
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
