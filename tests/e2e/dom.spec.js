import { test, expect } from '@playwright/test';
import { loginAsAdmin } from './helpers.js';

test.describe.serial('Blade + Alpine.js — Validação do DOM', () => {

  test('dark mode: store está funcional', async ({ page }) => {
    await loginAsAdmin(page);
    const storeExists = await page.evaluate(() => {
      return typeof window.Alpine !== 'undefined' && typeof Alpine.store === 'function';
    });
    expect(storeExists).toBeTruthy();
  });

  test('dark mode: classe dark é toggável via JS', async ({ page }) => {
    await loginAsAdmin(page);
    await page.evaluate(() => Alpine.store('theme').toggle());
    const hasDark = await page.evaluate(() => document.documentElement.classList.contains('dark'));
    expect(hasDark).toBeTruthy();
  });

  test('navegação: links principais existem no DOM', async ({ page }) => {
    await loginAsAdmin(page);
    const html = await page.content();
    expect(html).toContain('Painel');
    expect(html).toContain('Idosos');
    expect(html).toContain('Atividades');
    expect(html).toContain('Frequência');
  });

  test('dashboard: heading h1 existe', async ({ page }) => {
    await loginAsAdmin(page);
    const h1Count = await page.locator('h1').count();
    expect(h1Count).toBeGreaterThanOrEqual(1);
  });

  test('dashboard: cards de estatísticas existem', async ({ page }) => {
    await loginAsAdmin(page);
    const html = await page.content();
    expect(html).toContain('Idosos cadastrados');
    expect(html).toContain('Colaboradores presentes');
  });

  test('dashboard: saudação personalizada', async ({ page }) => {
    await loginAsAdmin(page);
    const html = await page.content();
    expect(html).toContain('Bem-vindo');
  });

  test('frequência: formulário tem x-data loading', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/frequencia');
    const html = await page.content();
    expect(html).toContain('x-data');
    expect(html).toContain('loading');
  });

  test('idosos create: formulário existe com campos required', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/idosos/create');
    const requiredCount = await page.locator('[required]').count();
    expect(requiredCount).toBeGreaterThanOrEqual(3);
  });

  test('idosos index: tabela existe', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/idosos');
    const tableCount = await page.locator('table').count();
    expect(tableCount).toBeGreaterThanOrEqual(1);
  });

  test('idosos index: barra de busca existe', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/idosos');
    const searchCount = await page.locator('input[name="search"]').count();
    expect(searchCount).toBeGreaterThanOrEqual(1);
  });

  test('service worker está registrado', async ({ page }) => {
    await loginAsAdmin(page);
    const swRegistered = await page.evaluate(async () => {
      if ('serviceWorker' in navigator) {
        const regs = await navigator.serviceWorker.getRegistrations();
        return regs.length > 0;
      }
      return false;
    });
    expect(swRegistered).toBeTruthy();
  });

  test('meta tags PWA existem', async ({ page }) => {
    await loginAsAdmin(page);
    const themeColor = await page.locator('meta[name="theme-color"]').count();
    expect(themeColor).toBeGreaterThan(0);
  });

  test('manifest link existe', async ({ page }) => {
    await loginAsAdmin(page);
    const html = await page.content();
    expect(html).toContain('rel="manifest"');
  });

  test('páginas têm title', async ({ page }) => {
    await loginAsAdmin(page);
    for (const url of ['/dashboard', '/idosos', '/frequencia']) {
      await page.goto(url);
      const title = await page.title();
      expect(title.length).toBeGreaterThan(3);
    }
  });

  test('welcome tem Open Graph tags', async ({ page }) => {
    await page.goto('/');
    const html = await page.content();
    expect(html).toContain('og:title');
  });
});
