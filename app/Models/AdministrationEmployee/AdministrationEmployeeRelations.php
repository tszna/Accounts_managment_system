<?php

namespace App\Models\AdministrationEmployee;

use App\Models\Address\Address;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AdministrationEmployeeRelations
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * @return BelongsTo
     */
    public function correspondenceAddress(): BelongsTo 
    {
        return $this->belongsTo(Address::class, 'correspondence_address_id');
    }

    /**
     * @return BelongsTo
     */
    public function homeAddress(): BelongsTo 
    {
        return $this->belongsTo(Address::class, 'home_address_id');
    }

}