# Guia de Instalação: Gestão CDI no Windows 11 🪟

Este guia detalha como instalar e rodar o **Sistema de Gestão CDI** localmente. Recomendamos fortemente o uso do **Docker** para garantir que todas as versões (PHP 8.4, etc.) funcionem corretamente sem conflitos no seu Windows.

---

## 🐳 Opção 1: Instalação via Docker (RECOMENDADO)

Esta é a forma mais rápida e segura. O Docker criará um ambiente isolado com tudo que o sistema precisa.

### 1. Pré-requisitos

* **Docker Desktop:** [Baixar aqui](https://www.docker.com/products/docker-desktop/). Certifique-se de habilitar o **WSL2** durante a instalação.
* **Git para Windows:** [Baixar aqui](https://git-scm.com/download/win).

### 2. Passo a Passo

1. Abra o **Terminal do Windows** (PowerShell) na pasta onde deseja baixar o projeto.
2. Clone o repositório:

   ```bash
   git clone <url-do-seu-repositorio>
   cd gestao-cdi
   ```

3. Prepare o arquivo de configuração:

   ```bash
   copy .env.example .env
   ```

4. Suba os containers (o primeiro build pode demorar alguns minutos):

   ```bash
   docker-compose up -d --build
   ```

5. Finalize a configuração inicial (dentro do container):

   ```bash
   docker exec -it gestao-cdi-app php artisan key:generate
   docker exec -it gestao-cdi-app php artisan migrate
   docker exec -it gestao-cdi-app php artisan storage:link
   ```

### 3. Acessar

O sistema estará disponível em:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 💻 Opção 2: Instalação Manual (Laragon / Herd)

Se preferir não usar Docker, você pode usar o Laragon (recomendado para Windows).

### 1. Requisitos

* **Laragon Full:** [Baixar aqui](https://laragon.org/download/) (PHP 8.2 ou superior).
* **Node.js (LTS):** [Baixar aqui](https://nodejs.org/).

### 2. Passo a Passo (Manual)

* Coloque a pasta do projeto em `C:\laragon\www\gestao-cdi`.
* No Laragon, clique em **Terminal** e digite:

   ```bash
   composer install
   npm install
   copy .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   npm run build
   ```

* No Laragon, clique em **Start All**.
* Acesse: 👉 **[http://gestao-cdi.test](http://gestao-cdi.test)**

---

## 🛡️ Criando o Administrador Inicial

O sistema nasce "vazio" por segurança. Para acessar e gerenciar a equipe:

1. Acesse o site e clique em **Register** (Cadastrar).
2. Após criar sua conta, promova-a via terminal:

   ```bash
   # Se estiver no Docker:
   docker exec -it gestao-cdi-app php artisan cdi:promote-admin seu-email@exemplo.com

   # Se estiver no modo manual:
   php artisan cdi:promote-admin seu-email@exemplo.com
   ```

---

## 💾 Rotina de Backup (VITAL)

O banco de dados é o arquivo `database/database.sqlite`.
**Toda sexta-feira:** Copie este arquivo para um Pendrive ou Google Drive. Se o computador quebrar, seus dados estarão salvos ali.

---

## 📸 Configurações de Upload de Fotos

Para aceitar fotos de alta qualidade de celulares modernos:

1. Localize o seu `php.ini` (No Laragon: Menu > PHP > php.ini).
2. Altere estas linhas para aceitar até 10MB:

   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   memory_limit = 256M
   ```

3. Reinicie o servidor.

*(No Docker, essas configurações já estão otimizadas).*

---
&copy; 2026 — Gestão CDI.
