<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=panier');
}

$itemId = trim((string) ($_POST['id'] ?? ''));
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

if ($itemId === '') {
    redirect_with_flash('../index.php?page=panier', 'error', 'Élément de panier introuvable.');
}

Panier::update($itemId, ['quantity' => $quantity]);

redirect_with_flash('../index.php?page=panier', 'success', 'Panier mis à jour.');
