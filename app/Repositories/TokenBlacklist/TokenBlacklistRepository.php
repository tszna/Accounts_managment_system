<?php


namespace App\Repositories\TokenBlacklist;


use App\Models\TokenBlacklist\TokenBlacklist;
use Illuminate\Support\Carbon;

/**
 * Class TokenBlacklistRepository
 * @package App\Repositories\TokenBlacklist
 */
class TokenBlacklistRepository
{
    /**
     * Sprawdzenie czy token jest na czarnej liście.
     *
     * @param string $token
     * @return bool
     */
    public function tokenIsOnBlacklist(string $token): bool
    {
        return TokenBlacklist::query()
            ->where('token', '=', $token)
            ->count() > 0;
    }

    /**
     * Dodanie tokenu do czarnej listy.
     *
     * @param string $token
     */
    public function addTokenToBlacklist(string $token, int $valid): void
    {
        $date = (new Carbon($valid))
            ->setTimezone(new \DateTimeZone("Europe/Warsaw"))
            ->format('Y-m-d H:i:s');

        TokenBlacklist::create([
            'token' => $token,
            'valid' => $date,
        ]);
    }

    /**
     * Usuwanie tokenów z czarnej listy których data ważności wygasła.
     *
     * @return void
     */
    public function deleteObsoleteTokensFromBlacklist(): void
    {
        TokenBlacklist::query()
            ->whereRaw('valid < CURRENT_TIMESTAMP')
            ->delete();
    }
}
