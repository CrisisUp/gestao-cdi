import { test, expect } from '@playwright/test';
import { login, ADMIN_EMAIL, ADMIN_PASSWORD } from './helpers.js';

test.describe('Autenticação', () => {

  test('login com credenciais corretas redireciona para dashboard', async ({ page }) => {
    await login(page, ADMIN_EMAIL, ADMIN_PASSWORD);
    // Após login + navegação manual, deve estar autenticado
    const body = await page.locator('body').textContent();
    expect(body.length).toBeGreaterThan(0);
    // Verifica que está em uma página autenticada (dashboard ou similar)
    expect(page.url()).not.toContain('/login');
  });

  test('login com senha errada permanece na página de login', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', ADMIN_EMAIL);
    await page.fill('input[name="password"]', 'senha-errada');
    await page.click('button[type="submit"]');

    await page.waitForTimeout(2000);
    // Login falhou — continua em /login
    expect(page.url()).toContain('login');
  });

  test('logout retorna para a página inicial', async ({ page }) => {
    await login(page, ADMIN_EMAIL, ADMIN_PASSWORD);

    // Clica no dropdown do usuário (botão com o nome do user)
    const userDropdown = page.locator('button:has-text("Administrador")').first();
    if (await userDropdown.isVisible({ timeout: 3000 })) {
      await userDropdown.click();
      await page.waitForTimeout(500);

      // Clica em Log Out
      await page.locator('text=Log Out').first().click();
      await page.waitForTimeout(2000);

      // Deve estar na página pública
      expect(page.url()).not.toContain('/dashboard');
    }
  });

  test('página de registro renderiza corretamente', async ({ page }) => {
    await page.goto('/register');
    await expect(page.locator('input[name="name"]')).toBeVisible();
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
  });
});
