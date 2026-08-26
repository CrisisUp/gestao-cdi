<?php

namespace Tests\Unit\Services;

use App\Models\Idoso;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ExportService();
    }

    /**
     * O CSV gerado deve conter BOM UTF-8, header correto e dados dos idosos.
     */
    public function test_gerar_csv_conteudo_correto(): void
    {
        $idoso1 = Idoso::factory()->create([
            'nome' => 'Maria da Silva',
            'sexo' => 'cis_f',
            'grau_dependencia' => 'II',
            'data_nascimento' => Carbon::now()->subYears(72)->format('Y-m-d'),
            'cpf' => '12345678901',
            'nis' => '98765432101',
        ]);
        $idoso2 = Idoso::factory()->create([
            'nome' => 'João Santos',
            'sexo' => 'cis_m',
            'grau_dependencia' => 'I',
            'data_nascimento' => Carbon::now()->subYears(65)->format('Y-m-d'),
        ]);

        $csv = $this->service->gerarCsvIdosos(collect([$idoso1, $idoso2]));

        // BOM UTF-8 para Excel
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);

        // Header
        $this->assertStringContainsString('Nome,CPF,NIS', $csv);

        // Dados dos idosos
        $this->assertStringContainsString('Maria da Silva', $csv);
        $this->assertStringContainsString('João Santos', $csv);

        // Status ATIVO (sem desligamento)
        $this->assertStringContainsString('ATIVO', $csv);

        // CPF mascarado: 123.***.***-01
        $this->assertStringContainsString('123.***.***-01', $csv);

        // NIS mascarado: 987.*****.**-1
        $this->assertStringContainsString('987.*****.**-1', $csv);

        // Grau de dependência formatado
        $this->assertStringContainsString('Grau II', $csv);
        $this->assertStringContainsString('Grau I', $csv);
    }

    /**
     * Idooso desligado deve aparecer com status DESLIGADO e motivo no CSV.
     */
    public function test_gerar_csv_idoso_desligado_marca_status(): void
    {
        $idoso = Idoso::factory()->create([
            'data_desligamento' => Carbon::now()->subMonth()->toDateString(),
            'motivo_desligamento' => 'Mudança de endereço',
        ]);

        $csv = $this->service->gerarCsvIdosos(collect([$idoso]));

        $this->assertStringContainsString('DESLIGADO', $csv);
        $this->assertStringContainsString('Mudança de endereço', $csv);
        $this->assertStringNotContainsString('ATIVO', $csv);
    }
}
