# Guia de Instalação - Paullada DJ Landing Page

## Repositório GitHub
https://github.com/kdkhost/paullada-dj-landingpage

## Pré-requisitos no cPanel

1. **PHP 8.x** - No cPanel, vá em "Select PHP Version" e selecione **PHP 8.3** (já habilitado)
2. **MySQL** - Banco já criado: `paulladadjcom_dj` (usuário: `paulladadjcom_djuser`)
3. **Git** - Verificar se está disponível no servidor

## Passo a Passo

### 1. Upload dos arquivos pelo cPanel File Manager

**Opção A - Via Git (recomendado):**
- Acesse o Gerenciador de Arquivos do cPanel
- Navegue até `/home/paulladadjcom/`
- Se Git estiver disponível via terminal SSH, clone o repositório:
  ```bash
  git clone https://github.com/kdkhost/paullada-dj-landingpage.git temp
  cp -r temp/backend/* laravel/
  cp -r temp/frontend/out/* public_html/
  rm -rf temp
  ```

**Opção B - Upload Manual:**
- Baixe o ZIP do repositório em: https://github.com/kdkhost/paullada-dj-landingpage/archive/refs/heads/master.zip
- Faça upload do ZIP pelo FileManager do cPanel
- Extraia na raiz da conta
- Copie `backend/` para uma pasta `laravel/` (fora do public_html)
- Copie `frontend/out/*` para `public_html/`

### 2. Configurar o Laravel

```bash
cd /home/paulladadjcom/laravel
cp .env.example .env
# Editar .env com:
#   DB_DATABASE=paulladadjcom_dj
#   DB_USERNAME=paulladadjcom_djuser
#   DB_PASSWORD=DjPaullada2026!
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
php artisan optimize
chmod -R 777 storage bootstrap/cache
```

### 3. Configurar .htaccess no public_html

O arquivo `.htaccess` em `public_html/` deve conter:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^api/ index.php [L]
    RewriteRule ^admin/ index.php [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.html [L]
</IfModule>
```

### 4. Criar index.php no public_html (Bootstrap do Laravel para /api e /admin)

```php
<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$prefix = '/home/paulladadjcom/laravel';

if (str_starts_with($uri, '/api') || str_starts_with($uri, '/admin')) {
    $_SERVER['SCRIPT_FILENAME'] = $prefix . '/public/index.php';
    chdir($prefix);
    require $prefix . '/public/index.php';
    exit;
}

http_response_code(404);
echo '404 Not Found';
```

### 5. Acessar o site

- **Site:** https://paulladadj.com.br
- **Admin:** https://paulladadj.com.br/admin
- **Login:** admin@paullada.com.br
- **Senha:** admin123

### 6. Primeiros passos no Admin

1. Acesse o painel admin com as credenciais acima
2. Vá em **Configurações do Site** para ajustar as informações
3. Vá em **Redes Sociais** para adicionar Facebook e WhatsApp
4. Adicione shows em **Agenda de Shows**
5. Faça upload de fotos/vídeos em **Upload de Arquivos**
6. Adicione à Galeria em **Galeria**

## Estrutura de Diretórios

```
/home/paulladadjcom/
├── laravel/              # Laravel backend (fora do public_html)
│   ├── app/
│   ├── config/
│   ├── public/
│   └── ...
├── public_html/          # Document root
│   ├── index.html        # Next.js frontend (página principal)
│   ├── _next/            # Assets estáticos do Next.js
│   ├── index.php         # Bootstrap do Laravel para /api e /admin
│   ├── .htaccess         # Regras de reescrita
│   ├── storage/          # Uploads (symlink → ../laravel/storage/app/public)
│   └── ...
```

## Credenciais

- **Admin:** admin@paullada.com.br / admin123
- **MySQL:** paulladadjcom_djuser / DjPaullada2026!
- **Instagram:** @paulladadjrj
