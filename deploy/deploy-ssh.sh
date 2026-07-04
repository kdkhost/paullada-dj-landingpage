#!/bin/bash
# Script de deploy para Paullada DJ Landing Page
# Execute com: bash deploy-ssh.sh
# Requer: git, ssh, composer, npm

REPO_URL="https://github.com/kdkhost/paullada-dj-landingpage.git"
HOME_DIR="/home/paulladadjcom"
PUBLIC_HTML="$HOME_DIR/public_html"
LARAVEL_DIR="$HOME_DIR/laravel"
TMP_DIR="$HOME_DIR/tmp_deploy"

echo "=== Iniciando deploy Paullada DJ ==="

# 1. Clone
echo "[1/7] Clonando repositório..."
rm -rf $TMP_DIR
git clone --depth 1 $REPO_URL $TMP_DIR

# 2. Backup
echo "[2/7] Backup do public_html atual..."
if [ -d "$PUBLIC_HTML" ]; then
    mv "$PUBLIC_HTML" "$HOME_DIR/backup_$(date +%Y%m%d_%H%M%S)"
fi

# 3. Laravel
echo "[3/7] Instalando Laravel..."
mkdir -p $LARAVEL_DIR
cp -r $TMP_DIR/backend/* $LARAVEL_DIR/
cp $TMP_DIR/backend/.env.example $LARAVEL_DIR/.env
cd $LARAVEL_DIR
composer install --no-dev --optimize-autoloader
php artisan key:generate

# 4. Frontend
echo "[4/7] Copiando frontend..."
mkdir -p $PUBLIC_HTML
cp -r $TMP_DIR/frontend/out/* $PUBLIC_HTML/

# 5. .htaccess
echo "[5/7] Configurando .htaccess..."
cat > $PUBLIC_HTML/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^api/ index.php [L]
    RewriteRule ^admin/ index.php [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.html [L]
</IfModule>
EOF

# 6. Bootstrap
echo "[6/7] Criando bootstrap..."
cat > $PUBLIC_HTML/index.php << 'PHPEOF'
<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if (str_starts_with($uri, '/api') || str_starts_with($uri, '/admin')) {
    $_SERVER['SCRIPT_FILENAME'] = '/home/paulladadjcom/laravel/public/index.php';
    chdir('/home/paulladadjcom/laravel');
    require '/home/paulladadjcom/laravel/public/index.php';
    exit;
}
http_response_code(404);
PHPEOF

# 7. Permissões
echo "[7/7] Ajustando permissões..."
chmod -R 755 $PUBLIC_HTML
chmod -R 755 $LARAVEL_DIR
chmod -R 777 $LARAVEL_DIR/storage
chmod -R 777 $LARAVEL_DIR/bootstrap/cache
ln -sf $LARAVEL_DIR/storage/app/public $PUBLIC_HTML/storage

# Limpeza
rm -rf $TMP_DIR

echo ""
echo "=== DEPLOY CONCLUÍDO ==="
echo "Site: https://paulladadj.com.br"
echo "Admin: https://paulladadj.com.br/admin"
echo "Login: admin@paullada.com.br / admin123"
