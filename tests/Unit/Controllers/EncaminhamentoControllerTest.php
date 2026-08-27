<?php

namespace Tests\Unit\Controllers;

use App\Models\Encaminhamento;
use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncaminhamentoControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Busca por nome deve incluir idosos soft-deletados (withTrashed).
     */
    public function test_busca_encaminhamento_inclui_idosos_deletados(): void
    {
        // Cria idoso, vincula encaminhamento, depois deleta o idoso
        $idoso = Idoso::factory()->create(['nome' => 'Maria Soft Deleted']);
        $enc = Encaminhamento::factory()->create([
            'idoso_id' => $idoso->id,
            'user_id' => $this->user->id,
        ]);
        $idoso->delete();

        $response = $this->actingAs($this->user)
            ->get(route('encaminhamento.index', ['search' => 'Maria']));

        $response->assertStatus(200);
        $response->assertSee('Maria Soft Deleted');
    }

    /**
     * user_id é atribuído automaticamente ao criar encaminhamento.
     */
    public function test_store_atribui_user_id_automaticamente(): void
    {
        $idoso = Idoso::factory()->create();

        $this->actingAs($this->user)->post(route('encaminhamento.store'), [
            'idoso_id' => $idoso->id,
            'instituicao_destino' => 'UBS Norte',
            'motivo' => 'Avaliação',
            'prioridade' => 'rotina',
            'data_encaminhamento' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('encaminhamentos', [
            'idoso_id' => $idoso->id,
            'user_id' => $this->user->id,
            'instituicao_destino' => 'UBS Norte',
        ]);
    }
}
