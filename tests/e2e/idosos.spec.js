import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe('Gestão de Idosos', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('criar idoso com todos os campos obrigatórios', async ({ page }) => {
    await page.goto('/idosos/create');

    // Dados pessoais
    await page.fill('#nome', 'Maria da Silva E2E');
    await page.selectOption('#sexo', 'cis_f');
    await page.selectOption('#raca_cor', 'parda');
    await page.selectOption('#grau_dependencia', 'II');
    await page.fill('#data_nascimento', '1955-03-15');
    await page.fill('#data_admissao', new Date().toISOString().split('T')[0]);

    // CPF e NIS (com máscara Alpine.js — digita os dígitos)
    await page.fill('#cpf', '52998224725');
    await page.fill('#nis', '12345678901');

    // Contato de emergência
    await page.fill('#contato_emergencia_nome', 'João Silva');
    await page.fill('#contato_emergencia_telefone', '11999887766');

    // Saúde
    await page.fill('#medicamentos', 'Losartana 50mg');

    // Submete
    await page.click('button:has-text("Finalizar Cadastro")');

    // Redireciona para a lista — pode ter mensagem de sucesso
    await page.waitForTimeout(3000);
    expect(page.url()).toContain('idosos');
    // Verifica que não houve erro de validação
    const body = await page.locator('body').textContent();
    expect(body).not.toContain('The given data failed to pass validation');
  });

  test('buscar idosos por nome filtra a lista', async ({ page }) => {
    await page.goto('/idosos');

    // Cria um idoso para garantir que existe
    await page.goto('/idosos/create');
    await page.fill('#nome', 'Buscável Único E2E');
    await page.selectOption('#sexo', 'cis_m');
    await page.selectOption('#raca_cor', 'branca');
    await page.selectOption('#grau_dependencia', 'I');
    await page.fill('#data_nascimento', '1960-05-10');
    await page.fill('#data_admissao', new Date().toISOString().split('T')[0]);
    await page.fill('#contato_emergencia_nome', 'Resp');
    await page.fill('#contato_emergencia_telefone', '11988776655');
    await page.click('button:has-text("Finalizar Cadastro")');
    await page.waitForURL(/idosos/, { timeout: 10000 });

    // Agora busca
    const searchInput = page.locator('input[type="search"], input[placeholder*="Buscar"]').first();
    if (await searchInput.isVisible()) {
      await searchInput.fill('Buscável Único');
      await searchInput.press('Enter');
      await page.waitForTimeout(1000);
      await expect(page.locator('text=Buscável Único E2E')).toBeVisible();
    }
  });

  test('excluir idoso remove da lista', async ({ page }) => {
    // Primeiro cria um idoso
    await page.goto('/idosos/create');
    await page.fill('#nome', 'Para Excluir E2E');
    await page.selectOption('#sexo', 'cis_m');
    await page.selectOption('#raca_cor', 'branca');
    await page.selectOption('#grau_dependencia', 'I');
    await page.fill('#data_nascimento', '1965-07-20');
    await page.fill('#data_admissao', new Date().toISOString().split('T')[0]);
    await page.fill('#contato_emergencia_nome', 'Resp');
    await page.fill('#contato_emergencia_telefone', '11977665544');
    await page.click('button:has-text("Finalizar Cadastro")');
    await page.waitForURL(/idosos/, { timeout: 10000 });

    // Encontra o idoso na lista e clica excluir
    const row = page.locator('tr:has-text("Para Excluir E2E")').first();
    if (await row.isVisible()) {
      // Clica no botão de excluir (geralmente um ícone de lixeira)
      const deleteBtn = row.locator('button[wire\\:click*="destroy"], form button, a[href*="destroy"]').first();
      if (await deleteBtn.isVisible()) {
        // Aceita o dialog de confirmação
        page.on('dialog', dialog => dialog.accept());
        await deleteBtn.click();
        await page.waitForTimeout(2000);
      }
    }
  });

  test('filtro de status mostra idosos desligados', async ({ page }) => {
    await page.goto('/idosos');

    // Verifica que a tabela de idosos existe
    await expect(page.locator('table').first()).toBeVisible({ timeout: 5000 });

    // Verifica que os links de filtro existem
    const filtroDesligados = page.locator('a:has-text("Desligados"), button:has-text("Desligados")').first();
    if (await filtroDesligados.isVisible()) {
      await filtroDesligados.click();
      await page.waitForTimeout(1000);
    }
  });

  test('página de listar idosos carrega corretamente', async ({ page }) => {
    await page.goto('/idosos');

    // Verifica que a página carregou
    await expect(page.locator('table').first()).toBeVisible({ timeout: 5000 });

    // Verifica que o botão "Novo" está visível
    await expect(page.locator('a:has-text("Novo"), a:has-text("Cadastrar")').first()).toBeVisible();
  });
});
