<?php

namespace Database\Factories;

use App\Models\Encaminhamento;
use App\Models\Idoso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Encaminhamento>
 */
class EncaminhamentoFactory extends Factory
{
    protected $model = Encaminhamento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $destinos = [
            'UPA Central',
            'Hospital Municipal',
            'Posto de Saúde (UBS)',
            'CRAS Regional',
            'INSS',
            'Clínica de Fisioterapia',
            'Centro de Especialidades'
        ];

        return [
            'idoso_id' => Idoso::factory(),
            'user_id' => User::factory(),
            'instituicao_destino' => fake()->randomElement($destinos),
            'especialidade' => fake()->optional(0.5)->randomElement(['Cardiologia', 'Oftalmologia', 'Geriatria', 'Dentista', 'Fisioterapia']),
            'motivo' => fake()->sentence(),
            'prioridade' => fake()->randomElement(['urgente', 'programado', 'rotina']),
            'data_encaminhamento' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['aberto', 'concluido', 'cancelado']),
            'observacoes_retorno' => fake()->optional(0.2)->sentence(),
        ];
    }
}
