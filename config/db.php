<?php

require_once __DIR__ . '/config.php';

// Load .env from project root
load_env(dirname(__DIR__) . '/.env');

function db()
{
    static $pdo = null;
    if ($pdo) return $pdo;

    $host = env('DB_HOST', '127.0.0.1');
    $name = env('DB_NAME', 'fam_db');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');

    $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
