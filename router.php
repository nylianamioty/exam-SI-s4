<?php
// router.php for PHP built-in server

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

$requested = __DIR__ . $uri;

// Serve the requested resource as-is if it exists and is a file
if ($uri !== '/' && file_exists($requested) && is_file($requested)) {
    return false;
}

// Otherwise, route the request to ws/index.php
require_once __DIR__ . '/ws/index.php';
