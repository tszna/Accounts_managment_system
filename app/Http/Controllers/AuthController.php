<?php


namespace App\Http\Controllers;


use App\Helpers\AuthTokenHelper;
use App\Models\User\User;
use App\Repositories\TokenBlacklist\TokenBlacklistRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', '=', $request->get('email'))
            ->first();

        if (!$user) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'wrong email',
                ], JsonResponse::HTTP_UNAUTHORIZED)
            );
        }

        $userHasValidPassword = Hash::check($request->get('password'), $user->password);

        if (!$userHasValidPassword) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'wrong password'
                ], JsonResponse::HTTP_UNAUTHORIZED)
            );
        }

        return response()->json([
            'access_token' => AuthTokenHelper::generateToken($user),
            'type' => 'Bearer',
            'ttl' => AuthTokenHelper::TOKEN_TTL,
        ]);
    }

    /**
     * @return mixed
     */
    public function me()
    {
        ['id' => $id] = AuthTokenHelper::retrieveTokenData();

        return User::findOrFail($id);
    }

    /**
     * @return array|null
     */
    public function token()
    {
        return AuthTokenHelper::retrieveTokenData();
    }

    /**
     * @param Request $request
     * @param TokenBlacklistRepository $blacklistRepository
     * @return JsonResponse
     */
    public function logout(Request $request, TokenBlacklistRepository $blacklistRepository)
    {
        ['valid' => $valid] = AuthTokenHelper::retrieveTokenData();

        $blacklistRepository->addTokenToBlacklist($request->bearerToken(), $valid);

        return response()->json([
            'message' => 'logged_out',
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function refresh()
    {
        ['id' => $id] = AuthTokenHelper::retrieveTokenData();

        $user = User::find($id);

        return response()->json([
            'access_token' => AuthTokenHelper::generateToken($user),
            'type' => 'Bearer',
            'ttl' => AuthTokenHelper::TOKEN_TTL,
        ]);
    }
}
