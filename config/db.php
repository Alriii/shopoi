<?php
declare(strict_types=1);

// Basic PDO connection helper.
// Reads configuration from environment variables if present, with sensible defaults.
// Expected env vars:
// - DB_HOST (default: localhost)
// - DB_PORT (default: 3306)
// - DB_NAME
// - DB_USER
// - DB_PASS

function getPdoConnection(): PDO
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_NAME') ?: 'shopoi';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, $user, $pass, $options);
}

