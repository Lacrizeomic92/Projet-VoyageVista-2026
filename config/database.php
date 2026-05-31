<?php

function get_pdo_connection()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseName = getenv('DB_NAME') ?: 'voyagevista';

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $connections = [];

    /*
     * 1) Configuration personnalisée par variables d'environnement
     * Utile si le professeur ou un membre du groupe veut forcer sa propre config.
     */
    $envHost = getenv('DB_HOST');
    $envPort = getenv('DB_PORT');
    $envUser = getenv('DB_USER');
    $envPassword = getenv('DB_PASSWORD');
    $envSocket = getenv('DB_SOCKET');

    if ($envSocket) {
        $connections[] = [
            'dsn' => "mysql:unix_socket={$envSocket};dbname={$databaseName};charset=utf8mb4",
            'user' => $envUser ?: 'root',
            'password' => $envPassword !== false ? $envPassword : '',
        ];
    }

    if ($envHost) {
        $portPart = $envPort ? ";port={$envPort}" : "";
        $connections[] = [
            'dsn' => "mysql:host={$envHost}{$portPart};dbname={$databaseName};charset=utf8mb4",
            'user' => $envUser ?: 'root',
            'password' => $envPassword !== false ? $envPassword : '',
        ];
    }

    /*
     * 2) Configurations automatiques les plus courantes
     */
    $connections = array_merge($connections, [
        // WAMP / MariaDB récent : souvent port 3307, root sans mot de passe.
        [
            'dsn' => "mysql:host=127.0.0.1;port=3307;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => '',
        ],

        // WAMP / MySQL classique : souvent port 3306, root sans mot de passe.
        [
            'dsn' => "mysql:host=127.0.0.1;port=3306;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => '',
        ],

        // MAMP macOS : port MySQL/MariaDB courant.
        [
            'dsn' => "mysql:host=127.0.0.1;port=8889;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => 'root',
        ],

        // MAMP macOS : socket courant.
        [
            'dsn' => "mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => 'root',
        ],

        // MAMP MySQL 8 selon certaines installations.
        [
            'dsn' => "mysql:unix_socket=/Applications/MAMP/Library/bin/mysql80/data/mysql.sock;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => 'root',
        ],

        // Cas où root/root est utilisé sur 3307.
        [
            'dsn' => "mysql:host=127.0.0.1;port=3307;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => 'root',
        ],

        // Cas où root/root est utilisé sur 3306.
        [
            'dsn' => "mysql:host=127.0.0.1;port=3306;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => 'root',
        ],

        // Derniers essais génériques.
        [
            'dsn' => "mysql:host=localhost;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => '',
        ],
        [
            'dsn' => "mysql:host=localhost;dbname={$databaseName};charset=utf8mb4",
            'user' => 'root',
            'password' => 'root',
        ],
    ]);

    $lastException = null;

    foreach ($connections as $connection) {
        try {
            $pdo = new PDO(
                $connection['dsn'],
                $connection['user'],
                $connection['password'],
                $options
            );

            return $pdo;
        } catch (PDOException $exception) {
            $lastException = $exception;
        }
    }

    die(
        "Connexion MySQL impossible. " .
        "Vérifiez que la base '{$databaseName}' existe, que le fichier database/voyagevista.sql a été importé, " .
        "et que MySQL/MariaDB est bien lancé."
    );
}