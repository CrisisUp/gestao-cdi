<?php

namespace Tests\Unit\Controllers;

use App\Models\Idoso;
use App\Models\PresencaEquipe;
use App\Models\User;
use App\Models\Frequencia;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerRulesTest extends TestCase
{
    use RefreshDatabase;

    // ─── PROFILE: BLOQUEIO DE AUTO-EXCLUSÃO DE ADMIN ─────────────

    public function test_admin_nao_pode_excluir_propria_conta(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->delete('/profile', ['password' => 'password']);

        $response->assertSessionHas('error');
        $this->assertNotNull($admin->fresh());
    }

    public function test_usuario_comum_pode_excluir_conta(): void
    {
        $user = User::factory()->create(['role' => 'funcionario']);

        $response = $this->actingAs($user)
            ->delete('/profile', ['password' => 'password']);

        $response->assertRedirect('/');
        $this->assertNull($user->fresh());
    }

    // ─── PONTO: CONTROLE DE ENTRADA/SAÍDA ────────────────────────

    public function test_entrada_duplicada_no_mesmo_dia_retorna_erro(): void
    {
        $user = User::factory()->create();

        // Primeira entrada
        $this->actingAs($user)->post(route('ponto.entrada'));

        // Segunda entrada no mesmo dia
        $response = $this->actingAs($user)->post(route('ponto.entrada'));

        $response->assertSessionHas('error');
        $this->assertEquals(1, PresencaEquipe::where('user_id', $user->id)->count());
    }

    public function test_saida_sem_entrada_retorna_erro(): void
    {
        $user = User::factory()->create();

        // Tenta bater saída sem ter batido entrada
        $response = $this->actingAs($user)->post(route('ponto.saida'));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('presenca_equipes', [
            'user_id' => $user->id,
            'saida' => '!=' . null,
        ]);
    }

    public function test_saida_duplicada_retorna_erro(): void
    {
        $user = User::factory()->create();

        // Registra entrada
        $this->actingAs($user)->post(route('ponto.entrada'));

        // Primeira saída
        $this->actingAs($user)->post(route('ponto.saida'));

        // Segunda saída
        $response = $this->actingAs($user)->post(route('ponto.saida'));

        $response->assertSessionHas('error');
    }

    // ─── FREQÜÊNCIA: LÓGICA DE UPSERT ────────────────────────────

    public function test_frequencia_upsert_so_para_idosos_ativos_na_data(): void
    {
        $user = User::factory()->create();
        $data = Carbon::now()->toDateString();

        // Idooso ativo na data
        $ativo = Idoso::factory()->create([
            'data_admissao' => Carbon::now()->subMonth()->toDateString(),
        ]);

        // Idooso desligado antes da data
        $desligado = Idoso::factory()->create([
            'data_admissao' => Carbon::now()->subMonths(3)->toDateString(),
            'data_desligamento' => Carbon::now()->subDays(5)->toDateString(),
        ]);

        $this->actingAs($user)->post(route('frequencia.store'), [
            'data' => $data,
            'presencas' => [$ativo->id => 'on', $desligado->id => 'on'],
        ]);

        // Apenas o ativo deve ter registro
        $this->assertDatabaseHas('frequencias', [
            'idoso_id' => $ativo->id,
            'data' => $data,
            'status' => 'presente',
        ]);
        $this->assertDatabaseMissing('frequencias', [
            'idoso_id' => $desligado->id,
            'data' => $data,
        ]);
    }

    public function test_frequencia_preserva_horarios_existentes(): void
    {
        $user = User::factory()->create();
        $idoso = Idoso::factory()->create([
            'data_admissao' => Carbon::now()->subMonth()->toDateString(),
        ]);
        $data = Carbon::now()->toDateString();

        // Cria registro manualmente com horários customizados
        Frequencia::create([
            'idoso_id' => $idoso->id,
            'user_id' => $user->id,
            'data' => $data,
            'status' => 'presente',
            'entrada' => '09:30:00',
            'saida' => '16:00:00',
        ]);

        // Faz upsert (atualiza status, mas não deve mexer nos horários)
        $this->actingAs($user)->post(route('frequencia.store'), [
            'data' => $data,
            'presencas' => [$idoso->id => 'on'],
        ]);

        $freq = Frequencia::where('idoso_id', $idoso->id)->where('data', $data)->first();
        $this->assertEquals('09:30:00', $freq->entrada);
        $this->assertEquals('16:00:00', $freq->saida);
    }

    public function test_frequencia_sem_idosos_retorna_erro(): void
    {
        $user = User::factory()->create();
        $data = Carbon::now()->toDateString();

        // Nenhum idoso ativo
        $response = $this->actingAs($user)->post(route('frequencia.store'), [
            'data' => $data,
            'presencas' => [],
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('frequencias', 0);
    }
}
