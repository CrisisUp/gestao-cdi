<?php

namespace Database\Factories;

use App\Models\Frequencia;
use App\Models\Idoso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Frequencia>
 */
class FrequenciaFactory extends Factory
{
    protected $model = Frequencia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idoso_id' => Idoso::factory(),
            'user_id' => User::factory(),
            'data' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['presente', 'ausente', 'justificado']),
            'entrada' => '08:00',
            'saida' => '17:00',
            'observacoes' => fake()->optional(0.1)->sentence(),
        ];
    }
}
