import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe('Dashboard', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('dashboard mostra conteúdo autenticado', async ({ page }) => {
    await page.goto('/dashboard');
    // Verifica que a página carregou com conteúdo autenticado
    const body = await page.locator('body').textContent();
    expect(body.length).toBeGreaterThan(100);
    expect(page.url()).not.toContain('/login');
  });

  test('navegação para Idosos funciona', async ({ page }) => {
    await page.goto('/dashboard');

    // Clica no link "Idosos" na nav
    const idososLink = page.locator('nav a').filter({ hasText: 'Idosos' }).first();
    await idososLink.click();
    await page.waitForTimeout(2000);

    expect(page.url()).toContain('idosos');
  });
});
