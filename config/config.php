<?php
// Démarre la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers pour API
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Gére les requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration
define('SITE_NAME', 'NetVerse');
?>
