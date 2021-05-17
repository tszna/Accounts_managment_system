<?php


namespace App\Http\Middleware;


use App\Helpers\AuthTokenHelper;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TokenBrowserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        ['browser' => $userAgent] = AuthTokenHelper::retrieveTokenData();

        $currentUserAgent = $request->headers->get('user-agent');

        if ($userAgent !== $currentUserAgent) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'browser change',
                ], JsonResponse::HTTP_UNAUTHORIZED)
            );
        }

        return $next($request);
    }
}
