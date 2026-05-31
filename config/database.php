<?php

function get_pdo_connection()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $lastException = null;
    $connections = [
        ['mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=voyagevista;charset=utf8mb4', 'root'],
        ['mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=voyagevista;charset=utf8mb4', ''],
        ['mysql:host=127.0.0.1;port=3307;dbname=voyagevista;charset=utf8mb4', 'root'],
        ['mysql:host=127.0.0.1;port=3306;dbname=voyagevista;charset=utf8mb4', 'root'],
        ['mysql:host=127.0.0.1;port=8889;dbname=voyagevista;charset=utf8mb4', 'root'],
    ];

    foreach ($connections as [$dsn, $password]) {
        try {
            $pdo = new PDO(
                $dsn,
                "root",
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return $pdo;
        } catch (PDOException $exception) {
            $lastException = $exception;
        }
    }

    throw $lastException;
}
