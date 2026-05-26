<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/repositories.php';
require_once dirname(__DIR__) . '/classes/Panier.php';
require_once dirname(__DIR__) . '/classes/Favoris.php';

Panier::init();
Favoris::init();
