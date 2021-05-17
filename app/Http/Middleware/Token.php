<?php

namespace App\Http\Middleware;

use App\Helpers\AuthTokenHelper;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tokenIsValid = AuthTokenHelper::retrieveTokenData() !== null;

        if (!$tokenIsValid) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'invalid_token',
                ], JsonResponse::HTTP_UNAUTHORIZED)
            );
        }

        return $next($request);
    }
}
