<?php

namespace Database\Factories\Professor;

use App\Models\Professor\Professor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessorFactory extends Factory
{
    const LEVELS_OF_EDUCATION = [
        'Szkoła podstawowa',
        'Szkoła średnia',
        'Szkoła wyższa',
    ];

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Professor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 10),
            'phone' => $this->faker->e164PhoneNumber,
            'level_of_education' => $this->faker->randomElement(self::LEVELS_OF_EDUCATION),
        ];
    }
}
