import { describe, it, expect, beforeEach } from 'vitest';

/**
 * Testes unitários do Alpine store 'theme'.
 * Replicamos a lógica do app.js para testar isoladamente.
 */

function createThemeStore(localStorageValue) {
  // Simula o localStorage
  const storage = {};
  if (localStorageValue) storage.theme = localStorageValue;

  const mockStorage = {
    getItem: (key) => storage[key] || null,
    setItem: (key, value) => { storage[key] = value; },
  };

  // Replica o store do Alpine
  const store = {
    darkMode: mockStorage.getItem('theme') === 'dark',
    _storage: mockStorage,
    _docClasses: [],

    init() {
      this.apply();
    },

    toggle() {
      this.darkMode = !this.darkMode;
      this._storage.setItem('theme', this.darkMode ? 'dark' : 'light');
      this.apply();
    },

    apply() {
      // Simula document.documentElement.classList
      this._docClasses = this.darkMode ? ['dark'] : [];
    },
  };

  return store;
}

describe('Theme Store', () => {
  it('darkMode é false quando localStorage está vazio', () => {
    const store = createThemeStore(null);
    expect(store.darkMode).toBe(false);
  });

  it('darkMode lê "dark" do localStorage', () => {
    const store = createThemeStore('dark');
    expect(store.darkMode).toBe(true);
  });

  it('darkMode lê "light" do localStorage', () => {
    const store = createThemeStore('light');
    expect(store.darkMode).toBe(false);
  });

  it('toggle alternna darkMode', () => {
    const store = createThemeStore(null);
    expect(store.darkMode).toBe(false);

    store.toggle();
    expect(store.darkMode).toBe(true);

    store.toggle();
    expect(store.darkMode).toBe(false);
  });

  it('toggle persiste no localStorage', () => {
    const store = createThemeStore(null);

    store.toggle();
    expect(store._storage.getItem('theme')).toBe('dark');

    store.toggle();
    expect(store._storage.getItem('theme')).toBe('light');
  });

  it('apply adiciona classe dark no documento', () => {
    const store = createThemeStore('dark');
    store.apply();
    expect(store._docClasses).toContain('dark');
  });

  it('apply remove classe dark do documento', () => {
    const store = createThemeStore('light');
    store.apply();
    expect(store._docClasses).not.toContain('dark');
  });
});
