<?php

namespace Tests\Feature;

use App\Models\PresencaEquipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PresencaEquipeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o registro de entrada da equipe.
     */
    public function test_registrar_entrada_sucesso()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('ponto.entrada'));

        $response->assertStatus(302);
        $this->assertDatabaseHas('presenca_equipes', [
            'user_id' => $user->id,
            'data' => date('Y-m-d')
        ]);
        
        $registro = PresencaEquipe::where('user_id', $user->id)->first();
        $this->assertNotNull($registro->entrada);
        $this->assertNull($registro->saida);
    }

    /**
     * Testa o registro de saída da equipe.
     */
    public function test_registrar_saida_sucesso()
    {
        $user = User::factory()->create();
        
        // Criar registro de entrada prévio
        PresencaEquipe::create([
            'user_id' => $user->id,
            'data' => date('Y-m-d'),
            'entrada' => '08:00:00'
        ]);

        $response = $this->actingAs($user)
            ->post(route('ponto.saida'));

        $response->assertStatus(302);
        
        $registro = PresencaEquipe::where('user_id', $user->id)->first();
        $this->assertNotNull($registro->saida);
    }

    /**
     * Testa se o sistema impede dois registros de entrada no mesmo dia.
     */
    public function test_impede_duplo_registro_entrada()
    {
        $user = User::factory()->create();
        
        // Primeiro registro
        $this->actingAs($user)->post(route('ponto.entrada'));
        
        // Segundo registro (tentativa)
        $response = $this->actingAs($user)->post(route('ponto.entrada'));

        $response->assertSessionHas('error');
        $this->assertEquals(1, PresencaEquipe::where('user_id', $user->id)->count());
    }

    /**
     * Testa a exportação do relatório de ponto em PDF (acesso admin).
     */
    public function test_admin_pode_exportar_relatorio_ponto()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $funcionario = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('ponto.exportar', ['user' => $funcionario->id, 'mes' => date('m'), 'ano' => date('Y')]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
