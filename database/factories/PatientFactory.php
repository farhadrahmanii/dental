<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'x_ray_id' => $this->faker->optional()->uuid(),
            'name' => $this->faker->name(),
            'father_name' => $this->faker->optional()->name(),
            'sex' => $this->faker->optional()->randomElement(['male', 'female', 'other']),
            'age' => $this->faker->optional()->numberBetween(1, 95),
            // store a placeholder URL or null; CanvasPointer will save as URL on create
            'diagnosis' => $this->faker->optional()->url(),
            'comment' => $this->faker->optional()->paragraphs(2, true),
            'images' => $this->faker->optional()->randomElements([
                '/storage/sample1.jpg',
                '/storage/sample2.jpg',
                '/storage/sample3.jpg',
            ], $this->faker->numberBetween(0, 3)),
            'treatment' => $this->faker->optional()->sentences(2, true),
            'doctor_name' => $this->faker->name(),
        ];
    }
}


