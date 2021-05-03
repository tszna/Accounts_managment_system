<?php

namespace App\Models\User;

trait UserMethods
{
    /**
     * @return bool
     */
    public function isProfessor(): bool
    {
        return $this->professor !== null;
    }

    /**
     * @return bool
     */
    public function isAdministrationEmployee(): bool
    {
        return $this->administrationEmployee !== null;
    }
}
