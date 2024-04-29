<?php

namespace Tests\Feature;

use App\QuotesFetcher\KanyeQuotesFetcher;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class QuotesAPITest extends TestCase
{
    private const EXAMPLE_QUOTES = [
        'quote1',
        'quote2',
        'quote3',
        'quote4',
        'quote5',
    ];

    private const EXAMPLE_QUOTES_ALTERNATE = [
        'quote5',
        'quote6',
        'quote7',
        'quote8',
        'quote9',
    ];

    /**
     * Test the Kanye endpoint is able to retreive an array of 5 quotes.
     */
    public function test_five_kanye_quotes_are_retreived(): void
    {
        $this->mock(KanyeQuotesFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchMany')
                ->once()
                ->andReturn(self::EXAMPLE_QUOTES);
        });

        $response = $this->withoutMiddleware()
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(self::EXAMPLE_QUOTES);
    }

    /**
     * Test that after patching to the refresh endpoint, we start receiving new quotes.
     */
    public function test_quotes_are_refreshed(): void
    {
        $this->mock(KanyeQuotesFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchMany')
                ->twice()
                ->andReturn(self::EXAMPLE_QUOTES, self::EXAMPLE_QUOTES_ALTERNATE);
        });

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
        $this->mock(KanyeQuotesFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchMany')
                ->never();
        });

        $response = $this->withoutToken()
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test the Kanye endpoint is accessible when using a valid token.
     */
    public function test_getting_kanye_quotes_with_valid_token_is_possible(): void
    {
        $this->mock(KanyeQuotesFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchMany')
                ->once()
                ->andReturn(self::EXAMPLE_QUOTES);
        });

        $response = $this->withToken(env('API_TOKEN'), 'Bearer')
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test the reset Kanye quotes endpoint is inaccessible when using an invalid token.
     */
    public function test_resetting_kanye_quotes_without_valid_token_is_impossible(): void
    {
        $this->mock(KanyeQuotesFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchMany')
                ->never();
        });

        $response = $this->withoutToken()
            ->get(route('quotes.kanye.reset'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test the resetting Kanye quotes endpoint is accessible when using a valid token.
     */
    public function test_resetting_kanye_quotes_with_valid_token_is_possible(): void
    {
        $this->mock(KanyeQuotesFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchMany')
                ->once()
                ->andReturn(self::EXAMPLE_QUOTES);
        });

        $response = $this->withToken(env('API_TOKEN'), 'Bearer')
            ->patch(route('quotes.kanye.reset'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that when the external API is down that our API gives sensible result.
     */
    public function test_internal_server_error_with_kanye_api(): void
    {
        Http::fake([
            'https://api.kanye.rest' => Http::sequence()
                ->push(['quote' => 'Something Kanye said'], 200)
                ->push(['quote' => 'Something Kanye said'], 200)
                ->push(['quote' => 'Something Kanye said'], 200)
                ->push(['quote' => 'Something Kanye said'], 200)
                ->pushStatus(500),
        ]);

        $response = $this->withoutMiddleware()
            ->get(route('quotes.kanye.get'));

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertExactJson(['error' => 'Third-party API is unavailable']);
    }
}
