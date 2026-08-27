import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';
import { loginAsAdmin } from './helpers.js';

test.describe('Acessibilidade (WCAG)', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('dashboard não tem violações críticas (critical)', async ({ page }) => {
    await page.goto('/dashboard');
    await page.waitForLoadState('domcontentloaded');
    const results = await new AxeBuilder({ page }).analyze();
    const critical = results.violations.filter(v => v.impact === 'critical');
    // Log para debug mas não bloqueia
    if (critical.length > 0) {
      console.log('⚠️ Acessibilidade critical:', critical.map(v => v.id).join(', '));
    }
    expect(critical.length).toBe(0);
  });

  test('idosos index não tem violações críticas (critical)', async ({ page }) => {
    await page.goto('/idosos');
    await page.waitForLoadState('domcontentloaded');
    const results = await new AxeBuilder({ page }).analyze();
    const critical = results.violations.filter(v => v.impact === 'critical');
    if (critical.length > 0) {
      console.log('⚠️ Acessibilidade critical:', critical.map(v => v.id).join(', '));
    }
    expect(critical.length).toBe(0);
  });

  test('frequência não tem violações críticas (critical)', async ({ page }) => {
    await page.goto('/frequencia');
    await page.waitForLoadState('domcontentloaded');
    const results = await new AxeBuilder({ page }).analyze();
    const critical = results.violations.filter(v => v.impact === 'critical');
    if (critical.length > 0) {
      console.log('⚠️ Acessibilidade critical:', critical.map(v => v.id).join(', '));
    }
    expect(critical.length).toBe(0);
  });

  test('login não tem violações críticas (critical)', async ({ page }) => {
    await page.goto('/login');
    await page.waitForLoadState('domcontentloaded');
    const results = await new AxeBuilder({ page }).analyze();
    const critical = results.violations.filter(v => v.impact === 'critical');
    if (critical.length > 0) {
      console.log('⚠️ Acessibilidade critical:', critical.map(v => v.id).join(', '));
    }
    expect(critical.length).toBe(0);
  });

  test('welcome não tem violações críticas (critical)', async ({ page }) => {
    await page.goto('/');
    await page.waitForLoadState('domcontentloaded');
    const results = await new AxeBuilder({ page }).analyze();
    const critical = results.violations.filter(v => v.impact === 'critical');
    if (critical.length > 0) {
      console.log('⚠️ Acessibilidade critical:', critical.map(v => v.id).join(', '));
    }
    expect(critical.length).toBe(0);
  });
});
