<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = __DIR__ . '/public' . $uri;

if ($uri !== '/' && is_file($path)) {
    $mimes = [
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
        'gif' => 'image/gif', 'webp' => 'image/webp', 'avif' => 'image/avif',
        'svg' => 'image/svg+xml', 'ico' => 'image/x-icon',
        'css' => 'text/css', 'js' => 'application/javascript',
        'json' => 'application/json', 'map' => 'application/json',
        'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf',
        'mp4' => 'video/mp4', 'webm' => 'video/webm',
    ];
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: public, max-age=3600');
    readfile($path);
    return true;
}

require_once __DIR__ . '/public/index.php';
