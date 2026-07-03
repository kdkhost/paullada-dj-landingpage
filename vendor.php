<?php
header('X-Proxy: paullada-vendor-proxy');

$path = $_GET['path'] ?? '';
if (!$path) { http_response_code(404); exit; }

$cdnMap = [
    'fontawesome-free/css/all.min.css' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
    'icheck-bootstrap/icheck-bootstrap.min.css' => 'https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css',
    'overlayScrollbars/css/OverlayScrollbars.min.css' => 'https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/css/OverlayScrollbars.min.css',
    'overlayScrollbars/js/OverlayScrollbars.min.js' => 'https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/js/OverlayScrollbars.min.js',
    'adminlte/dist/css/adminlte.min.css' => 'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css',
    'adminlte/dist/js/adminlte.min.js' => 'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js',
    'jquery/jquery.min.js' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js',
    'jquery/jquery.js' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js',
    'bootstrap/js/bootstrap.bundle.min.js' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js',
    'bootstrap/css/bootstrap.min.css' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css',
    'paullada-admin.css' => '/vendor/paullada-admin.css?local',
];

if (isset($cdnMap[$path])) {
    $url = $cdnMap[$path];
    if (strpos($url, '?local') !== false) {
        $localPath = __DIR__ . '/' . str_replace('?local', '', $url);
        if (file_exists($localPath)) {
            header('Content-Type: ' . getMimeType($path));
            header('Cache-Control: public, max-age=3600');
            readfile($localPath);
            exit;
        }
        http_response_code(404);
        echo 'Local file not found: ' . htmlspecialchars($path);
        exit;
    }
    if (!$url) { http_response_code(404); exit; }
    $context = stream_context_create(['http' => ['timeout' => 15]]);
    $content = @file_get_contents($url, false, $context);
    if ($content === false) {
        http_response_code(502);
        echo 'Failed to fetch: ' . htmlspecialchars($path);
        exit;
    }
    header('Content-Type: ' . getMimeType($path));
    header('Cache-Control: public, max-age=86400');
    echo $content;
    exit;
}

foreach ($cdnMap as $key => $url) {
    if (strpos($path, $key) === 0 && $url && substr($url, -1) === '/') {
        $relativePath = substr($path, strlen($key));
        $proxyUrl = $url . $relativePath;
        $context = stream_context_create(['http' => ['timeout' => 15]]);
        $content = @file_get_contents($proxyUrl, false, $context);
        if ($content === false) { continue; }
        $ext = pathinfo($relativePath, PATHINFO_EXTENSION);
        $mimeTypes = ['woff2' => 'font/woff2', 'woff' => 'font/woff', 'ttf' => 'font/ttf', 'eot' => 'application/vnd.ms-fontobject', 'svg' => 'image/svg+xml'];
        header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));
        header('Cache-Control: public, max-age=86400');
        echo $content;
        exit;
    }
}

http_response_code(404);
echo 'Vendor asset not found: ' . htmlspecialchars($path);

function getMimeType($path) {
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $map = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];
    return $map[$ext] ?? 'application/octet-stream';
}
