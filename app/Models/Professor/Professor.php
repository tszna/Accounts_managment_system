<?php

namespace App\Models\Professor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory,
        ProfessorRelations;

    protected $fillable = [
        'user_id',
        'phone',
        'level_of_education',
    ];
}
