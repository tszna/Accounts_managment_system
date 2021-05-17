<?php

namespace App\Models\User;

use App\Models\AdministrationEmployee\AdministrationEmployee;
use App\Models\Professor\Professor;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserRelations
{
    /**
     * @return HasOne
     */
    public function professor(): HasOne
    {
        return $this->hasOne(Professor::class);
    }

    /**
     * @return HasOne
     */
    public function administrationEmployee(): HasOne
    {

        return $this->hasOne(AdministrationEmployee::class);
    }
}
