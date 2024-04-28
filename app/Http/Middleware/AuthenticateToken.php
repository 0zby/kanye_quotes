<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    /**
     * Handle an incoming request, only proceeding when a valid token is present.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $this->isValidToken($token)) {
            return response()->json(
                ['error' => 'Unauthorised. Please authorise using a valid token.'],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        return $next($request);
    }

    /**
     * Check a given token against the one set in the environment file.
     *
     * @param ?string The token given in the request.
     * @return bool Whether the token matches the application's token.
     */
    private function isValidToken(?string $token): bool
    {
        return $token === env('API_TOKEN');
    }
}
