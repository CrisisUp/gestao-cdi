<?php

namespace Database\Factories;

use App\Models\Idoso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idoso>
 */
class IdosoFactory extends Factory
{
    protected $model = Idoso::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'foto' => null,
            'data_nascimento' => fake()->dateTimeBetween('-90 years', '-60 years')->format('Y-m-d'),
            'sexo' => fake()->randomElement(['cis_m', 'cis_f', 'trans_m', 'trans_f', 'agenero', 'nao_declarado']),
            'raca_cor' => fake()->randomElement(['branca', 'preta', 'parda', 'amarela', 'indigena', 'nao_informado']),
            'grau_dependencia' => fake()->randomElement(['I', 'II', 'III']),
            'data_admissao' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'cpf' => fake()->unique()->numerify('###########'),
            'nis' => fake()->unique()->numerify('###########'),
            'contato_emergencia_nome' => fake()->name(),
            'contato_emergencia_telefone' => fake()->phoneNumber(),
            'alergias' => fake()->optional(0.3)->sentence(),
            'medicamentos' => fake()->optional(0.7)->randomElement([
                'Losartana 50mg, Omeprazol 20mg',
                'Metformina 850mg',
                'Sinvastatina 20mg, AAS 100mg',
                'Enalapril 10mg, Hidroclorotiazida 25mg',
                'Glibenclamida 5mg',
                'Puran T4 50mcg'
            ]),
            'observacoes' => fake()->optional(0.2)->paragraph(),
        ];
    }

    public function faixa60_64()
    {
        return $this->state(fn (array $attributes) => [
            'data_nascimento' => fake()->dateTimeBetween('-64 years', '-60 years')->format('Y-m-d'),
        ]);
    }

    public function faixa65_69()
    {
        return $this->state(fn (array $attributes) => [
            'data_nascimento' => fake()->dateTimeBetween('-69 years', '-65 years')->format('Y-m-d'),
        ]);
    }

    public function faixa70_74()
    {
        return $this->state(fn (array $attributes) => [
            'data_nascimento' => fake()->dateTimeBetween('-74 years', '-70 years')->format('Y-m-d'),
        ]);
    }

    public function faixa75_79()
    {
        return $this->state(fn (array $attributes) => [
            'data_nascimento' => fake()->dateTimeBetween('-79 years', '-75 years')->format('Y-m-d'),
        ]);
    }

    public function faixa80_mais()
    {
        return $this->state(fn (array $attributes) => [
            'data_nascimento' => fake()->dateTimeBetween('-95 years', '-80 years')->format('Y-m-d'),
        ]);
    }

    public function menor60()
    {
        return $this->state(fn (array $attributes) => [
            'data_nascimento' => fake()->dateTimeBetween('-59 years', '-55 years')->format('Y-m-d'),
        ]);
    }
}
