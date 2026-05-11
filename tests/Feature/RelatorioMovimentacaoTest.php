<?php

namespace Tests\Feature;

use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class RelatorioMovimentacaoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar um usuário administrador para acessar as rotas
        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    /**
     * Testa se o saldo anterior é calculado corretamente.
     */
    public function test_saldo_anterior_calculo_correto()
    {
        // Cenário: Estamos em Março de 2026.
        // O saldo anterior deve ser quem estava ativo em 28/02/2026.

        // 1. Idoso admitido ano passado (Deve contar no saldo anterior)
        Idoso::factory()->create([
            'nome' => 'Idoso Antigo',
            'data_admissao' => '2025-01-01',
            'data_nascimento' => '1960-01-01', // 66 anos em 2026
            'sexo' => 'cis_m'
        ]);

        // 2. Idoso admitido em Fevereiro (Deve contar no saldo anterior)
        Idoso::factory()->create([
            'nome' => 'Idoso Fevereiro',
            'data_admissao' => '2026-02-15',
            'data_nascimento' => '1950-01-01', // 76 anos em 2026
            'sexo' => 'cis_f'
        ]);

        // 3. Idoso admitido em Março (NÃO deve contar no saldo anterior)
        Idoso::factory()->create([
            'nome' => 'Idoso Março',
            'data_admissao' => '2026-03-05',
            'data_nascimento' => '1940-01-01',
            'sexo' => 'cis_f'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('relatorios.movimentacao', ['mes' => 3, 'ano' => 2026]));

        $response->assertStatus(200);

        // No saldo anterior (Discriminação: SALDO ANTERIOR), devemos ter 2 idosos.
        // O controlador agrupa por faixa etária.
        $data = $response->viewData('saldoAnterior');

        // 1 idoso M de 66 anos (m_65_69)
        // 1 idoso F de 76 anos (f_75_79)
        $this->assertEquals(1, $data->m_65_69);
        $this->assertEquals(1, $data->f_75_79);

        // Total geral do saldo anterior na view deve ser 2
        $response->assertSee('SALDO ANTERIOR');
    }

    /**
     * Testa o cálculo de entradas e saídas no período.
     */
    public function test_entradas_e_saidas_periodo_calculo_correto()
    {
        // Cenário: Março de 2026

        // 1. Entrada em Março
        Idoso::factory()->create([
            'nome' => 'Novo em Março',
            'data_admissao' => '2026-03-10',
            'data_nascimento' => '1966-03-10', // 60 anos
            'sexo' => 'cis_m'
        ]);

        // 2. Saída em Março (Admitido antes, desligado em Março)
        Idoso::factory()->create([
            'nome' => 'Saiu em Março',
            'data_admissao' => '2025-01-01',
            'data_desligamento' => '2026-03-15',
            'data_nascimento' => '1946-01-01', // 80 anos
            'sexo' => 'cis_f'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('relatorios.movimentacao', ['mes' => 3, 'ano' => 2026]));

        $response->assertStatus(200);

        $entradas = $response->viewData('entradas');
        $saidas = $response->viewData('saidas');

        $this->assertEquals(1, $entradas->m_60_64);
        $this->assertEquals(1, $saidas->f_80_mais);
    }

    /**
     * Testa o balanço final (Saldo Atual).
     */
    public function test_saldo_atual_matematicamente_correto()
    {
        // 1 Antigo (Saldo Anterior = 1)
        Idoso::factory()->create(['data_admissao' => '2025-01-01', 'data_nascimento' => '1960-01-01', 'sexo' => 'cis_m']);

        // 2 Entradas (Total Entradas = 2)
        Idoso::factory()->create(['data_admissao' => '2026-03-01', 'data_nascimento' => '1960-01-01', 'sexo' => 'cis_m']);
        Idoso::factory()->create(['data_admissao' => '2026-03-15', 'data_nascimento' => '1960-01-01', 'sexo' => 'cis_m']);

        // 1 Saída (Total Saídas = 1)
        Idoso::factory()->create([
            'data_admissao' => '2025-01-01',
            'data_desligamento' => '2026-03-10',
            'data_nascimento' => '1960-01-01',
            'sexo' => 'cis_m'
        ]);

        // Balanço: 2 (ativos no início) + 2 (entradas) - 1 (saída) = 3 (saldo atual)
        // Nota: O "Antigo" e o "Que saiu" ambos contam no saldo anterior.

        $response = $this->actingAs($this->admin)
            ->get(route('relatorios.movimentacao', ['mes' => 3, 'ano' => 2026]));

        $saldoAtual = $response->viewData('saldoAtual');
        $totalAtual = array_sum((array)$saldoAtual);

        $this->assertEquals(3, $totalAtual);
    }

    /**
     * Verifica as estatísticas de perfil dos usuários atendidos.
     */
    public function test_estatisticas_perfil_atendidos()
    {
        // Criar idosos with different profiles to test the grouping
        Idoso::factory()->create(['sexo' => 'cis_m', 'raca_cor' => 'branca', 'grau_dependencia' => 'I', 'data_admissao' => '2026-03-01', 'data_nascimento' => '1960-01-01']);
        Idoso::factory()->create(['sexo' => 'cis_f', 'raca_cor' => 'preta', 'grau_dependencia' => 'II', 'data_admissao' => '2026-03-01', 'data_nascimento' => '1960-01-01']);
        Idoso::factory()->create(['sexo' => 'trans_f', 'raca_cor' => 'parda', 'grau_dependencia' => 'III', 'data_admissao' => '2026-03-01', 'data_nascimento' => '1960-01-01']);

        $response = $this->actingAs($this->admin)
            ->get(route('relatorios.movimentacao', ['mes' => 3, 'ano' => 2026]));

        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['sexo']['M']); // cis_m
        $this->assertEquals(2, $stats['sexo']['F']); // cis_f + trans_f

        $this->assertEquals(1, $stats['raca_cor']['branca']);
        $this->assertEquals(1, $stats['raca_cor']['preta']);
        $this->assertEquals(1, $stats['raca_cor']['parda']);

        $this->assertEquals(1, $stats['grau_dependencia']['I']);
        $this->assertEquals(1, $stats['grau_dependencia']['II']);
        $this->assertEquals(1, $stats['grau_dependencia']['III']);
    }
}
