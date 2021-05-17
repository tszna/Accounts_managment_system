<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrowserCheck
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
        if (Auth::check()) {
            $userAgent = \auth()->payload()->get('useragent');

            if ($userAgent !== $request->headers->get('user-agent')) {
                throw new HttpResponseException(
                    response()->json([
                        'message' => 'wrong browser',
                    ], JsonResponse::HTTP_UNAUTHORIZED)
                );
            }
        }

        return $next($request);
    }
}
