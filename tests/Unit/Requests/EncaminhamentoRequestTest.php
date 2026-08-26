<?php

namespace Tests\Unit\Requests;

use App\Models\Idoso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class EncaminhamentoRequestTest extends TestCase
{
    use RefreshDatabase;

    private array $dadosValidos;

    protected function setUp(): void
    {
        parent::setUp();

        $idoso = Idoso::factory()->create();

        $this->dadosValidos = [
            'idoso_id' => $idoso->id,
            'instituicao_destino' => 'UBS Centro',
            'especialidade' => 'Geriatria',
            'motivo' => 'Avaliação geriátrica',
            'prioridade' => 'rotina',
            'data_encaminhamento' => now()->toDateString(),
        ];
    }

    private function getRules(): array
    {
        $request = new \App\Http\Requests\EncaminhamentoRequest();
        return $request->rules();
    }

    public function test_request_aceita_dados_validos(): void
    {
        $validator = Validator::make($this->dadosValidos, $this->getRules());
        $this->assertTrue($validator->passes());
    }

    public function test_request_rejeita_idoso_id_inexistente(): void
    {
        $dados = $this->dadosValidos;
        $dados['idoso_id'] = 99999;

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('idoso_id', $validator->errors()->toArray());
    }

    public function test_request_rejeita_idoso_desligado(): void
    {
        $idoso = Idoso::factory()->create([
            'data_desligamento' => now()->subMonth(),
        ]);

        $dados = $this->dadosValidos;
        $dados['idoso_id'] = $idoso->id;

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('idoso_id', $validator->errors()->toArray());
    }

    public function test_request_rejeita_prioridade_invalida(): void
    {
        $dados = $this->dadosValidos;
        $dados['prioridade'] = 'invalida';

        $validator = Validator::make($dados, $this->getRules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('prioridade', $validator->errors()->toArray());
    }
}
