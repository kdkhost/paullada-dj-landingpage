<?php
/**
 * Deployer para Paullada DJ Landing Page
 * Faça upload deste arquivo para /home/paulladadjcom/public_html/deploy.php
 * Depois acesse: https://15.235.57.3/deploy.php?key=djpaullada2026
 * Delete este arquivo após o deploy!
 */

$key = $_GET['key'] ?? '';
if ($key !== 'djpaullada2026') {
    http_response_code(403);
    die('Acesso negado');
}

echo "<pre>Iniciando deploy do Paullada DJ...\n\n";

// Configurações
$repoUrl = 'https://github.com/kdkhost/paullada-dj-landingpage.git';
$homeDir = '/home/paulladadjcom';
$publicHtml = $homeDir . '/public_html';
$laravelDir = $homeDir . '/laravel';
$tmpDir = $homeDir . '/tmp_deploy';
$startTime = microtime(true);

function logMsg($msg) {
    echo "[" . date('H:i:s') . "] " . $msg . "\n";
    ob_flush();
    flush();
}

// 1. Criar diretórios
logMsg("Criando diretórios...");
@mkdir($tmpDir, 0755, true);

// 2. Clonar repositório
logMsg("Clonando repositório do GitHub...");
$output = shell_exec("cd $homeDir && git clone --depth 1 $repoUrl $tmpDir 2>&1");
logMsg($output);

// 3. Verificar se clonou
if (!is_dir($tmpDir . '/backend') || !is_dir($tmpDir . '/frontend')) {
    logMsg("ERRO: Repositório não clonado corretamente.");
    die("Falha no deploy.");
}

// 4. Backup dos arquivos atuais
logMsg("Fazendo backup do public_html atual...");
if (is_dir($publicHtml)) {
    $backupDir = $homeDir . '/backup_' . date('Ymd_His');
    rename($publicHtml, $backupDir);
    logMsg("Backup criado em: $backupDir");
}

// 5. Copiar Laravel
logMsg("Instalando Laravel...");
mkdir($laravelDir, 0755, true);
shell_exec("cp -r $tmpDir/backend/* $laravelDir/");
shell_exec("cp $tmpDir/backend/.env $laravelDir/.env 2>/dev/null");

// 6. Copiar Next.js static export para public_html
logMsg("Copiando frontend estático...");
mkdir($publicHtml, 0755, true);
if (is_dir($tmpDir . '/frontend/out')) {
    shell_exec("cp -r $tmpDir/frontend/out/* $publicHtml/");
    shell_exec("cp -r $tmpDir/frontend/out/.[!.]* $publicHtml/ 2>/dev/null");
} else {
    logMsg("AVISO: Frontend build não encontrado. Copiando fonte...");
    shell_exec("cd $tmpDir/frontend && npm install && npm run build 2>&1");
    if (is_dir($tmpDir . '/frontend/out')) {
        shell_exec("cp -r $tmpDir/frontend/out/* $publicHtml/");
    }
}

// 7. Criar .htaccess
logMsg("Criando .htaccess...");
$htaccess = <<<HTACCESS
<IfModule mod_rewrite.c>
    RewriteEngine On

    # API e Admin -> Laravel
    RewriteRule ^api/ - [L]
    RewriteRule ^admin/ - [L]

    # Se o arquivo existe, serve diretamente
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.html [L]
</IfModule>
HTACCESS;
file_put_contents($publicHtml . '/.htaccess', $htaccess);

// 8. Criar arquivo de bootstrap do Laravel
logMsg("Criando bootstrap do Laravel...");
$laravelBootstrap = <<<'PHP'
<?php
/**
 * Bootstrap do Laravel para /api e /admin
 */
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
PHP;
file_put_contents($publicHtml . '/index.php', $laravelBootstrap);

// 9. Permissões
logMsg("Ajustando permissões...");
shell_exec("chmod -R 755 $publicHtml");
shell_exec("chmod -R 755 $laravelDir");
shell_exec("chmod -R 777 $laravelDir/storage");
shell_exec("chmod -R 777 $laravelDir/bootstrap/cache");

// 10. Criar symlink do storage
logMsg("Criando storage symlink...");
if (!is_dir($publicHtml . '/storage')) {
    symlink($laravelDir . '/storage/app/public', $publicHtml . '/storage');
}

// 11. Composer install
logMsg("Instalando dependências do Laravel...");
shell_exec("cd $laravelDir && composer install --no-dev --optimize-autoloader 2>&1");
shell_exec("cd $laravelDir && php artisan storage:link 2>&1");
shell_exec("cd $laravelDir && php artisan optimize 2>&1");

// 12. Limpeza
logMsg("Limpando arquivos temporários...");
shell_exec("rm -rf $tmpDir");

$elapsed = round(microtime(true) - $startTime, 2);
logMsg("\n==========================================");
logMsg("DEPLOY CONCLUÍDO em {$elapsed}s!");
logMsg("==========================================");
logMsg("Site: http://15.235.57.3");
logMsg("Admin: http://15.235.57.3/admin");
logMsg("Login: admin@paullada.com.br / admin123");
logMsg("==========================================");
logMsg("IMPORTANTE: Delete este arquivo (deploy.php) agora!");
