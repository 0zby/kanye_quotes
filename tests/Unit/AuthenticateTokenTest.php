<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;
use App\Http\Middleware\AuthenticateToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateTokenTest extends TestCase
{
    /**
     * Test that when the middleware receives a request with a valid token, it proceeds to the next middleware.
     */
    public function test_valid_token(): void
    {
        $validToken = env('API_TOKEN');
        $middleware = new AuthenticateToken();

        $request = Request::create('/', 'GET');
        $request->headers->set('Authorization', "Bearer {$validToken}");

        $response = $middleware->handle($request, function ($req) {
            return new Response('Response from next middleware', Response::HTTP_OK);
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test that when the middleware receives a request with an invalid token, it returns an unauthorized response.
     */
    public function test_invalid_token(): void
    {
        $middleware = new AuthenticateToken();

        $request = Request::create('/', 'GET');
        $request->headers->set('Authorization', 'Bearer INVALID_TOKEN');

        $response = $middleware->handle($request, function () {});

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * Test that when the middleware receives a request without a token, it returns an unauthorized response.
     */
    public function test_null_token(): void
    {
        $middleware = new AuthenticateToken();

        $request = Request::create('/', 'GET');

        $response = $middleware->handle($request, function () {});

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
