<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();
require_admin();

$destinationId = (int) ($_GET['id'] ?? 0);

if ($destinationId <= 0) {
    redirect_with_flash('../index.php?page=admin', 'error', 'Destination introuvable.');
}

if (!delete_destination($destinationId)) {
    redirect_with_flash('../index.php?page=admin', 'error', 'Suppression impossible.');
}

redirect_with_flash('../index.php?page=admin', 'success', 'Destination supprimée.');
