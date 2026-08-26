<?php

namespace Tests\Unit\Models;

use App\Models\Idoso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdosoTest extends TestCase
{
    use RefreshDatabase;

    // ─── FAIXA ETÁRIA ────────────────────────────────────────────

    public function test_faixa_etaria_menor_60(): void
    {
        $idoso = Idoso::factory()->menor60()->create();
        $this->assertEquals('Menor de 60 anos', $idoso->faixa_etaria);
    }

    public function test_faixa_etaria_60_64(): void
    {
        $idoso = Idoso::factory()->faixa60_64()->create();
        $this->assertEquals('60-64 anos', $idoso->faixa_etaria);
    }

    public function test_faixa_etaria_65_69(): void
    {
        $idoso = Idoso::factory()->faixa65_69()->create();
        $this->assertEquals('65-69 anos', $idoso->faixa_etaria);
    }

    public function test_faixa_etaria_70_74(): void
    {
        $idoso = Idoso::factory()->faixa70_74()->create();
        $this->assertEquals('70-74 anos', $idoso->faixa_etaria);
    }

    public function test_faixa_etaria_75_79(): void
    {
        $idoso = Idoso::factory()->faixa75_79()->create();
        $this->assertEquals('75-79 anos', $idoso->faixa_etaria);
    }

    public function test_faixa_etaria_80_mais(): void
    {
        $idoso = Idoso::factory()->faixa80_mais()->create();
        $this->assertEquals('80 anos ou mais', $idoso->faixa_etaria);
    }

    // ─── CPF MASCARADO ───────────────────────────────────────────

    public function test_cpf_mascarado_formato_correto(): void
    {
        $idoso = Idoso::factory()->create(['cpf' => '12345678901']);
        $this->assertEquals('123.***.***-01', $idoso->cpf_masked);
    }

    public function test_cpf_mascarado_nulo(): void
    {
        $idoso = Idoso::factory()->create(['cpf' => null]);
        $this->assertEquals('Não informado', $idoso->cpf_masked);
    }

    public function test_cpf_mascarado_tamanho_invalido(): void
    {
        $idoso = Idoso::factory()->create(['cpf' => '12345']);
        $this->assertEquals('12345', $idoso->cpf_masked);
    }

    // ─── NIS MASCARADO ───────────────────────────────────────────

    public function test_nis_mascarado_formato_correto(): void
    {
        $idoso = Idoso::factory()->create(['nis' => '98765432101']);
        $this->assertEquals('987.*****.**-1', $idoso->nis_masked);
    }

    public function test_nis_mascarado_nulo(): void
    {
        // NIS tem constraint NOT NULL, então testamos com string vazia
        $idoso = Idoso::factory()->create(['nis' => '']);
        $this->assertEquals('Não informado', $idoso->nis_masked);
    }

    // ─── SEXO TEXTO ──────────────────────────────────────────────

    public function test_sexo_texto_mapeamento(): void
    {
        $casos = [
            'cis_m' => 'Cisgênero Masculino',
            'cis_f' => 'Cisgênero Feminino',
            'trans_m' => 'Transgênero Masculino',
            'trans_f' => 'Transgênero Feminino',
            'agenero' => 'Agênero',
            'nao_declarado' => 'Não declarado',
        ];

        foreach ($casos as $valor => $esperado) {
            $idoso = Idoso::factory()->create(['sexo' => $valor]);
            $this->assertEquals($esperado, $idoso->sexo_texto, "Falhou para sexo: {$valor}");
        }
    }

    // ─── RAÇA/COR TEXTO ──────────────────────────────────────────

    public function test_raca_cor_texto_mapeamento(): void
    {
        $casos = [
            'branca' => 'Branca',
            'preta' => 'Preta',
            'parda' => 'Parda',
            'amarela' => 'Amarela',
            'indigena' => 'Indígena',
            'nao_informado' => 'Não informado',
        ];

        foreach ($casos as $valor => $esperado) {
            $idoso = Idoso::factory()->create(['raca_cor' => $valor]);
            $this->assertEquals($esperado, $idoso->raca_cor_texto, "Falhou para raça/cor: {$valor}");
        }
    }

    // ─── BOOT: CÓDIGO DE REGISTRO ────────────────────────────────

    public function test_codigo_registro_formato_correto(): void
    {
        $idoso = Idoso::factory()->create();
        $ano = Carbon::now()->year;
        $this->assertMatchesRegularExpression("/^CDI-{$ano}-\d{4}$/", $idoso->codigo_registro);
    }

    public function test_codigo_registro_sequencial(): void
    {
        $i1 = Idoso::factory()->create();
        $i2 = Idoso::factory()->create();
        $i3 = Idoso::factory()->create();

        $this->assertStringEndsWith('-0001', $i1->codigo_registro);
        $this->assertStringEndsWith('-0002', $i2->codigo_registro);
        $this->assertStringEndsWith('-0003', $i3->codigo_registro);
    }

    public function test_codigo_registro_mantem_se_fornecido(): void
    {
        $idoso = Idoso::factory()->create(['codigo_registro' => 'CDI-2020-9999']);
        $this->assertEquals('CDI-2020-9999', $idoso->codigo_registro);
    }

    // ─── SCOPE: FILTERED ─────────────────────────────────────────

    public function test_scope_filtered_busca_por_nome(): void
    {
        Idoso::factory()->create(['nome' => 'Maria da Silva']);
        Idoso::factory()->create(['nome' => 'João Santos']);

        $resultados = Idoso::filtered(null, null)->pluck('nome');
        $this->assertCount(2, $resultados);

        $resultados = Idoso::filtered('Maria', null)->pluck('nome');
        $this->assertCount(1, $resultados);
        $this->assertTrue($resultados->contains('Maria da Silva'));
    }

    public function test_scope_filtered_filtro_desligados(): void
    {
        Idoso::factory()->create(['nome' => 'Ativo']);
        Idoso::factory()->create([
            'nome' => 'Desligado',
            'data_desligamento' => Carbon::now()->subMonth(),
        ]);

        $ativos = Idoso::filtered(null, null)->pluck('nome');
        $this->assertTrue($ativos->contains('Ativo'));
        $this->assertFalse($ativos->contains('Desligado'));

        $desligados = Idoso::filtered(null, 'desligados')->pluck('nome');
        $this->assertCount(1, $desligados);
        $this->assertTrue($desligados->contains('Desligado'));

        $todos = Idoso::filtered(null, 'todos')->pluck('nome');
        $this->assertCount(2, $todos);
    }

    public function test_scope_filtered_filtro_sem_cpf(): void
    {
        Idoso::factory()->create(['cpf' => null]);
        Idoso::factory()->create(['cpf' => '12345678901']);

        $semCpf = Idoso::filtered(null, 'sem_cpf')->get();
        $this->assertCount(1, $semCpf);
        $this->assertNull($semCpf->first()->cpf);
    }

    public function test_scope_filtered_filtro_com_medicamento(): void
    {
        Idoso::factory()->create(['medicamentos' => null]);
        Idoso::factory()->create(['medicamentos' => 'Losartana 50mg']);

        $comMed = Idoso::filtered(null, 'com_medicamento')->get();
        $this->assertCount(1, $comMed);
        $this->assertEquals('Losartana 50mg', $comMed->first()->medicamentos);
    }

    // ─── SOFT DELETE ─────────────────────────────────────────────

    public function test_soft_delete_nao_remove_registro(): void
    {
        $idoso = Idoso::factory()->create();
        $id = $idoso->id;

        $idoso->delete();

        $this->assertNull(Idoso::find($id));
        $this->assertNotNull(Idoso::withTrashed()->find($id));
    }
}
