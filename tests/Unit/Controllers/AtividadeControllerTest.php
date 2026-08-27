<?php

namespace Tests\Unit\Controllers;

use App\Models\Atividade;
use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtividadeControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * syncWithoutDetaching não deve criar vínculos duplicados.
     */
    public function test_vincular_idoso_nao_cria_duplicata(): void
    {
        $atividade = Atividade::factory()->create();
        $idoso = Idoso::factory()->create();

        // Vincula duas vezes
        $this->actingAs($this->user)
            ->post(route('atividade.vincular', $atividade), ['idoso_id' => $idoso->id]);
        $this->actingAs($this->user)
            ->post(route('atividade.vincular', $atividade), ['idoso_id' => $idoso->id]);

        // Deve existir apenas 1 registro na pivot
        $this->assertEquals(1, $atividade->idosos()->count());
    }

    /**
     * Desvincular remove o registro da pivot.
     */
    public function test_desvincular_idoso_remove_vinculo(): void
    {
        $atividade = Atividade::factory()->create();
        $idoso = Idoso::factory()->create();
        $atividade->idosos()->attach($idoso->id);

        $this->actingAs($this->user)
            ->delete(route('atividade.desvincular', [$atividade, $idoso]));

        $this->assertEquals(0, $atividade->idosos()->count());
    }

    /**
     * Não é possível vincular um idoso desligado.
     */
    public function test_nao_vincula_idoso_desligado(): void
    {
        $atividade = Atividade::factory()->create();
        $idoso = Idoso::factory()->create([
            'data_desligamento' => now()->subMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('atividade.vincular', $atividade), ['idoso_id' => $idoso->id]);

        $response->assertInvalid('idoso_id');
        $this->assertEquals(0, $atividade->idosos()->count());
    }
}
