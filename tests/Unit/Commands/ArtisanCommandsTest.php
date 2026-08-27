<?php

namespace Tests\Unit\Commands;

use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtisanCommandsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * cdi:promote-admin promove usuário existente para admin.
     */
    public function test_promote_admin_promove_usuario(): void
    {
        $user = User::factory()->create([
            'email' => 'teste@promote.com',
            'role' => 'funcionario',
        ]);

        $this->artisan('cdi:promote-admin', ['email' => 'teste@promote.com'])
            ->expectsOutputToContain('ADMINISTRADOR');

        $this->assertDatabaseHas('users', [
            'email' => 'teste@promote.com',
            'role' => 'admin',
        ]);
    }

    /**
     * cdi:promote-admin com email inexistente retorna erro.
     */
    public function test_promote_admin_email_inexistente_retorna_erro(): void
    {
        $this->artisan('cdi:promote-admin', ['email' => 'naoexiste@teste.com'])
            ->expectsOutputToContain('não encontrado');
    }

    /**
     * cdi:gerar-codigos gera códigos para idosos sem código.
     */
    public function test_gerar_codigos_para_idosos_sem_codigo(): void
    {
        // Insere direto no banco para bypassar o boot do model que gera código automaticamente
        $now = now();
        \Illuminate\Support\Facades\DB::table('idosos')->insert([
            ['nome' => 'Sem Código 1', 'data_nascimento' => '1955-01-01', 'sexo' => 'cis_m',
             'raca_cor' => 'branca', 'grau_dependencia' => 'I', 'data_admissao' => '2026-01-01',
             'nis' => '11111111111', 'contato_emergencia_nome' => 'Resp',
             'contato_emergencia_telefone' => '11999999999',
             'created_at' => $now, 'updated_at' => $now],
            ['nome' => 'Sem Código 2', 'data_nascimento' => '1960-01-01', 'sexo' => 'cis_f',
             'raca_cor' => 'preta', 'grau_dependencia' => 'II', 'data_admissao' => '2026-01-01',
             'nis' => '22222222222', 'contato_emergencia_nome' => 'Resp',
             'contato_emergencia_telefone' => '11888888888',
             'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->artisan('cdi:gerar-codigos')
            ->expectsOutputToContain('Gerando códigos');

        // Verifica que os códigos foram gerados
        $count = \Illuminate\Support\Facades\DB::table('idosos')
            ->whereNotNull('codigo_registro')
            ->where('codigo_registro', '!=', '')
            ->count();
        $this->assertGreaterThanOrEqual(2, $count);
    }

    /**
     * cdi:gerar-codigos não sobrescreve códigos existentes.
     */
    public function test_gerar_codigos_nao_sobrescreve_existente(): void
    {
        $idoso = Idoso::factory()->create(['codigo_registro' => 'CDI-2020-9999']);

        $this->artisan('cdi:gerar-codigos')
            ->expectsOutputToContain('Todos os idosos');

        $this->assertEquals('CDI-2020-9999', $idoso->fresh()->codigo_registro);
    }
}
