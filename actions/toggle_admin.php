<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();
require_admin();

$userId = (int) ($_GET['id'] ?? 0);
$targetRole = trim((string) ($_GET['role'] ?? 'voyageur'));

if ($userId <= 0 || !in_array($targetRole, ['voyageur', 'admin'], true)) {
    redirect_with_flash('../index.php?page=admin', 'error', 'Action administrateur invalide.');
}

if ((int) current_user()['id'] === $userId && $targetRole !== 'admin') {
    redirect_with_flash('../index.php?page=admin', 'error', 'Vous ne pouvez pas retirer votre propre rôle admin pendant la démo.');
}

if (!update_user_role($userId, $targetRole)) {
    redirect_with_flash('../index.php?page=admin', 'error', 'Impossible de modifier le rôle utilisateur.');
}

redirect_with_flash('../index.php?page=admin', 'success', 'Rôle utilisateur mis à jour.');
