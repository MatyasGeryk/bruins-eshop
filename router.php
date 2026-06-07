<?php
// router.php — pro PHP built-in server (php -S localhost:8000 router.php)
// Servíruje statické soubory přímo, zbytek posílá na .php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$file = __DIR__ . $uri;

// statické soubory
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

if ($uri === '/' || $uri === '') {
    require __DIR__ . '/index.php';
    return true;
}

// .php existující soubor
$phpFile = __DIR__ . rtrim($uri, '/');
if (is_file($phpFile)) {
    require $phpFile;
    return true;
}
if (is_file($phpFile . '.php')) {
    require $phpFile . '.php';
    return true;
}

// 404
http_response_code(404);
require __DIR__ . '/404.php';
return true;
