<?php

namespace Tests\Unit\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UserRequestTest extends TestCase
{
    use RefreshDatabase;

    private array $dadosValidos;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dadosValidos = [
            'name' => 'João Funcionário',
            'email' => 'joao@teste.com',
            'role' => 'funcionario',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ];
    }

    private function getRules(): array
    {
        $request = new \App\Http\Requests\UserRequest();
        return $request->rules();
    }

    public function test_request_aceita_dados_validos_para_criacao(): void
    {
        $validator = Validator::make($this->dadosValidos, $this->getRules());
        $this->assertTrue($validator->passes());
    }

    public function test_request_rejeita_email_duplicado(): void
    {
        User::factory()->create(['email' => 'existente@teste.com']);

        $dados = $this->dadosValidos;
        $dados['email'] = 'existente@teste.com';

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_request_rejeita_password_curto(): void
    {
        $dados = $this->dadosValidos;
        $dados['password'] = '123';
        $dados['password_confirmation'] = '123';

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_request_rejeita_role_invalida(): void
    {
        $dados = $this->dadosValidos;
        $dados['role'] = 'superadmin';

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('role', $validator->errors()->toArray());
    }

    public function test_request_rejeita_password_sem_confirmacao(): void
    {
        $dados = $this->dadosValidos;
        unset($dados['password_confirmation']);

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
    }
}
