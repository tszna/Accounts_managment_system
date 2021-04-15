<?php

namespace Database\Factories\AdministrationEmployee;

use App\Models\AdministrationEmployee\AdministrationEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdministrationEmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdministrationEmployee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 10),
            'correspondence_address_id' => $this->faker->numberBetween(1, 25),
            'home_address_id' => $this->faker->numberBetween(1, 25),
        ];
    }
}
