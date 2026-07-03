<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$laravelPaths = ['/api', '/admin', '/vendor', '/password', '/register', '/home'];
foreach ($laravelPaths as $prefix) {
    if (strpos($uri, $prefix) === 0) {
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/laravel/public/index.php';
        chdir(__DIR__ . '/laravel');
        require __DIR__ . '/laravel/public/index.php';
        exit;
    }
}
http_response_code(404);
