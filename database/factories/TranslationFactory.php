<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;
    public function definition(): array {
        return [
            'key' => 'key_' . $this->faker->unique()->numberBetween(1, 200000),
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
            'content' => $this->faker->sentence(),
            'tag' => $this->faker->randomElement(['web', 'mobile', 'desktop']),
        ];
    }
}
