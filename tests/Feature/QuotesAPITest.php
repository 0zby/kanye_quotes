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
