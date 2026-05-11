<?php

namespace Tests\Feature;

use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdosoRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o código de registro é gerado no formato correto.
     */
    public function test_codigo_registro_gerado_no_formato_correto()
    {
        $idoso = Idoso::factory()->create();
        
        $ano = date('Y');
        // Formato esperado: CDI-AAAA-0001
        $this->assertMatchesRegularExpression("/^CDI-{$ano}-\d{4}$/", $idoso->codigo_registro);
    }

    /**
     * Testa se o sequencial do código de registro incrementa corretamente.
     */
    public function test_codigo_registro_sequencial_incrementa()
    {
        $idoso1 = Idoso::factory()->create();
        $idoso2 = Idoso::factory()->create();
        $idoso3 = Idoso::factory()->create();

        $this->assertStringEndsWith('0001', $idoso1->codigo_registro);
        $this->assertStringEndsWith('0002', $idoso2->codigo_registro);
        $this->assertStringEndsWith('0003', $idoso3->codigo_registro);
    }

    /**
     * Testa se idosos excluídos (soft delete) ainda são considerados para o sequencial.
     * Isso evita que códigos sejam reutilizados.
     */
    public function test_codigo_registro_nao_reutiliza_id_de_excluidos()
    {
        $idoso1 = Idoso::factory()->create(); // CDI-2026-0001
        $idoso1->delete(); // Soft delete

        $idoso2 = Idoso::factory()->create(); // Deve ser CDI-2026-0002

        $this->assertStringEndsWith('0002', $idoso2->codigo_registro);
    }

    /**
     * Testa a validação de CPF único (removida a unicidade no banco, mas pode haver validação no Request).
     * Nota: O sistema permite CPFs duplicados no banco agora (migration 2026_03_26), 
     * mas vamos testar se o cadastro básico via rota funciona.
     */
    public function test_cadastro_idoso_via_rota()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $data = [
            'nome' => 'João Silva',
            'data_nascimento' => '1950-05-10',
            'sexo' => 'cis_m',
            'data_admissao' => '2026-01-01',
            'grau_dependencia' => 'I',
            'raca_cor' => 'branca',
            'cpf' => '12345678901',
            'nis' => '12345678901',
            'contato_emergencia_nome' => 'Maria Silva',
            'contato_emergencia_telefone' => '11999999999'
        ];

        $response = $this->actingAs($admin)
            ->post(route('idoso.store'), $data);

        $response->assertRedirect(route('idoso.index'));
        $this->assertDatabaseHas('idosos', ['nome' => 'João Silva']);
    }
}
