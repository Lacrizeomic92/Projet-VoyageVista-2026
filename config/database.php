<?php

function get_pdo_connection()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        $pdo = new PDO(
            "mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=voyagevista;charset=utf8mb4",
            "root",
            "root",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        die("Erreur PDO : " . $e->getMessage());
    }

    return $pdo;
}