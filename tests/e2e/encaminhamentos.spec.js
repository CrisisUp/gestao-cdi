import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe('Encaminhamentos', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('criar encaminhamento para idoso ativo', async ({ page }) => {
    // Primeiro cria um idoso para ter alguém para encaminhar
    await page.goto('/idosos/create');
    await page.fill('#nome', 'Idoso Para Encaminhar E2E');
    await page.selectOption('#sexo', 'cis_f');
    await page.selectOption('#raca_cor', 'preta');
    await page.selectOption('#grau_dependencia', 'I');
    await page.fill('#data_nascimento', '1958-06-10');
    await page.fill('#data_admissao', new Date().toISOString().split('T')[0]);
    await page.fill('#contato_emergencia_nome', 'Filha');
    await page.fill('#contato_emergencia_telefone', '11966554433');
    await page.click('button:has-text("Finalizar Cadastro")');
    await page.waitForURL(/idosos/, { timeout: 10000 });

    // Agora cria o encaminhamento
    await page.goto('/encaminhamentos/create');

    // Seleciona o idoso no select
    const idosoSelect = page.locator('select[name="idoso_id"]').first();
    if (await idosoSelect.isVisible()) {
      // Seleciona a primeira opção disponível (o idoso que acabou de criar)
      await idosoSelect.selectOption({ index: 1 });
    }

    await page.fill('#instituicao_destino, input[name="instituicao_destino"]', 'UBS Centro');
    await page.fill('#motivo, textarea[name="motivo"], input[name="motivo"]', 'Avaliação geriátrica');

    const prioridadeSelect = page.locator('select[name="prioridade"]').first();
    if (await prioridadeSelect.isVisible()) {
      await prioridadeSelect.selectOption('rotina');
    }

    const dataInput = page.locator('input[name="data_encaminhamento"]').first();
    if (await dataInput.isVisible()) {
      await dataInput.fill(new Date().toISOString().split('T')[0]);
    }

    await page.click('button[type="submit"], button:has-text("Salvar"), button:has-text("Criar")');
    await page.waitForTimeout(2000);
  });

  test('listar encaminhamentos mostra a tabela', async ({ page }) => {
    await page.goto('/encaminhamentos');

    // Verifica que a página carregou
    await expect(page.locator('h2, h3, table').first()).toBeVisible({ timeout: 5000 });
  });
});
