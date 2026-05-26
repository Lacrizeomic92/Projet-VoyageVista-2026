<?php

function get_pdo_connection()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('VOYAGEVISTA_DB_HOST') ?: '127.0.0.1';
    $port = getenv('VOYAGEVISTA_DB_PORT') ?: '3307';
    $database = getenv('VOYAGEVISTA_DB_NAME') ?: 'voyagevista';
    $username = getenv('VOYAGEVISTA_DB_USER') ?: 'root';
    $password = getenv('VOYAGEVISTA_DB_PASSWORD');

    if ($password === false) {
        $password = '';
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $host,
        $port,
        $database
    );

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}