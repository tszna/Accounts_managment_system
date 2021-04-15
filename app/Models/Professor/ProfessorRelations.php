<?php

namespace App\Models\Professor;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ProfessorRelations
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}