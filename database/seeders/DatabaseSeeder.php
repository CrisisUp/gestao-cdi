<?php

namespace Database\Seeders;

use App\Models\Atividade;
use App\Models\Encaminhamento;
use App\Models\Frequencia;
use App\Models\Idoso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar Usuário Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@cdi.com.br'],
            [
                'name' => 'Administrador CDI',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Criar Atividades
        $atividades = [
            ['nome' => 'Fisioterapia Coletiva', 'dia_semana' => 'segunda', 'horario' => '09:00'],
            ['nome' => 'Oficina de Artesanato', 'dia_semana' => 'terca', 'horario' => '14:00'],
            ['nome' => 'Coral e Musicalização', 'dia_semana' => 'quarta', 'horario' => '10:00'],
            ['nome' => 'Baile de Integração', 'dia_semana' => 'sexta', 'horario' => '15:00'],
            ['nome' => 'Ginástica Adaptada', 'dia_semana' => 'quinta', 'horario' => '08:30'],
            ['nome' => 'Horta Comunitária', 'dia_semana' => 'quarta', 'horario' => '16:00'],
        ];

        foreach ($atividades as $ativ) {
            Atividade::factory()->create($ativ);
        }

        $todasAtividades = Atividade::all();

        // 3. Criar Idosos por Faixa Etária
        $faixas = [
            'menor60',
            'faixa60_64',
            'faixa65_69',
            'faixa70_74',
            'faixa75_79',
            'faixa80_mais'
        ];

        foreach ($faixas as $faixa) {
            // Criar 3 idosos para cada faixa
            Idoso::factory()->count(3)->$faixa()->create()->each(function ($idoso) use ($todasAtividades, $admin) {
                
                // Vincular a 2-3 atividades aleatórias
                $idoso->atividades()->attach(
                    $todasAtividades->random(rand(2, 3))->pluck('id')->toArray()
                );

                // Gerar algumas frequências para o mês atual
                $dataInicio = Carbon::now()->startOfMonth();
                $hoje = Carbon::now();

                for ($d = 0; $d <= $hoje->day; $d++) {
                    $data = $dataInicio->copy()->addDays($d);
                    // Apenas dias de semana
                    if (!$data->isWeekend()) {
                        Frequencia::factory()->create([
                            'idoso_id' => $idoso->id,
                            'user_id' => $admin->id,
                            'data' => $data->toDateString(),
                            'status' => (rand(1, 10) > 2) ? 'presente' : 'ausente'
                        ]);
                    }
                }

                // Gerar encaminhamentos (0 a 2 por idoso)
                Encaminhamento::factory()->count(rand(0, 2))->create([
                    'idoso_id' => $idoso->id,
                    'user_id' => $admin->id
                ]);
            });
        }

        // Criar também alguns idosos desligados para o gráfico de movimentação
        Idoso::factory()->count(4)->create([
            'data_desligamento' => Carbon::now()->subMonths(rand(1, 3))->toDateString(),
            'motivo_desligamento' => 'Mudança de endereço / Cidade'
        ]);

        $this->command->info('Banco de dados populado com sucesso!');
        $this->command->info('Login: admin@cdi.com.br');
        $this->command->info('Senha: password');
    }
}
