<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

$id = $_GET['id'] ?? null;
$redirect = trim((string) ($_GET['redirect'] ?? 'index.php?page=panier'));

if ($id) {
    Panier::remove($id);
}

if (!str_starts_with($redirect, 'index.php')) {
    $redirect = 'index.php?page=panier';
}

redirect_to('../' . $redirect);
