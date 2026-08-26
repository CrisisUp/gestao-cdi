<?php

namespace Tests\Feature;

use App\Models\Idoso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatorioMovimentacaoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private int $mes;
    private int $ano;
    private Carbon $inicioMes;
    private Carbon $fimMes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->mes = (int) Carbon::now()->month;
        $this->ano = (int) Carbon::now()->year;
        $this->inicioMes = Carbon::now()->startOfMonth();
        $this->fimMes = Carbon::now()->endOfMonth();
    }

    private function acessarRelatorio(): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->admin)
            ->get(route('relatorios.movimentacao', ['mes' => $this->mes, 'ano' => $this->ano]));
    }

    private function criarIdoso(array $overrides = []): Idoso
    {
        return Idoso::factory()->create(array_merge([
            'data_admissao' => $this->inicioMes->copy()->subYear()->toDateString(),
            'data_nascimento' => Carbon::now()->subYears(70)->toDateString(),
            'sexo' => 'cis_m',
        ], $overrides));
    }

    // ─── SALDO ANTERIOR ──────────────────────────────────────────

    public function test_saldo_anterior_conta_apenas_ativos_no_inicio(): void
    {
        // 2 idosos ativos antes do mês atual
        $this->criarIdoso(['nome' => 'Ativo 1']);
        $this->criarIdoso(['nome' => 'Ativo 2']);
        // 1 admitido DENTRO do mês — NÃO conta no saldo anterior
        $this->criarIdoso([
            'nome' => 'Novo',
            'data_admissao' => $this->inicioMes->copy()->addDays(5)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();
        $saldoAnterior = $response->viewData('saldoAnterior');

        $this->assertEquals(2, array_sum((array) $saldoAnterior));
    }

    public function test_saldo_anterior_exclui_desligados_antes_do_mes(): void
    {
        $this->criarIdoso(['nome' => 'Ativo']);
        $this->criarIdoso([
            'nome' => 'Desligado',
            'data_desligamento' => $this->inicioMes->copy()->subDays(10)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();
        $saldoAnterior = $response->viewData('saldoAnterior');

        $this->assertEquals(1, array_sum((array) $saldoAnterior));
    }

    public function test_saldo_anterior_exclui_soft_deleted_antes_do_mes(): void
    {
        $this->criarIdoso(['nome' => 'Ativo']);
        $deletado = $this->criarIdoso(['nome' => 'Deletado']);
        // Força deleted_at para antes do mês (->delete() usa now())
        $deletado->forceDelete();
        \Illuminate\Support\Facades\DB::table('idosos')
            ->where('id', $deletado->id)
            ->update(['deleted_at' => $this->inicioMes->copy()->subDays(5)]);

        $response = $this->acessarRelatorio();
        $saldoAnterior = $response->viewData('saldoAnterior');

        $this->assertEquals(1, array_sum((array) $saldoAnterior));
    }

    // ─── ENTRADAS E SAÍDAS ───────────────────────────────────────

    public function test_entradas_conta_apenas_admitidos_no_mes(): void
    {
        // Antigo (não é entrada)
        $this->criarIdoso(['nome' => 'Antigo']);
        // 2 entradas no mês
        $this->criarIdoso([
            'nome' => 'Entrada 1',
            'data_admissao' => $this->inicioMes->copy()->addDays(3)->toDateString(),
        ]);
        $this->criarIdoso([
            'nome' => 'Entrada 2',
            'data_admissao' => $this->inicioMes->copy()->addDays(10)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();
        $entradas = $response->viewData('entradas');

        $this->assertEquals(2, array_sum((array) $entradas));
    }

    public function test_saidas_oficiais_contam(): void
    {
        $this->criarIdoso([
            'nome' => 'Desligado',
            'data_desligamento' => $this->inicioMes->copy()->addDays(10)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();
        $saidas = $response->viewData('saidas');

        $this->assertEquals(1, array_sum((array) $saidas));
    }

    public function test_saidas_por_soft_delete_contam(): void
    {
        // Idooso admitido antes do mês, soft-deletado DENTRO do mês
        $idoso = $this->criarIdoso([
            'nome' => 'Soft Deleted',
            'data_admissao' => $this->inicioMes->copy()->subMonth()->toDateString(),
        ]);
        // Força o deleted_at para dentro do mês
        $idoso->delete();

        $response = $this->acessarRelatorio();
        $saidas = $response->viewData('saidas');

        $this->assertEquals(1, array_sum((array) $saidas));
    }

    // ─── EQUAÇÃO FUNDAMENTAL ─────────────────────────────────────

    public function test_equacao_saldo_atual_eh_ant_mais_entradas_menos_saidas(): void
    {
        // 3 idosos ativos antes do mês — 2 sem desligamento + 1 que vai sair
        $this->criarIdoso(['nome' => 'Antigo 1']);
        $this->criarIdoso(['nome' => 'Antigo 2']);
        $this->criarIdoso([
            'nome' => 'Antigo que Saiu',
            'data_admissao' => $this->inicioMes->copy()->subMonths(6)->toDateString(),
            'data_desligamento' => $this->inicioMes->copy()->addDays(10)->toDateString(),
        ]);
        // Saldo Anterior = 3 (todos ativos no início do mês)

        // 2 entradas no mês
        $this->criarIdoso([
            'nome' => 'Entrada 1',
            'data_admissao' => $this->inicioMes->copy()->addDays(1)->toDateString(),
        ]);
        $this->criarIdoso([
            'nome' => 'Entrada 2',
            'data_admissao' => $this->inicioMes->copy()->addDays(15)->toDateString(),
        ]);
        // Entradas = 2

        // 1 saída = o desligamento do "Antigo que Saiu"
        // Saídas = 1

        $response = $this->acessarRelatorio();

        $saldoAnterior = array_sum((array) $response->viewData('saldoAnterior'));
        $entradas = array_sum((array) $response->viewData('entradas'));
        $saidas = array_sum((array) $response->viewData('saidas'));
        $saldoAtual = array_sum((array) $response->viewData('saldoAtual'));

        // EQUAÇÃO: Saldo Atual = Anterior + Entradas - Saídas
        $this->assertEquals($saldoAnterior + $entradas - $saidas, $saldoAtual);
        // Valores esperados: 3 + 2 - 1 = 4
        $this->assertEquals(3, $saldoAnterior);
        $this->assertEquals(2, $entradas);
        $this->assertEquals(1, $saidas);
        $this->assertEquals(4, $saldoAtual);
    }

    public function test_equacao_com_saidas_por_soft_delete(): void
    {
        // 3 ativos antes (2 sem desligamento + 1 que vai ser soft-deletado)
        $this->criarIdoso(['nome' => 'Ativo 1']);
        $this->criarIdoso(['nome' => 'Ativo 2']);
        $deletado = $this->criarIdoso([
            'nome' => 'Deletado',
            'data_admissao' => $this->inicioMes->copy()->subMonths(2)->toDateString(),
        ]);
        // Soft delete + ajusta deleted_at para dentro do mês
        $deletado->delete();
        \Illuminate\Support\Facades\DB::table('idosos')
            ->where('id', $deletado->id)
            ->update(['deleted_at' => $this->inicioMes->copy()->addDays(5)]);

        // 1 entrada
        $this->criarIdoso([
            'nome' => 'Entrada',
            'data_admissao' => $this->inicioMes->copy()->addDays(10)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();

        $saldoAnterior = array_sum((array) $response->viewData('saldoAnterior'));
        $entradas = array_sum((array) $response->viewData('entradas'));
        $saidas = array_sum((array) $response->viewData('saidas'));
        $saldoAtual = array_sum((array) $response->viewData('saldoAtual'));

        // 3 antigos + 1 entrada - 1 soft-delete = 3
        $this->assertEquals(3, $saldoAnterior);
        $this->assertEquals(1, $entradas);
        $this->assertEquals(1, $saidas);
        $this->assertEquals(3, $saldoAtual);
        $this->assertEquals($saldoAnterior + $entradas - $saidas, $saldoAtual);
    }

    // ─── TOTAL ATENDIDOS ─────────────────────────────────────────

    public function test_total_atendidos_inclui_todos_que_passaram_pelo_servico(): void
    {
        // 2 ativos antigos
        $this->criarIdoso(['nome' => 'Ativo 1']);
        $this->criarIdoso(['nome' => 'Ativo 2']);
        // 1 admitido no mês
        $this->criarIdoso([
            'nome' => 'Novo',
            'data_admissao' => $this->inicioMes->copy()->addDays(5)->toDateString(),
        ]);
        // 1 desligado no mês (passou pelo serviço e saiu)
        $this->criarIdoso([
            'nome' => 'Saiu',
            'data_desligamento' => $this->inicioMes->copy()->addDays(10)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();

        $this->assertEquals(4, $response->viewData('totalAtendidos'));
    }

    public function test_total_atendidos_mes_sem_movimentacao(): void
    {
        $this->criarIdoso(['nome' => 'Ativo']);

        $response = $this->acessarRelatorio();

        $this->assertEquals(1, $response->viewData('totalAtendidos'));
    }

    // ─── AGRUPAMENTO POR FAIXA ETÁRIA E SEXO ─────────────────────

    public function test_agrupamento_por_faixa_etaria_correto(): void
    {
        // 62 anos = faixa 60-64, masculino
        $this->criarIdoso([
            'data_nascimento' => Carbon::now()->subYears(62)->toDateString(),
            'sexo' => 'cis_m',
        ]);
        // 77 anos = faixa 75-79, feminino
        $this->criarIdoso([
            'data_nascimento' => Carbon::now()->subYears(77)->toDateString(),
            'sexo' => 'cis_f',
        ]);

        $response = $this->acessarRelatorio();
        $saldoAtual = $response->viewData('saldoAtual');

        $this->assertEquals(1, $saldoAtual->m_60_64);
        $this->assertEquals(1, $saldoAtual->f_75_79);
        $this->assertEquals(0, $saldoAtual->m_65_69);
    }

    public function test_agrupamento_trans_m_conta_como_m(): void
    {
        $this->criarIdoso([
            'data_nascimento' => Carbon::now()->subYears(68)->toDateString(),
            'sexo' => 'trans_m',
        ]);
        $this->criarIdoso([
            'data_nascimento' => Carbon::now()->subYears(72)->toDateString(),
            'sexo' => 'trans_f',
        ]);

        $response = $this->acessarRelatorio();
        $saldoAtual = $response->viewData('saldoAtual');

        // trans_m → prefixo m_, 68 anos → 65-69
        $this->assertEquals(1, $saldoAtual->m_65_69);
        // trans_f → prefixo f_, 72 anos → 70-74
        $this->assertEquals(1, $saldoAtual->f_70_74);
    }

    public function test_agrupamento_agenero_conta_como_outros(): void
    {
        $this->criarIdoso([
            'data_nascimento' => Carbon::now()->subYears(72)->toDateString(),
            'sexo' => 'agenero',
        ]);

        $response = $this->acessarRelatorio();
        $saldoAtual = $response->viewData('saldoAtual');

        // agenero → prefixo o_, 72 anos → 70-74
        $this->assertEquals(1, $saldoAtual->o_70_74);
    }

    // ─── ESTATÍSTICAS GERAIS ─────────────────────────────────────

    public function test_stats_sexo_raca_cruzamento(): void
    {
        $this->criarIdoso([
            'sexo' => 'cis_m',
            'raca_cor' => 'branca',
            'data_nascimento' => Carbon::now()->subYears(65)->toDateString(),
        ]);
        $this->criarIdoso([
            'sexo' => 'cis_f',
            'raca_cor' => 'preta',
            'data_nascimento' => Carbon::now()->subYears(80)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['sexo_raca']['M']['branca']);
        $this->assertEquals(1, $stats['sexo_raca']['F']['preta']);
        $this->assertEquals(0, $stats['sexo_raca']['M']['preta']);
    }

    public function test_stats_grau_dependencia(): void
    {
        $this->criarIdoso([
            'grau_dependencia' => 'I',
            'data_nascimento' => Carbon::now()->subYears(63)->toDateString(),
        ]);
        $this->criarIdoso([
            'grau_dependencia' => 'I',
            'data_nascimento' => Carbon::now()->subYears(67)->toDateString(),
        ]);
        $this->criarIdoso([
            'grau_dependencia' => 'III',
            'data_nascimento' => Carbon::now()->subYears(85)->toDateString(),
        ]);

        $response = $this->acessarRelatorio();
        $stats = $response->viewData('stats');

        $this->assertEquals(2, $stats['grau_dependencia']['I']);
        $this->assertEquals(0, $stats['grau_dependencia']['II']);
        $this->assertEquals(1, $stats['grau_dependencia']['III']);
    }

    // ─── CENÁRIOS DE BORDA ───────────────────────────────────────

    public function test_mes_sem_nenhum_idoso(): void
    {
        $response = $this->acessarRelatorio();

        $this->assertEquals(0, array_sum((array) $response->viewData('saldoAnterior')));
        $this->assertEquals(0, array_sum((array) $response->viewData('entradas')));
        $this->assertEquals(0, array_sum((array) $response->viewData('saidas')));
        $this->assertEquals(0, array_sum((array) $response->viewData('saldoAtual')));
        $this->assertEquals(0, $response->viewData('totalAtendidos'));
    }
}
