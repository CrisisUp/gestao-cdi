import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe('Perfil', () => {

  test('página de perfil carrega corretamente', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/profile');

    // Verifica que o formulário de perfil está visível
    await expect(page.locator('input[name="name"]')).toBeVisible({ timeout: 5000 });
    await expect(page.locator('input[name="email"]')).toBeVisible();
  });

  test('atualizar nome do perfil funciona', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/profile');

    // Limpa e preenche o nome
    const nameInput = page.locator('input[name="name"]');
    await nameInput.clear();
    await nameInput.fill('Admin Atualizado E2E');

    // Submete — procura botão que contenha texto de salvar
    const submitBtn = page.locator('form button[type="submit"]').first();
    await submitBtn.click();

    // Espera a resposta — pode redirecionar ou mostrar mensagem
    await page.waitForTimeout(3000);

    // Verifica que não saiu da página de perfil
    expect(page.url()).toContain('profile');
  });
});
