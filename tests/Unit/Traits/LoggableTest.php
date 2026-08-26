<?php

namespace Tests\Unit\Traits;

use App\Models\ActivityLog;
use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoggableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cria um Idoso e retorna o ActivityLog de criação correspondente.
     */
    private function criarIdosoComLog(): ActivityLog
    {
        Idoso::factory()->create();

        return ActivityLog::where('model_type', Idoso::class)
            ->where('action', 'created')
            ->latest()
            ->first();
    }

    public function test_criacao_gera_log_de_auditoria(): void
    {
        $log = $this->criarIdosoComLog();

        $this->assertNotNull($log);
        $this->assertEquals('created', $log->action);
        $this->assertEquals(Idoso::class, $log->model_type);
        $this->assertNull($log->old_values);
        $this->assertNotNull($log->new_values);
    }

    public function test_atualizacao_gera_log_com_old_e_new(): void
    {
        $logCriacao = $this->criarIdosoComLog();
        $idoso = Idoso::find($logCriacao->model_id);

        $nomeAntigo = $idoso->nome;
        $idoso->update(['nome' => 'Nome Novo']);

        $log = ActivityLog::where('model_type', Idoso::class)
            ->where('action', 'updated')
            ->latest()
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('updated', $log->action);
        $this->assertEquals($nomeAntigo, $log->old_values['nome']);
        $this->assertEquals('Nome Novo', $log->new_values['nome']);
    }

    public function test_atualizacao_sem_mudancas_nao_gera_log(): void
    {
        $logCriacao = $this->criarIdosoComLog();
        $idoso = Idoso::find($logCriacao->model_id);

        $countAntes = ActivityLog::count();

        $idoso->save();

        $this->assertEquals($countAntes, ActivityLog::count());
    }

    public function test_soft_delete_gera_log_soft_deleted(): void
    {
        $logCriacao = $this->criarIdosoComLog();
        $idoso = Idoso::find($logCriacao->model_id);

        $idoso->delete();

        $log = ActivityLog::where('model_type', Idoso::class)
            ->where('action', 'soft_deleted')
            ->latest()
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('soft_deleted', $log->action);
        $this->assertNotNull($log->old_values);
        $this->assertNull($log->new_values);
    }

    public function test_campos_sensiveis_sao_excluidos_do_log(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('senha-secreta'),
        ]);

        // Pega o log de criação do User
        $logCriacao = ActivityLog::where('model_type', User::class)
            ->where('action', 'created')
            ->latest()
            ->first();

        // Password não deve estar no log de criação
        $this->assertArrayNotHasKey('password', $logCriacao->new_values);
        $this->assertArrayNotHasKey('created_at', $logCriacao->new_values);
        $this->assertArrayNotHasKey('updated_at', $logCriacao->new_values);
    }

    public function test_log_registra_model_id_correto(): void
    {
        $log = $this->criarIdosoComLog();

        $this->assertNotNull($log->model_id);
        $this->assertNotNull($this->findById($log->model_type, $log->model_id));
    }

    /**
     * Busca um model pelo tipo completo (namespace) e ID.
     */
    private function findById(string $modelType, int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $modelType::withTrashed()->find($id);
    }
}
