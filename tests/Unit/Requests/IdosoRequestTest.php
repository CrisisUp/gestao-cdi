<?php

namespace Tests\Unit\Requests;

use App\Models\Idoso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IdosoRequestTest extends TestCase
{
    use RefreshDatabase;

    private array $dadosValidos;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dadosValidos = [
            'nome' => 'Maria da Silva',
            'data_nascimento' => '1955-03-15',
            'sexo' => 'cis_f',
            'raca_cor' => 'parda',
            'grau_dependencia' => 'II',
            'data_admissao' => '2026-01-01',
            'nis' => '12345678901',
            'contato_emergencia_nome' => 'João Silva',
            'contato_emergencia_telefone' => '11999887766',
        ];
    }

    /**
     * Obtém as regras de validação do IdosoRequest.
     */
    private function getRules(): array
    {
        $request = new \App\Http\Requests\IdosoRequest();
        return $request->rules();
    }

    public function test_request_aceita_dados_validos(): void
    {
        $validator = Validator::make($this->dadosValidos, $this->getRules());
        $this->assertTrue($validator->passes());
    }

    public function test_request_rejeita_nome_ausente(): void
    {
        $dados = $this->dadosValidos;
        unset($dados['nome']);

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('nome', $validator->errors()->toArray());
    }

    public function test_request_rejeita_data_nascimento_futura(): void
    {
        $dados = $this->dadosValidos;
        $dados['data_nascimento'] = now()->addYear()->toDateString();

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('data_nascimento', $validator->errors()->toArray());
    }

    public function test_request_rejeita_sexo_invalido(): void
    {
        $dados = $this->dadosValidos;
        $dados['sexo'] = 'invalido';

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sexo', $validator->errors()->toArray());
    }

    public function test_request_aceita_cpf_nulo(): void
    {
        $dados = $this->dadosValidos;
        $dados['cpf'] = null;

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->passes());
    }

    public function test_request_rejeita_grau_dependencia_invalido(): void
    {
        $dados = $this->dadosValidos;
        $dados['grau_dependencia'] = 'IV';

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('grau_dependencia', $validator->errors()->toArray());
    }
}
