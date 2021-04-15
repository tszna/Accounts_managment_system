<?php

namespace App\Models\AdministrationEmployee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrationEmployee extends Model
{
    use HasFactory, 
        AdministrationEmployeeRelations;

    protected $fillable = [
        'user_id',
        'correspondence_address_id',
        'home_address_id',
    ];
}
