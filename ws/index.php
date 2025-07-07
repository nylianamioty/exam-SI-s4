<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/fonds_routes.php';
require 'routes/type_pret_routes.php';
require 'routes/pret_routes.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle OPTIONS requests for CORS preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

Flight::start();
