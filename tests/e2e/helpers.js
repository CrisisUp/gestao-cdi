/**
 * Helpers compartilhados para testes E2E.
 */

const ADMIN_EMAIL = 'admin@cdi.com.br';
const ADMIN_PASSWORD = 'password';

/**
 * Faz login como admin no sistema.
 */
async function loginAsAdmin(page) {
  await login(page, ADMIN_EMAIL, ADMIN_PASSWORD);
}

/**
 * Faz login com credenciais específicas.
 */
async function login(page, email, password) {
  await page.goto('/login');
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  // Espera redirecionamento — pode ir para /dashboard ou / (se email não verificado)
  await page.waitForTimeout(2000);
  // Se ficou em /, navega para /dashboard manualmente
  if (page.url() === 'http://localhost:8000/' || page.url().endsWith('/')) {
    await page.goto('/dashboard');
    await page.waitForTimeout(1000);
  }
}

/**
 * Faz logout via dropdown do usuário.
 */
async function logout(page) {
  // Abre o dropdown do usuário
  await page.click('[x-data] button img, [x-data] button svg');
  // Clica em "Log Out"
  await page.locator('text=Log Out').click();
  await page.waitForURL('**/');
}

export { loginAsAdmin, login, logout, ADMIN_EMAIL, ADMIN_PASSWORD };
