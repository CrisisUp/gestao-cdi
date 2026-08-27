.PHONY: setup dev test test-js test-e2e seed migrate fresh help

help: ## Mostra esta ajuda
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

setup: ## Instala tudo (deps + .env + key + migrate + seed + build)
	composer install
	cp -n .env.example .env || true
	php artisan key:generate --force
	php artisan migrate --force
	php artisan db:seed --force
	npm install
	npm run build

dev: ## Roda em paralelo: artisan serve + vite
	php artisan serve --port=8000 & npm run dev

test: ## Roda todos os testes PHP
	php artisan test

test-js: ## Roda testes frontend (Vitest)
	npx vitest run

test-e2e: ## Roda testes E2E (Playwright)
	npx playwright test

seed: ## Popula o banco com dados de teste
	php artisan db:seed --force

migrate: ## Roda migrations
	php artisan migrate --force

fresh: ## Deleta tudo e refaz (migrate + seed)
	php artisan migrate:fresh --seed --force
