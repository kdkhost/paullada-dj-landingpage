# Changelog

Todas as alterações notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.1.0/).

---

## [1.0.0] — 2026-07-02

### ✨ Lançamento Inicial

Versão de lançamento da Landing Page Administrável para Paullada DJ.

#### Configuração do Ambiente

- Configuração do projeto Laravel 13 com PHP 8.3+
- Configuração do projeto Next.js 16 com App Router
- Integração Tailwind CSS v4 com tema dark neon personalizado
- Configuração AdminLTE 4 para o painel administrativo
- Banco de dados MySQL 8 com todas as tabelas e índices
- Variáveis de ambiente para ambientes local e produção

#### Banco de Dados (Migrations)

- `CreateUsersTable` — Usuários do sistema (admin)
- `CreatePersonalAccessTokensTable` — Tokens de API (Sanctum)
- `CreateShowsTable` — Agenda de shows com campos: título, data, local, descrição, link_ingresso, ingresso_ativo
- `CreateGalleryItemsTable` — Galeria de mídia com suporte a fotos e vídeos do YouTube
- `CreateSocialLinksTable` — Links de redes sociais
- `CreateSiteSettingsTable` — Configurações dinâmicas do site
- `CreateAnalyticsEventsTable` — Eventos de analytics (pageview, click, form_submission)

#### Modelos

- `Show` — Gerenciamento de shows com mutator para data e escopo `upcoming()`
- `GalleryItem` — Itens da galeria com suporte a type (photo/video) e thumbnail
- `SocialLink` — Links de redes sociais com ícone e ordenação
- `SiteSetting` — Configurações dinâmicas chave-valor com cache
- `AnalyticsEvent` — Registro de eventos com tipo, URL, user agent e IP
- `ShowQuery` — Modelo para consultas personalizadas de shows via query builder

#### Admin Panel (AdminLTE 4)

- Dashboard com cards de métricas (total de shows, visitas, itens na galeria)
- Gráfico de visitas diárias com Chart.js (últimos 7/30 dias)
- Gráfico de pizza com distribuição de tipos de evento
- CRUD completo de Shows com formulário responsivo
- CRUD completo da Galeria com preview de imagem
- CRUD de Links Sociais com campo de ícone
- Gerenciamento de Configurações do Site (título, descrição, SEO)
- Visualização de eventos de analytics em tabela paginada
- Design premium com tema dark

#### Dashboard Analítico

- Contagem de visitas totais, cliques e submissions
- Gráfico de linha (Chart.js) para visitas nos últimos 7 dias
- Gráfico de pizza para distribuição de eventos (pageview vs click vs form_submission)
- Integração com `AnalyticsTracker` no frontend Next.js
- Envio automático de eventos via API

#### API RESTful Pública

- `GET /api/shows` — Lista de shows futuros com ingressos ativos
- `GET /api/shows/{id}` — Detalhes de um show
- `GET /api/gallery` — Itens da galeria pública
- `GET /api/social-links` — Links de redes sociais
- `GET /api/settings` — Configurações públicas do site
- `POST /api/analytics` — Registro de evento de analytics
- `GET /api/shows/query` — Consulta personalizada de shows por parâmetros

#### Frontend Next.js

- **Navbar** — Navegação responsiva com scroll suave e neon glow
- **Hero** — Apresentação principal com call-to-action
- **About** — Seção "Sobre" com apresentação do DJ
- **Shows** — Grid de cards com agenda, status de ingresso e link
- **Gallery** — Galeria em grid responsivo com modal de visualização
- **Contact** — Formulário de contato com integração API
- **Footer** — Links sociais e informações de contato
- **Layout global** — SEO tags, fontes, metadados Open Graph
- **Página 404** — Página personalizada de erro

#### Preloader Personalizado

- Animação de vinil girando em CSS puro
- Texto "Paullada DJ" com efeito pulsante
- Barra de progresso animada
- Transição suave de fade-out ao carregar
- Detecção automática de carregamento completo

#### Scroll Customizado Neon

- Estilização da barra de rolagem com cores neon
- Suporte a WebKit e Firefox
- Tamanho reduzido e cantos arredondados

#### SEO Completo

- Metadados Open Graph (og:title, og:description, og:image, og:url)
- Twitter Cards (summary_large_image)
- JSON-LD estruturado para `Person` e `Event`
- Sitemap.xml dinâmico com todas as rotas
- Robots.txt configurado
- Títulos e descrições únicas por página
- Schema.org para artista e eventos

#### Tema Dark Neon Premium

- Paleta de cores: preto (#0a0a0a), neon verde (#00ff88), neon roxo (#b300ff)
- Botões com efeito glow e hover animado
- Cards com bordas neon sutis
- Tipografia com fontes system-ui e mono
- Gradientes e sombras personalizadas

#### Deploy

- Servidor: 15.235.57.3
- Backend hospedado em subdomínio/subpasta com Apache
- Frontend exportado como SSG via `next export`
- Configuração .htaccess para rotas amigáveis
- HTTPS configurado com certificado SSL

---

## [1.0.0] - 2026-07-02

### Adicionado

- Estrutura completa do projeto Laravel 13 + Next.js 16
- Migrations e seeders do banco MySQL
- Modelos, controllers e rotas da API RESTful
- Admin panel com AdminLTE 4 e Chart.js
- Frontend completo com Tailwind CSS v4
- Preloader com animação de vinil
- Scroll customizado neon
- SEO (Open Graph, JSON-LD, sitemap, robots)
- Analytics tracker com gráficos no dashboard
- Tema dark neon responsivo
- Deploy no servidor 15.235.57.3
