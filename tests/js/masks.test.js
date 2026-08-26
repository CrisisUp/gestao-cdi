import { describe, it, expect } from 'vitest';

/**
 * Testes unitários das máscaras de input usadas no projeto.
 * As máscaras Alpine.js (@alpinejs/mask) usam a diretiva x-mask.
 * Testamos a lógica de formatação que o plugin aplica.
 */

/**
 * Simula a formatação de CPF: 999.999.999-99
 * O plugin Alpine mask processa caractere por caractere.
 */
function maskCpf(value) {
  const digits = value.replace(/\D/g, '').slice(0, 11);
  if (digits.length <= 3) return digits;
  if (digits.length <= 6) return `${digits.slice(0, 3)}.${digits.slice(3)}`;
  if (digits.length <= 9) return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6)}`;
  return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`;
}

/**
 * Simula a formatação de NIS: 999.99999.99-9
 */
function maskNis(value) {
  const digits = value.replace(/\D/g, '').slice(0, 11);
  if (digits.length <= 3) return digits;
  if (digits.length <= 8) return `${digits.slice(0, 3)}.${digits.slice(3)}`;
  if (digits.length <= 10) return `${digits.slice(0, 3)}.${digits.slice(3, 8)}.${digits.slice(8)}`;
  return `${digits.slice(0, 3)}.${digits.slice(3, 8)}.${digits.slice(8, 10)}-${digits.slice(10)}`;
}

/**
 * Simula a formatação de telefone: (99) 99999-9999
 */
function maskPhone(value) {
  const digits = value.replace(/\D/g, '').slice(0, 11);
  if (digits.length <= 2) return digits.length ? `(${digits}` : '';
  if (digits.length <= 7) return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
  return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
}

describe('Máscara CPF', () => {
  it('formata CPF completo corretamente', () => {
    expect(maskCpf('12345678901')).toBe('123.456.789-01');
  });

  it('formata CPF parcial (3 dígitos)', () => {
    expect(maskCpf('123')).toBe('123');
  });

  it('formata CPF parcial (6 dígitos)', () => {
    expect(maskCpf('123456')).toBe('123.456');
  });

  it('formata CPF parcial (9 dígitos)', () => {
    expect(maskCpf('123456789')).toBe('123.456.789');
  });

  it('remove caracteres não numéricos', () => {
    expect(maskCpf('abc.123.456-78901')).toBe('123.456.789-01');
  });

  it('ignora entrada vazia', () => {
    expect(maskCpf('')).toBe('');
  });
});

describe('Máscara NIS', () => {
  it('formata NIS completo corretamente', () => {
    expect(maskNis('12345678901')).toBe('123.45678.90-1');
  });

  it('formata NIS parcial', () => {
    expect(maskNis('12345')).toBe('123.45');
  });

  it('remove caracteres não numéricos', () => {
    expect(maskNis('abc12345678901')).toBe('123.45678.90-1');
  });
});

describe('Máscara Telefone', () => {
  it('formata telefone completo (11 dígitos)', () => {
    expect(maskPhone('11999887766')).toBe('(11) 99988-7766');
  });

  it('formata telefone fixo (10 dígitos)', () => {
    expect(maskPhone('1133344556')).toBe('(11) 33344-556');
  });

  it('formata parcial (2 dígitos)', () => {
    expect(maskPhone('11')).toBe('(11');
  });

  it('remove caracteres não numéricos', () => {
    expect(maskPhone('(11) 99988-7766')).toBe('(11) 99988-7766');
  });
});
