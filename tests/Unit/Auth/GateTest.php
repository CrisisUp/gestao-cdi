<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class GateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_access_retorna_true_para_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->assertTrue(Gate::allows('admin-access'));
    }

    public function test_admin_access_retorna_false_para_funcionario(): void
    {
        $funcionario = User::factory()->create(['role' => 'funcionario']);
        $this->actingAs($funcionario);

        $this->assertFalse(Gate::allows('admin-access'));
    }

    public function test_funcionario_nao_pode_acessar_equipe(): void
    {
        $funcionario = User::factory()->create(['role' => 'funcionario']);

        $response = $this->actingAs($funcionario)->get(route('user.index'));
        $response->assertStatus(403);
    }

    public function test_admin_pode_acessar_equipe(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('user.index'));
        $response->assertStatus(200);
    }

    public function test_funcionario_pode_acessar_idosos(): void
    {
        $funcionario = User::factory()->create(['role' => 'funcionario']);

        $response = $this->actingAs($funcionario)->get(route('idoso.index'));
        $response->assertStatus(200);
    }

    public function test_funcionario_nao_pode_acessar_logs(): void
    {
        $funcionario = User::factory()->create(['role' => 'funcionario']);

        $response = $this->actingAs($funcionario)->get(route('admin.logs.index'));
        $response->assertStatus(403);
    }
}
