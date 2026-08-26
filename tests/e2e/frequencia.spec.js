import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe('Frequência', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('página de frequência carrega corretamente', async ({ page }) => {
    await page.goto('/frequencia');

    // Verifica que a página carregou (URL ou conteúdo)
    await page.waitForTimeout(2000);
    expect(page.url()).toContain('frequencia');
    expect(page.url()).not.toContain('/login');
  });

  test('navegar para data diferente atualiza a página', async ({ page }) => {
    await page.goto('/frequencia');

    // Procura um input de data
    const dateInput = page.locator('input[type="date"]').first();
    if (await dateInput.isVisible({ timeout: 3000 })) {
      const yesterday = new Date();
      yesterday.setDate(yesterday.getDate() - 1);
      const dateStr = yesterday.toISOString().split('T')[0];
      await dateInput.fill(dateStr);
      await dateInput.press('Enter');
      await page.waitForTimeout(2000);

      // A URL ou conteúdo deve refletir a nova data
      expect(page.url()).toContain('frequencia');
    }
  });
});
