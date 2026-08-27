# 🏥 Gestão CDI

Sistema de gestão para **Centros de Dia para Idosos (CDI)** — cadastro, frequência, atividades, encaminhamentos, relatórios e controle de equipe.

## 🚀 Início rápido

```bash
make setup    # Instala tudo e popula o banco
make dev      # Roda o servidor (porta 8000)
```

**Login:** `admin@cdi.com.br` / `password`

## 📋 Comandos úteis

| Comando | O que faz |
|---------|-----------|
| `make setup` | Instala deps, cria .env, migra, popula banco, builda assets |
| `make dev` | Roda `artisan serve` + `vite` em paralelo |
| `make test` | Roda testes PHPUnit (123 testes) |
| `make test-js` | Roda testes frontend Vitest (20 testes) |
| `make test-e2e` | Roda testes E2E Playwright (21 testes) |
| `make seed` | Popula banco com dados de teste |
| `make fresh` | Reseta e refaz o banco do zero |

## 🧪 Testes

```bash
make test          # PHPUnit: 123 testes, 272 asserts
make test-js       # Vitest: 20 testes (theme, masks)
make test-e2e      # Playwright: 21 testes (browser real)
```

### Cobertura

| Área | Testes | O que valida |
|------|--------|-------------|
| Unit (Models) | 21 | Accessors, scopes, boot, soft delete |
| Unit (Services) | 6 | Dashboard stats, CSV export |
| Unit (Traits) | 6 | Auditoria automática (Loggable) |
| Unit (Requests) | 15 | Validação de formulários |
| Unit (Auth) | 6 | Gate admin-access |
| Unit (Controllers) | 14 | Ponto, frequência, atividades, encaminhamentos |
| Unit (Commands) | 4 | Artisan commands |
| Feature (Auth) | 16 | Login, registro, reset, verificação |
| Feature (CRUD) | 8 | Idosos, presenca equipe |
| Feature (Reports) | 16 | Relatório de movimentação matemático |
| JS Unit | 20 | Theme store, masks (CPF, NIS, telefone) |
| E2E | 21 | Fluxos completos em browser Chromium |

## 🏗️ Arquitetura

- **Framework:** Laravel 12 (PHP 8.4+)
- **Auth:** Laravel Breeze (Blade + Alpine.js)
- **CSS:** Tailwind CSS 4
- **Build:** Vite 8
- **Database:** SQLite (padrão)
- **Testes:** PHPUnit 11 + Vitest + Playwright

### Estrutura

```
app/
├── Console/Commands/    → Artisan commands (gerar códigos, promover admin)
├── Http/Controllers/    → 10 controllers (Auth + 7 de negócio + Dashboard + Profile)
├── Http/Requests/       → 6 Form Requests (validação)
├── Models/              → 7 models (User, Idoso, Atividade, Frequencia, etc)
├── Services/            → 2 services (DashboardService, ExportService)
└── Traits/              → 1 trait (Loggable - auditoria)

resources/views/
├── errors/              → 404, 403, 500 customizados
├── layouts/             → app, guest, navigation
├── components/          → 15 componentes Blade reutilizáveis
└── (módulos)/           → idosos, atividades, frequencia, etc

tests/
├── Unit/                → Models, Services, Traits, Requests, Auth, Controllers, Commands
├── Feature/             → Auth, CRUD, Relatórios
├── js/                  → Theme, Masks (Vitest)
└── e2e/                 → Auth, Dashboard, Idosos, Atividades, etc (Playwright)
```

## 📱 Mobile

- Touch targets 44px mínimo
- Theme toggle disponível em mobile
- Menu responsivo com transição animada
- Gráficos com altura responsiva
- Manifest.json para instalação como app

## 🔒 Segurança

- Rate limiting 5/min em login e registro
- Soft deletes em idosos (dados nunca são perdidos)
- Logs de auditoria em todas as ações (criar/atualizar/deletar)
- Export CSV dos logs para análise
- Gate `admin-access` para rotas administrativas
- CSRF protegido em todos os formulários

## 🐳 Docker

```bash
docker compose up -d    # Sobe PHP 8.4 + Apache
```

## 📄 Licença

Projeto privado — Gestão CDI
