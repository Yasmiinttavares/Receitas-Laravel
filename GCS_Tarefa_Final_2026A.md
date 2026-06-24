# Gerência de Configuração de Software — Tarefa Final 2026/A
**Disciplina:** 4815207 – Gerência de Configuração de Software  
**Professor:** Fabrício  
**Aluno(s):** _[Nome do(s) aluno(s)]_

---

## 1. Visão Geral do Projeto

O projeto consiste em um **CRUD de Cadastro de Receitas**, desenvolvido em **Laravel (PHP)**, com pipeline de CI/CD completo via **GitHub Actions**, controle de versionamento pelo **GitHub**, dois bancos de dados **PostgreSQL** (homologação e produção) e ambientes isolados via **Docker containers**, executando na VM da Univates.

---

## 2. Tecnologias Utilizadas

| Categoria | Tecnologia |
|---|---|
| Linguagem | PHP 8.x |
| Framework | Laravel 11.x |
| Banco de Dados | PostgreSQL 16 (2 instâncias: homolog e prod) |
| Versionamento | Git + GitHub |
| CI/CD | GitHub Actions |
| Controle de Mudança | GitHub Issues + GitHub Projects |
| Análise de Qualidade | Laravel Pint / PHP_CodeSniffer |
| Testes Automatizados | PHPUnit (nativo do Laravel) |
| Contêineres | Docker + Docker Compose |
| Ambiente | VM Univates (Ubuntu 22.04) |
| Versionamento de BD | Laravel Migrations |

---

## 3. Estrutura do Banco de Dados

### 3.1 Tabela `usuarios`

| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | BIGINT (PK, auto) | Identificador único |
| `nome` | VARCHAR(255) | Nome completo do usuário |
| `login` | VARCHAR(100) UNIQUE | Login de acesso |
| `senha` | VARCHAR(255) | Senha (hash bcrypt) |
| `situacao` | BOOLEAN | Ativo (true) / Inativo (false) |
| `created_at` | TIMESTAMP | Criado em |
| `updated_at` | TIMESTAMP | Atualizado em |

### 3.2 Tabela `receitas`

| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | BIGINT (PK, auto) | Identificador único |
| `nome` | VARCHAR(255) | Nome da receita |
| `descricao` | TEXT | Descrição da receita |
| `data_registro` | DATE | Data de registro |
| `custo` | DECIMAL(10,2) | Custo estimado (R$) |
| `tipo_receita` | ENUM('doce','salgada') | Tipo da receita |
| `created_at` | TIMESTAMP | Criado em |
| `updated_at` | TIMESTAMP | Atualizado em |

> O versionamento do banco de dados é feito via **Laravel Migrations**, garantindo rastreabilidade e reprodutibilidade das alterações em todos os ambientes.

---

## 4. Arquitetura do Pipeline CI/CD

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          VM UNIVATES (Ubuntu 22.04)                         │
│                                                                             │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────────────────────┐  │
│  │   Workspace  │    │   GitHub     │    │    GitHub Actions (Runner)    │  │
│  │   Local      │    │   (Remoto)   │    │                              │  │
│  │              │    │              │    │  ┌────────────────────────┐  │  │
│  │  - Código    │───▶│  - Issues    │───▶│  │  CI Pipeline           │  │  │
│  │  - Migrations│    │  - Projects  │    │  │  1. Checkout           │  │  │
│  │  - Testes    │    │  - Branches  │    │  │  2. Install deps       │  │  │
│  │              │    │  - PRs       │    │  │  3. Run Migrations     │  │  │
│  └──────────────┘    └──────────────┘    │  │  4. PHPUnit (20 tests) │  │  │
│                                          │  │  5. Laravel Pint       │  │  │
│                                          │  │  6. Build              │  │  │
│                                          │  │  7. Deploy → Homolog   │  │  │
│                                          │  │  8. Deploy → Prod*     │  │  │
│                                          │  └────────────────────────┘  │  │
│                                          └──────────────────────────────┘  │
│                                                                             │
│  ┌────────────────────────────┐    ┌──────────────────────────────────────┐ │
│  │  Container: HOMOLOGAÇÃO    │    │  Container: PRODUÇÃO                 │ │
│  │                            │    │                                      │ │
│  │  - App Laravel (porta 8080)│    │  - App Laravel (porta 8081)          │ │
│  │  - PostgreSQL homolog      │    │  - PostgreSQL prod                   │ │
│  │    (porta 5433)            │    │    (porta 5434)                      │ │
│  └────────────────────────────┘    └──────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────────────┘

* Deploy para Produção: semi-automatizado (requer aprovação manual no GitHub Actions)
```

---

## 5. Estrutura de Diretórios do Projeto

```
receitas-app/
├── .github/
│   └── workflows/
│       ├── ci.yml             # Pipeline de integração (testes + qualidade)
│       └── deploy.yml         # Deploy para homolog e produção
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ReceitaController.php
│   │   │   └── UsuarioController.php
│   │   └── Middleware/
│   │       └── AuthSimples.php
│   └── Models/
│       ├── Receita.php
│       └── Usuario.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_usuarios_table.php
│   │   └── xxxx_create_receitas_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── docker/
│   ├── homolog/
│   │   ├── Dockerfile
│   │   └── .env.homolog
│   └── prod/
│       ├── Dockerfile
│       └── .env.prod
├── docker-compose.homolog.yml
├── docker-compose.prod.yml
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   │   ├── LoginTest.php
│   │   │   └── LogoutTest.php
│   │   ├── Receita/
│   │   │   ├── ReceitaCreateTest.php
│   │   │   ├── ReceitaReadTest.php
│   │   │   ├── ReceitaUpdateTest.php
│   │   │   └── ReceitaDeleteTest.php
│   │   └── Usuario/
│   │       ├── UsuarioCreateTest.php
│   │       ├── UsuarioReadTest.php
│   │       ├── UsuarioUpdateTest.php
│   │       └── UsuarioDeleteTest.php
│   └── Unit/
│       ├── ReceitaModelTest.php
│       ├── UsuarioModelTest.php
│       └── ValidacaoReceitaTest.php
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── receitas/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── layouts/
│           └── app.blade.php
├── routes/
│   └── web.php
├── .env.example
├── composer.json
└── README.md
```

---

## 6. Fases do Pipeline (A → H)

### A) Registro da Mudança
- **Ferramenta:** GitHub Issues + GitHub Projects
- Toda demanda ou bug é registrado como uma **Issue** no GitHub
- Issues são classificadas com labels: `feature`, `bugfix`, `hotfix`, `chore`
- O fluxo de trabalho segue o modelo **GitFlow**:
  - `main` → branch de produção
  - `homolog` → branch de homologação
  - `develop` → branch de integração
  - `feature/nome-da-feature` → branches de desenvolvimento

### B) Implementação
- Desenvolvimento local no **workspace da VM Univates**
- Cada Issue gera uma branch: `feature/GCS-{numero}-descricao`
- Implementação inclui: código-fonte PHP/Blade + Migrations de banco

### C) Versionamento
- **Ferramenta:** Git + GitHub
- Commits semânticos: `feat:`, `fix:`, `test:`, `chore:`, `docs:`
- Pull Request da `feature/*` para `develop`
- Merge de `develop` → `homolog` → `main` seguindo o fluxo de validação

### D) Testes Automatizados
- **Ferramenta:** PHPUnit (nativo do Laravel — `php artisan test`)
- **Total: 20 testes** distribuídos conforme tabela abaixo
- Estatísticas exibidas no GitHub Actions com relatório de cobertura

| Grupo | Arquivo | Testes |
|---|---|---|
| Autenticação | `LoginTest.php` | 3 |
| Autenticação | `LogoutTest.php` | 1 |
| Receitas (Feature) | `ReceitaCreateTest.php` | 3 |
| Receitas (Feature) | `ReceitaReadTest.php` | 2 |
| Receitas (Feature) | `ReceitaUpdateTest.php` | 2 |
| Receitas (Feature) | `ReceitaDeleteTest.php` | 2 |
| Usuários (Feature) | `UsuarioCreateTest.php` | 1 |
| Usuários (Feature) | `UsuarioReadTest.php` | 1 |
| Usuários (Feature) | `UsuarioUpdateTest.php` | 1 |
| Usuários (Feature) | `UsuarioDeleteTest.php` | 1 |
| Unit | `ReceitaModelTest.php` | 2 |
| Unit | `ValidacaoReceitaTest.php` | 1 |
| **Total** | | **20** |

### E) Análise de Qualidade de Código
- **Ferramenta:** Laravel Pint (wrapper do PHP-CS-Fixer)
- Execução: `./vendor/bin/pint --test`
- Pipeline falha se houver violações de estilo
- Padrão: PSR-12

### F) Atualização do Ambiente de Homologação
- Disparado automaticamente após merge na branch `homolog`
- GitHub Actions executa:
  1. Pull da imagem Docker atualizada
  2. `docker compose -f docker-compose.homolog.yml up -d`
  3. `php artisan migrate --env=homolog --force`

### G) Atualização do Ambiente de Produção
- Disparado manualmente via **workflow_dispatch** no GitHub Actions (semi-automatizado)
- Requer aprovação no GitHub Environments (`production`)
- Executa os mesmos passos do ambiente de homologação, apontando para o banco de produção

### H) Criação dos Ambientes (Homolog e Prod)
- **Ferramenta:** Docker + Docker Compose
- Ambos os ambientes são criados via `docker compose up` na VM Univates
- Toda infraestrutura é declarada em código (Infrastructure as Code)

---

## 7. Arquivos de Configuração

### 7.1 `docker-compose.homolog.yml`

```yaml
version: '3.8'

services:
  app_homolog:
    build:
      context: .
      dockerfile: docker/homolog/Dockerfile
    container_name: receitas_app_homolog
    ports:
      - "8080:80"
    env_file:
      - docker/homolog/.env.homolog
    depends_on:
      - db_homolog
    networks:
      - homolog_network

  db_homolog:
    image: postgres:16-alpine
    container_name: receitas_db_homolog
    ports:
      - "5433:5432"
    environment:
      POSTGRES_DB: receitas_homolog
      POSTGRES_USER: app_user
      POSTGRES_PASSWORD: homolog_secret
    volumes:
      - pgdata_homolog:/var/lib/postgresql/data
    networks:
      - homolog_network

volumes:
  pgdata_homolog:

networks:
  homolog_network:
    driver: bridge
```

### 7.2 `docker-compose.prod.yml`

```yaml
version: '3.8'

services:
  app_prod:
    build:
      context: .
      dockerfile: docker/prod/Dockerfile
    container_name: receitas_app_prod
    ports:
      - "8081:80"
    env_file:
      - docker/prod/.env.prod
    depends_on:
      - db_prod
    networks:
      - prod_network

  db_prod:
    image: postgres:16-alpine
    container_name: receitas_db_prod
    ports:
      - "5434:5432"
    environment:
      POSTGRES_DB: receitas_prod
      POSTGRES_USER: app_user
      POSTGRES_PASSWORD: prod_secret
    volumes:
      - pgdata_prod:/var/lib/postgresql/data
    networks:
      - prod_network

volumes:
  pgdata_prod:

networks:
  prod_network:
    driver: bridge
```

### 7.3 `.github/workflows/ci.yml` (Integração Contínua)

```yaml
name: CI — Testes e Qualidade

on:
  push:
    branches: [develop, homolog, main]
  pull_request:
    branches: [develop, homolog, main]

jobs:
  testes_e_qualidade:
    name: PHPUnit + Laravel Pint
    runs-on: ubuntu-latest

    services:
      postgres:
        image: postgres:16-alpine
        env:
          POSTGRES_DB: receitas_test
          POSTGRES_USER: app_user
          POSTGRES_PASSWORD: secret
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

    steps:
      - name: Checkout do código
        uses: actions/checkout@v4

      - name: Instalar PHP 8.x
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: pdo, pdo_pgsql, mbstring, xml
          coverage: xdebug

      - name: Instalar dependências (Composer)
        run: composer install --no-interaction --prefer-dist

      - name: Copiar .env de teste
        run: cp .env.example .env.testing

      - name: Gerar chave da aplicação
        run: php artisan key:generate --env=testing

      - name: Executar Migrations (banco de teste)
        run: php artisan migrate --env=testing --force
        env:
          DB_CONNECTION: pgsql
          DB_HOST: 127.0.0.1
          DB_PORT: 5432
          DB_DATABASE: receitas_test
          DB_USERNAME: app_user
          DB_PASSWORD: secret

      - name: Executar Testes Automatizados (20 testes)
        run: php artisan test --coverage --min=80
        env:
          DB_CONNECTION: pgsql
          DB_HOST: 127.0.0.1
          DB_PORT: 5432
          DB_DATABASE: receitas_test
          DB_USERNAME: app_user
          DB_PASSWORD: secret

      - name: Análise de Qualidade (Laravel Pint)
        run: ./vendor/bin/pint --test
```

### 7.4 `.github/workflows/deploy.yml` (Deploy Contínuo)

```yaml
name: CD — Deploy Homologação e Produção

on:
  push:
    branches: [homolog, main]
  workflow_dispatch:
    inputs:
      ambiente:
        description: 'Ambiente de deploy'
        required: true
        type: choice
        options: [homolog, prod]

jobs:
  deploy_homolog:
    name: Deploy → Homologação
    if: github.ref == 'refs/heads/homolog'
    runs-on: self-hosted  # Runner na VM Univates
    environment: homologacao

    steps:
      - name: Checkout do código
        uses: actions/checkout@v4

      - name: Build e subida dos containers (Homolog)
        run: |
          docker compose -f docker-compose.homolog.yml build --no-cache
          docker compose -f docker-compose.homolog.yml up -d

      - name: Executar Migrations (Homolog)
        run: |
          docker exec receitas_app_homolog php artisan migrate --force

      - name: Verificar status da aplicação
        run: curl -f http://localhost:8080 || exit 1

  deploy_prod:
    name: Deploy → Produção
    if: github.ref == 'refs/heads/main'
    runs-on: self-hosted  # Runner na VM Univates
    environment: producao  # Requer aprovação manual configurada no GitHub

    steps:
      - name: Checkout do código
        uses: actions/checkout@v4

      - name: Build e subida dos containers (Prod)
        run: |
          docker compose -f docker-compose.prod.yml build --no-cache
          docker compose -f docker-compose.prod.yml up -d

      - name: Executar Migrations (Prod)
        run: |
          docker exec receitas_app_prod php artisan migrate --force

      - name: Verificar status da aplicação
        run: curl -f http://localhost:8081 || exit 1
```

---

## 8. Roteiro de Validação

Sequência de passos a seguir na apresentação do trabalho:

1. **Mostrar ambientes sem estrutura:** containers ainda não existem (`docker ps` vazio para homolog e prod)
2. **Criar ambiente de Homologação:** `docker compose -f docker-compose.homolog.yml up -d`
3. **Criar ambiente de Produção:** `docker compose -f docker-compose.prod.yml up -d`
4. **Apresentar aplicação em Homologação:** acessar `http://vm-univates:8080`
5. **Apresentar aplicação em Produção:** acessar `http://vm-univates:8081`
6. **Registrar mudança:** criar uma Issue no GitHub (ex: "Adicionar campo 'porcoes' na receita")
7. **Implementar:** criar branch `feature/GCS-1-adicionar-porcoes`, alterar código e migration
8. **Versionar:** `git add . && git commit -m "feat: adiciona campo porcoes na receita"` + `git push`
9. **Integração (CI automático):** GitHub Actions executa automaticamente — testes, qualidade, build
10. **Atualizar Homologação:** merge da branch em `homolog` → deploy automático via GitHub Actions
11. **Apresentar Homologação atualizada:** nova coluna visível na aplicação + banco de dados
12. **Atualizar Produção:** merge de `homolog` em `main` → aprovar deploy manual no GitHub Actions
13. **Apresentar Produção atualizada:** mesmas atualizações refletidas no ambiente de produção

---

## 9. Configuração do Self-Hosted Runner (VM Univates)

Para que o GitHub Actions possa fazer deploy direto na VM, é necessário configurar um **self-hosted runner**:

```bash
# Na VM Univates, executar:
mkdir actions-runner && cd actions-runner
curl -o actions-runner-linux-x64.tar.gz -L \
  https://github.com/actions/runner/releases/download/v2.x.x/actions-runner-linux-x64-2.x.x.tar.gz
tar xzf ./actions-runner-linux-x64.tar.gz

# Configurar (token gerado no GitHub: Settings > Actions > Runners)
./config.sh --url https://github.com/SEU_USER/receitas-app --token SEU_TOKEN

# Iniciar como serviço
sudo ./svc.sh install
sudo ./svc.sh start
```

---

## 10. Diagrama de Fluxo do Pipeline

```
 GitHub Issue           Feature Branch         Pull Request
 (Mudança registrada) → (Implementação) ──────▶ (Code Review)
                                                      │
                                                      ▼
                                              GitHub Actions CI
                                         ┌────────────────────────┐
                                         │ 1. Checkout            │
                                         │ 2. Composer Install    │
                                         │ 3. Migrations (test)   │
                                         │ 4. PHPUnit (20 testes) │◀── FALHA: bloqueia merge
                                         │ 5. Laravel Pint        │
                                         └────────────────────────┘
                                                      │ SUCESSO
                                                      ▼
                                              Merge → homolog
                                                      │
                                                      ▼
                                         GitHub Actions CD (auto)
                                         ┌────────────────────────┐
                                         │ Docker build           │
                                         │ docker compose up      │
                                         │ php artisan migrate    │
                                         └────────────────────────┘
                                                      │
                                                      ▼
                                         ✅ Homologação atualizada
                                                      │
                                              Merge → main
                                                      │
                                                      ▼
                                         GitHub Actions CD (aprovação manual)
                                         ┌────────────────────────┐
                                         │ Docker build           │
                                         │ docker compose up      │
                                         │ php artisan migrate    │
                                         └────────────────────────┘
                                                      │
                                                      ▼
                                         ✅ Produção atualizada
```

---

## 11. Observações e Pendências

- [ ] Definir nomes dos alunos responsáveis pelo projeto
- [ ] Criar repositório no GitHub e configurar Environments (`homologacao` e `producao`)
- [ ] Instalar e registrar o self-hosted runner na VM Univates
- [ ] Configurar Secrets no GitHub: `DB_PASSWORD_HOMOLOG`, `DB_PASSWORD_PROD`
- [ ] Implementar os 20 testes PHPUnit conforme mapeamento da seção 6-D
- [ ] Validar configuração do `php.ini` no Dockerfile para extensão `pgsql`

---

*Documento gerado para a disciplina 4815207 — Gerência de Configuração de Software, 2026/A.*
