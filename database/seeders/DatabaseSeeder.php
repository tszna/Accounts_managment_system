<?php

namespace Database\Seeders;

use App\Models\Address\Address;
use App\Models\AdministrationEmployee\AdministrationEmployee;
use App\Models\Professor\Professor;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(10)
            ->create();

        Address::factory()
            ->count(25)
            ->create();

        $professors = Professor::factory()
            ->count(4)
            ->create();

        AdministrationEmployee::factory()
            ->count(4)
            ->create();

        AdministrationEmployee::factory()
            ->create([
                'user_id' => $professors->first()->user_id,
            ]);
    }
}
