<?php


namespace App\Helpers;


use App\Models\User\User;
use App\Repositories\TokenBlacklist\TokenBlacklistRepository;
use function request;

class AuthTokenHelper
{
    const TOKEN_TTL = 3600;

    /**
     * @param User $user
     * @return string
     */
    public static function generateToken(User $user): string
    {
        $data = [
            'id' => $user->id,
            'issued' => time(),
            'valid' => time() + self::TOKEN_TTL,
            'algo' => 'aes-256-ctr',
            'browser' => request()->headers->get('user-agent'),
        ];

        $key = env('JWT_SECRET');

        $data['control'] = openssl_encrypt(
            json_encode($data),
            $data['algo'],
            $key,
            0,
            substr($key, 0, 16)
        );

        return base64_encode(json_encode($data));
    }

    /**
     * @return array|null
     */
    public static function retrieveTokenData(): ?array
    {
        $token = request()->bearerToken();
        $tokenData = json_decode(base64_decode($token), true);

        if ($tokenData === null) {
            return null;
        }

        $controlPartIsValid = self::validateControlPart($tokenData);

        if (!$controlPartIsValid) {
            return null;
        }

        $tokenIsNotExpired = self::checkTokenExpiration($tokenData['valid']);

        if (!$tokenIsNotExpired) {
            return null;
        }

        $tokenIsOnBlacklist = self::isTokenOnBlacklist($token);

        if ($tokenIsOnBlacklist) {
            return null;
        }

        return $tokenData;
    }

    /**
     * @param array $tokenData
     * @return bool
     */
    private static function validateControlPart(array $tokenData): bool
    {
        $control = $tokenData['control'];

        $key = env('JWT_SECRET');

        $decrypted = openssl_decrypt(
            $control,
            $tokenData['algo'],
            $key,
            0,
            substr($key, 0, 16)
        );

        if ($decrypted === false) {
            return false;
        }

        $decrypted = json_decode($decrypted, true);

        return
            $decrypted !== null &&
            $decrypted['id'] === $tokenData['id'] &&
            $decrypted['issued'] === $tokenData['issued'] &&
            $decrypted['valid'] === $tokenData['valid'];
    }

    /**
     * @param $expirationTime
     * @return bool
     */
    private static function checkTokenExpiration($expirationTime): bool
    {
        return time() <= $expirationTime;
    }

    /**
     * @param string|null $token
     * @return bool
     */
    private static function isTokenOnBlacklist(?string $token): bool
    {
        /** @var TokenBlacklistRepository $blacklistRepository */
        $blacklistRepository = app(TokenBlacklistRepository::class);

        return $blacklistRepository->tokenIsOnBlacklist($token);
    }
}
