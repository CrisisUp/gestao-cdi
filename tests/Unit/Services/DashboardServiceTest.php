<?php

namespace Tests\Unit\Services;

use App\Models\Atividade;
use App\Models\Idoso;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    private DashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DashboardService();
    }

    /**
     * Estatísticas de grau de dependência devem contar apenas idosos ativos.
     */
    public function test_grau_dependencia_stats_so_conta_ativos(): void
    {
        // 2 idosos com grau I (ativos)
        Idoso::factory()->count(2)->create(['grau_dependencia' => 'I']);
        // 1 idoso com grau II (ativo)
        Idoso::factory()->create(['grau_dependencia' => 'II']);
        // 1 idoso com grau III — desligado (não deve contar)
        Idoso::factory()->create([
            'grau_dependencia' => 'III',
            'data_desligamento' => Carbon::now()->subMonth(),
        ]);

        $stats = $this->service->getGrauDependenciaStats();

        $this->assertEquals(2, $stats['I']);
        $this->assertEquals(1, $stats['II']);
        $this->assertArrayNotHasKey('III', $stats);
    }

    /**
     * Estatísticas de faixa etária devem agrupar corretamente e remover zeras.
     */
    public function test_faixa_etaria_stats_por_grupo(): void
    {
        Idoso::factory()->faixa60_64()->create();
        Idoso::factory()->faixa60_64()->create();
        Idoso::factory()->faixa75_79()->create();
        Idoso::factory()->faixa80_mais()->create();

        $stats = $this->service->getFaixaEtariaStats();

        $this->assertEquals(2, $stats['60-64 anos']);
        $this->assertEquals(1, $stats['75-79 anos']);
        $this->assertEquals(1, $stats['80 anos ou mais']);
        // Faixas sem idosos devem ser removidas (array_filter)
        $this->assertArrayNotHasKey('65-69 anos', $stats);
        $this->assertArrayNotHasKey('70-74 anos', $stats);
        $this->assertArrayNotHasKey('Menor de 60 anos', $stats);
    }

    /**
     * Estatísticas de atividades devem somar idosos ativos vinculados por atividade.
     */
    public function test_atividades_stats_soma_idosos_por_atividade(): void
    {
        $user = User::factory()->create();

        $fisio = Atividade::factory()->create([
            'nome' => 'Fisioterapia',
            'dia_semana' => 'segunda',
        ]);
        $arte = Atividade::factory()->create([
            'nome' => 'Artesanato',
            'dia_semana' => 'terca',
        ]);

        // 3 idosos ativos na Fisioterapia
        $idososFisio = Idoso::factory()->count(3)->create();
        $fisio->idosos()->attach($idososFisio->pluck('id'));

        // 1 idoso ativo no Artesanato + 1 desligado (não deve contar)
        $idosoAtivo = Idoso::factory()->create();
        $idosoDesligado = Idoso::factory()->create([
            'data_desligamento' => Carbon::now()->subMonth(),
        ]);
        $arte->idosos()->attach([$idosoAtivo->id, $idosoDesligado->id]);

        $stats = $this->service->getAtividadesStats();

        $this->assertEquals(3, $stats['Fisioterapia (Segunda)']);
        $this->assertEquals(1, $stats['Artesanato (Terca)']);
    }

    /**
     * Movimentação mensal deve retornar 6 meses com contagens de admissões e desligamentos.
     */
    public function test_movimentacao_mensal_6_meses(): void
    {
        $mesAtual = Carbon::now();

        // 2 admissões no mês atual
        Idoso::factory()->count(2)->create([
            'data_admissao' => $mesAtual->copy()->startOfMonth()->addDays(5)->toDateString(),
        ]);

        // 1 admissão no mês anterior
        $mesAnterior = $mesAtual->copy()->subMonth();
        Idoso::factory()->create([
            'data_admissao' => $mesAnterior->copy()->startOfMonth()->addDays(10)->toDateString(),
        ]);

        // 1 desligamento no mês atual
        Idoso::factory()->create([
            'data_admissao' => $mesAtual->copy()->subMonths(3)->toDateString(),
            'data_desligamento' => $mesAtual->copy()->startOfMonth()->addDays(15)->toDateString(),
        ]);

        $resultado = $this->service->getMovimentacaoMensal();

        // Deve ter exatamente 6 meses
        $this->assertCount(6, $resultado['labels']);
        $this->assertCount(6, $resultado['admissoes']);
        $this->assertCount(6, $resultado['desligamentos']);

        // Último mês (atual): 2 admissões, 1 desligamento
        $indiceAtual = array_key_last($resultado['labels']);
        $this->assertEquals(2, $resultado['admissoes'][$indiceAtual]);
        $this->assertEquals(1, $resultado['desligamentos'][$indiceAtual]);

        // Mês anterior: 1 admissão, 0 desligamentos
        $indiceAnterior = $indiceAtual - 1;
        $this->assertEquals(1, $resultado['admissoes'][$indiceAnterior]);
        $this->assertEquals(0, $resultado['desligamentos'][$indiceAnterior]);
    }
}
