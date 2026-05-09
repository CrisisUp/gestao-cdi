<?php

namespace Database\Factories;

use App\Models\Atividade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Atividade>
 */
class AtividadeFactory extends Factory
{
    protected $model = Atividade::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $atividades = [
            'Fisioterapia Coletiva',
            'Oficina de Artesanato',
            'Coral e Musicalização',
            'Baile de Integração',
            'Ginástica Adaptada',
            'Informática Básica',
            'Horta Comunitária',
            'Culinária Terapêutica'
        ];

        return [
            'nome' => fake()->randomElement($atividades),
            'descricao' => fake()->sentence(),
            'facilitador' => fake()->name(),
            'dia_semana' => fake()->randomElement(['segunda', 'terca', 'quarta', 'quinta', 'sexta']),
            'horario' => fake()->time('H:i'),
        ];
    }
}
