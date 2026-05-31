<?php

function get_pdo_connection()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $lastException = null;
    $databaseName = getenv('DB_NAME') ?: 'voyagevista';
    $databaseUser = getenv('DB_USER') ?: 'root';
    $databasePassword = getenv('DB_PASSWORD');
    $databaseHost = getenv('DB_HOST') ?: '127.0.0.1';
    $databasePort = getenv('DB_PORT') ?: '';
    $databaseSocket = getenv('DB_SOCKET') ?: '';

    $connections = [];

    if ($databaseSocket !== '') {
        $connections[] = [
            'dsn' => sprintf('mysql:unix_socket=%s;dbname=%s;charset=utf8mb4', $databaseSocket, $databaseName),
            'user' => $databaseUser,
            'password' => $databasePassword !== false ? $databasePassword : '',
        ];
    }

    if ($databasePort !== '') {
        $connections[] = [
            'dsn' => sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $databaseHost, $databasePort, $databaseName),
            'user' => $databaseUser,
            'password' => $databasePassword !== false ? $databasePassword : '',
        ];
    }

    $connections = array_merge($connections, [
        // MAMP macOS : socket le plus courant.
        [
            'dsn' => sprintf('mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => 'root',
        ],
        // MAMP MySQL 8 selon certaines installations.
        [
            'dsn' => sprintf('mysql:unix_socket=/Applications/MAMP/Library/bin/mysql80/data/mysql.sock;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => 'root',
        ],
        // MAMP quand MySQL est exposé par port.
        [
            'dsn' => sprintf('mysql:host=127.0.0.1;port=8889;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => 'root',
        ],
        [
            'dsn' => sprintf('mysql:host=127.0.0.1;port=3307;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => 'root',
        ],
        // WAMP / MySQL local classique : souvent root sans mot de passe.
        [
            'dsn' => sprintf('mysql:host=127.0.0.1;port=3306;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => '',
        ],
        // Certaines installations WAMP/MAMP gardent root/root sur 3306.
        [
            'dsn' => sprintf('mysql:host=127.0.0.1;port=3306;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => 'root',
        ],
        // Dernier essai : paramètres hôte/port par défaut de PDO.
        [
            'dsn' => sprintf('mysql:host=localhost;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => '',
        ],
        [
            'dsn' => sprintf('mysql:host=localhost;dbname=%s;charset=utf8mb4', $databaseName),
            'user' => 'root',
            'password' => 'root',
        ],
    ]);

    $seenConnections = [];
    $connections = array_filter($connections, function ($connection) use (&$seenConnections) {
        $key = $connection['dsn'] . '|' . $connection['user'] . '|' . $connection['password'];

        if (isset($seenConnections[$key])) {
            return false;
        }

        $seenConnections[$key] = true;
        return true;
    });

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

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

    throw $lastException;
}
