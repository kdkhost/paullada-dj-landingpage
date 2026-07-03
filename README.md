# Paullada DJ — Landing Page Administrável

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Next.js](https://img.shields.io/badge/Next.js-16-000000?logo=nextdotjs&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?logo=tailwindcss&logoColor=white)
![AdminLTE](https://img.shields.io/badge/AdminLTE-4-00A65A?logo=adminlte&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

> **Landing Page Administrável para Paullada DJ — Especialista em flashback anos 70, 80, 90 e 2000**

Sistema completo com frontend em Next.js 16, backend em Laravel 13, painel admin premium com AdminLTE4, integração com MySQL, e API RESTful para gerenciamento de shows, ingressos, galeria e analytics.

---

## Índice

- [Visão Geral](#visão-geral)
- [Tech Stack](#tech-stack)
- [Funcionalidades](#funcionalidades)
- [Pré-requisitos](#pré-requisitos)
- [Setup Local](#setup-local)
- [Deploy no CPanel](#deploy-no-cpanel)
- [Credenciais do Admin](#credenciais-do-admin)
- [Links](#links)
- [Licença](#licença)

---

## Visão Geral

A **Paullada DJ Landing Page** é uma plataforma completa para divulgação e gerenciamento de shows do DJ **Paullada**, especialista em flashback dos anos 70, 80, 90 e 2000. O sistema conta com:

- Página pública moderna e responsiva com tema dark neon
- Painel administrativo completo com gráficos e métricas
- Gestão de agenda de shows com ativação/desativação de vendas de ingressos
- Galeria de fotos e vídeos
- Analytics integrado com armazenamento no banco de dados
- SEO completo com Open Graph, JSON-LD e sitemap dinâmico

---

## Tech Stack

| Tecnologia     | Versão | Finalidade                        |
|----------------|--------|-----------------------------------|
| Laravel        | 13     | API RESTful e backend             |
| Next.js        | 16     | Frontend SSR/SSG                  |
| Tailwind CSS   | v4     | Estilização e tema dark neon      |
| AdminLTE       | 4      | Painel administrativo premium     |
| MySQL          | 8      | Banco de dados relacional         |
| PHP            | 8.3+   | Runtime do backend                |
| Node.js        | 22+    | Runtime do frontend               |

---

## Funcionalidades

- **Agenda de Shows** — Cadastro, edição e exibição de eventos futuros
- **Vendas de Ingressos** — Ativar/desativar por show individualmente
- **Galeria de Fotos/Vídeos** — Upload e gerenciamento multimídia
- **Preloader Personalizado** — Animação de vinil girando antes do carregamento
- **Scroll Customizado** — Barra de rolagem neon estilizada
- **SEO Completo** — Open Graph, Twitter Cards, JSON-LD, sitemap.xml, robots.txt
- **Analytics com Gráficos** — Eventos de página, cliques e formulários; gráficos Chart.js
- **Painel Admin Premium** — AdminLTE4 com dashboard, tabelas responsivas e gráficos
- **API RESTful** — Endpoints públicos para consumo do frontend
- **Tema Dark Neon** — Design moderno e imersivo

---

## Pré-requisitos

- PHP 8.3+
- Composer 2
- Node.js 22+
- NPM 10+
- MySQL 8
- Extensões PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD

---

## Setup Local

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/paullada-dj.git
cd paullada-dj

# 2. Configure o backend
cd backend
cp .env.example .env
composer install
php artisan key:generate

# Configure o banco no .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
php artisan migrate --seed
php artisan storage:link
php artisan serve
# Backend rodando em http://localhost:8000

# 3. Configure o frontend
cd ../frontend
npm install
cp .env.example .env.local
# Edite NEXT_PUBLIC_API_URL=http://localhost:8000/api
npm run dev
# Frontend rodando em http://localhost:3000

# 4. Acesse o admin
# http://localhost:8000/admin
```

### Variáveis de Ambiente (backend/.env)

```env
APP_NAME=PaulladaDJ
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paullada_dj
DB_USERNAME=root
DB_PASSWORD=
```

### Variáveis de Ambiente (frontend/.env.local)

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
NEXT_PUBLIC_SITE_URL=http://localhost:3000
```

---

## Deploy no CPanel

### Backend (Laravel)

1. Faça upload da pasta `backend/` para o diretório `public_html/api`
2. Configure o `.env` com dados do banco MySQL do CPanel
3. Execute via SSH ou Terminal do CPanel:
   ```bash
   cd public_html/api
   composer install --no-dev --optimize-autoloader
   php artisan migrate --seed
   php artisan config:cache
   php artisan route:cache
   php artisan storage:link
   ```
4. Ajuste o `public/index.php` para apontar para o bootstrap correto
5. Configure o **Cron job** para `php artisan schedule:run`

### Frontend (Next.js)

1. Faça o build:
   ```bash
   cd frontend
   npm run build
   npm run export
   ```
2. Envie o conteúdo de `frontend/out/` para `public_html/`
3. Crie ou atualize o arquivo `.htaccess` na raiz com regras de rewrite

---

## Credenciais do Admin

| Campo    | Valor                     |
|----------|---------------------------|
| E-mail   | admin@paullada.com.br     |
| Senha    | admin123                  |

> **⚠️ Importante:** Altere a senha padrão imediatamente após o primeiro login em produção.

---

## Links

- **Instagram:** [@paulladadjrj](https://instagram.com/paulladadjrj)
- **Site (produção):** [https://paulladadj.com.br](https://paulladadj.com.br)

---

## Licença

Este projeto está licenciado sob a licença MIT.
