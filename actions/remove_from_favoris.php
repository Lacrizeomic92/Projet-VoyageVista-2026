<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

$id = $_GET['id'] ?? null;
$dbId = $_GET['db_id'] ?? null;
$redirect = trim((string) ($_GET['redirect'] ?? 'index.php?page=favoris'));

if ($id) {
    Favoris::remove($id);
}

if ($dbId && is_logged_in()) {
    remove_favorite_for_user((int) $dbId, (int) current_user()['id']);
}

if (!str_starts_with($redirect, 'index.php')) {
    $redirect = 'index.php?page=favoris';
}

redirect_to('../' . $redirect);
