<?php


namespace App\Models\TokenBlacklist;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TokenBlacklist
 * @package App\Models\TokenBlacklist
 */
class TokenBlacklist extends Model
{
    /**
     * @var string
     */
    protected $table = 'token_blacklist';

    /**
     * @var string[]
     */
    protected $fillable = [
        'token',
        'valid',
    ];
}
