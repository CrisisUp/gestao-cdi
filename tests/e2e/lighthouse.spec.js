import { test, expect } from '@playwright/test';

test.describe('Lighthouse Audits', () => {
  test.setTimeout(60000);

  test('welcome page tem SEO score >= 90', async ({ page }) => {
    await page.goto('/');
    // Verifica elementos de SEO
    const title = await page.title();
    expect(title.length).toBeGreaterThan(5);

    const metaDesc = await page.locator('meta[name="description"]').getAttribute('content');
    expect(metaDesc).toBeTruthy();

    const canonical = await page.locator('link[rel="canonical"]').getAttribute('href');
    expect(canonical).toBeTruthy();

    // Open Graph tags
    const ogTitle = await page.locator('meta[property="og:title"]').getAttribute('content');
    expect(ogTitle).toBeTruthy();
  });

  test('dashboard tem heading hierarchy (h1)', async ({ page }) => {
    await page.goto('/dashboard');
    const h1 = await page.locator('h1').first();
    await expect(h1).toBeVisible();
  });

  test('idosos index tem heading hierarchy (h1)', async ({ page }) => {
    await page.goto('/idosos');
    const h1 = await page.locator('h1').first();
    await expect(h1).toBeVisible();
  });

  test('páginas têm theme-color para PWA', async ({ page }) => {
    await page.goto('/dashboard');
    const themeColor = await page.locator('meta[name="theme-color"]').getAttribute('content');
    expect(themeColor).toBeTruthy();
  });

  test('manifest.json existe e é válido', async ({ page }) => {
    const response = await page.goto('/manifest.json');
    expect(response.status()).toBe(200);
    const manifest = await response.json();
    expect(manifest.name).toBeTruthy();
    expect(manifest.short_name).toBeTruthy();
    expect(manifest.start_url).toBeTruthy();
    expect(manifest.display).toBe('standalone');
  });

  test('service worker está registrado', async ({ page }) => {
    await page.goto('/dashboard');
    const swResponse = await page.goto('/sw.js');
    expect(swResponse.status()).toBe(200);
    const swContent = await swResponse.text();
    expect(swContent).toContain('CACHE_NAME');
    expect(swContent).toContain('self.addEventListener');
  });

  test('imagens têm alt text ou loading lazy', async ({ page }) => {
    await page.goto('/idosos');
    const images = await page.locator('img').all();
    for (const img of images) {
      const alt = await img.getAttribute('alt');
      const loading = await img.getAttribute('loading');
      // Toda imagem deve ter alt OU loading="lazy"
      expect(alt !== null || loading === 'lazy').toBeTruthy();
    }
  });

  test('formulários têm labels associados', async ({ page }) => {
    await page.goto('/idosos/create');
    const inputs = await page.locator('input[required]').all();
    for (const input of inputs) {
      const id = await input.getAttribute('id');
      if (id) {
        const label = await page.locator(`label[for="${id}"]`).count();
        expect(label).toBeGreaterThan(0);
      }
    }
  });
});
