# Gestão CDI - Centro de Dia para Idosos 🏥👴👵

Este é um sistema web profissional desenvolvido com o ecossistema **Laravel 12** e **Tailwind CSS v4**, especializado no gerenciamento completo de Centros de Dia para Idosos (CDI). O sistema foca em acessibilidade, segurança de dados e eficiência operacional para equipes de assistência social e saúde.

---

## 🚀 Funcionalidades Principais

### 📋 Módulo de Idosos (Beneficiários)

* **Cadastro Visual:** Registro com upload de foto para identificação rápida.
* **Privacidade:** Máscaras dinâmicas de CPF/NIS alinhadas com a LGPD.
* **Prontuário Digital:** Ficha técnica de saúde, medicamentos e contatos de emergência.
* **Geração de Registro:** Código único automático (Ex: `CDI-2026-0001`).

### ✅ Operacional e Frequência

* **Chamada Inteligente:** Registro de presença/ausência em lote para oficinas e atividades.
* **Ponto da Equipe:** Controle de jornada dos funcionários com relatórios mensais.
* **Timeline:** Auditoria automática de todas as alterações feitas nos registros.

### 📄 Relatórios Profissionais

* **PDFs Oficiais:** Geração de relatórios de movimentação mensal (Controle Social).
* **Exportação BI:** Extração de dados em CSV para análise em Excel.
* **Impressão:** Layouts otimizados para prontuários físicos.

---

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.4 + Laravel 12
* **Frontend:** Tailwind CSS v4 + Alpine.js
* **Banco de Dados:** SQLite (Padronizado para portabilidade)
* **Infra:** Docker (PHP-Apache)

---

## 🧪 Qualidade e Testes Automatizados

O sistema é protegido por uma suíte robusta de **37 testes automatizados** (Feature e Unit tests), garantindo que as regras de negócio críticas nunca falhem:

* **Relatórios Oficiais:** Validação matemática rigorosa dos cálculos de movimentação mensal e saldos para o Controle Social.
* **Geração de Códigos:** Garantia de integridade no formato sequencial único (`CDI-AAAA-NNNN`).
* **Ponto da Equipe:** Testes de registro de jornada, prevenção de duplicidade e exportação de PDF.
* **Segurança:** Verificação completa de autenticação, permissões de administrador e proteção de dados (LGPD).

Para validar a integridade do sistema, basta rodar:

```bash
php artisan test
```

---

## 💻 Instalação Rápida (Docker)

Para começar agora mesmo, utilize o Docker Desktop no seu Windows:

```bash
# 1. Clone o projeto
git clone https://github.com/seu-usuario/gestao-cdi.git

# 2. Configure o ambiente
cp .env.example .env
docker-compose up -d --build

# 3. Prepare o banco
docker exec -it gestao-cdi-app php artisan key:generate
docker exec -it gestao-cdi-app php artisan migrate
docker exec -it gestao-cdi-app php artisan storage:link
```

Para instruções detalhadas de instalação manual (Laragon/Windows), consulte o **[Guia de Instalação Completo](README_INSTALACAO.md)**.

---
&copy; 2026 — Gestão CDI. Sistema profissional para o cuidado e assistência à pessoa idosa.
