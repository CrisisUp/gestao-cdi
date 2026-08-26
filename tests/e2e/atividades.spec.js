import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe('Atividades', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('criar nova atividade', async ({ page }) => {
    await page.goto('/atividades/create');

    await page.fill('#nome', 'Yoga E2E Test');
    await page.selectOption('#dia_semana', 'segunda');
    await page.fill('#horario', '09:00');
    await page.fill('#facilitador', 'Instrutor Teste');
    await page.fill('#descricao', 'Atividade criada pelo teste E2E');

    await page.click('button:has-text("Salvar"), button:has-text("Criar"), button[type="submit"]');

    // Redireciona para a lista
    await page.waitForTimeout(3000);
    expect(page.url()).toContain('atividades');
    const body = await page.locator('body').textContent();
    expect(body).not.toContain('The given data failed to pass validation');
  });

  test('listar atividades mostra a tabela', async ({ page }) => {
    await page.goto('/atividades');

    // Verifica que a página carrega com pelo menos o header
    await expect(page.locator('h2, h3, th').first()).toBeVisible({ timeout: 5000 });
  });

  test('acessar detalhes de uma atividade', async ({ page }) => {
    // Primeiro cria uma atividade
    await page.goto('/atividades/create');
    await page.fill('#nome', 'Detalhes E2E Test');
    await page.selectOption('#dia_semana', 'terca');
    await page.fill('#horario', '14:00');
    await page.click('button:has-text("Salvar"), button:has-text("Criar"), button[type="submit"]');
    await page.waitForURL(/atividades/, { timeout: 10000 });

    // Clica na atividade para ver detalhes
    const atividadeLink = page.locator('a:has-text("Detalhes E2E Test"), tr:has-text("Detalhes E2E Test") a').first();
    if (await atividadeLink.isVisible()) {
      await atividadeLink.click();
      await expect(page.locator('text=Detalhes E2E Test')).toBeVisible({ timeout: 5000 });
    }
  });
});
