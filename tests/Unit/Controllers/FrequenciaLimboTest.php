<?php

namespace Tests\Unit\Controllers;

use App\Models\Frequencia;
use App\Models\Idoso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrequenciaLimboTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Idooso com data_admissao futura: não deve ter frequência na data atual.
     */
    public function test_idoso_admitido_futuro_nao_aparece_hoje(): void
    {
        $amanha = Carbon::tomorrow()->toDateString();

        $idoso = Idoso::factory()->create([
            'data_admissao' => $amanha,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('frequencia.index', ['data' => Carbon::today()->toDateString()]));

        $response->assertStatus(200);
        // O idoso não deve estar na listagem — pega só os IDs dos idosos listados
        $this->assertDatabaseMissing('frequencias', [
            'idoso_id' => $idoso->id,
            'data' => Carbon::today()->toDateString(),
        ]);
    }

    /**
     * Idooso desligado antes da data: frequência não deve ser registrada.
     */
    public function test_idoso_desligado_nao_recebe_frequencia(): void
    {
        $ontem = Carbon::yesterday()->toDateString();
        $hoje = Carbon::today()->toDateString();

        $idoso = Idoso::factory()->create([
            'data_admissao' => Carbon::now()->subMonths(3)->toDateString(),
            'data_desligamento' => $ontem,
        ]);

        // Tenta registrar frequência para hoje
        $this->actingAs($this->user)->post(route('frequencia.store'), [
            'data' => $hoje,
            'presencas' => [$idoso->id => 'on'],
        ]);

        // O idoso desligado não deve ter registro de frequência
        $this->assertDatabaseMissing('frequencias', [
            'idoso_id' => $idoso->id,
            'data' => $hoje,
        ]);
    }

    /**
     * Idooso soft-deletado: frequência passada permanece no banco.
     */
    public function test_frequencia_passada_permanece_apos_soft_delete(): void
    {
        $dataPassada = Carbon::now()->subMonth()->toDateString();

        $idoso = Idoso::factory()->create();
        Frequencia::create([
            'idoso_id' => $idoso->id,
            'user_id' => $this->user->id,
            'data' => $dataPassada,
            'status' => 'presente',
            'entrada' => '08:00:00',
            'saida' => '17:00:00',
        ]);

        // Deleta o idoso
        $idoso->delete();

        // A frequência antiga deve continuar existindo
        $this->assertDatabaseHas('frequencias', [
            'idoso_id' => $idoso->id,
            'data' => $dataPassada,
            'status' => 'presente',
        ]);
    }
}
