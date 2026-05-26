<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=panier');
}

$redirect = trim((string) ($_POST['redirect'] ?? 'index.php?page=panier'));

if (!str_starts_with($redirect, 'index.php')) {
    $redirect = 'index.php?page=panier';
}

$stayContext = get_stay_context();

if (!($stayContext['dates_valid'] ?? true)) {
    redirect_with_flash('../' . $redirect, 'error', 'Les dates du séjour sont incohérentes. Corrigez-les avant d’ajouter un élément.');
}

$item = build_cart_item([
    'type' => $_POST['type'] ?? '',
    'nom' => $_POST['nom'] ?? '',
    'details' => $_POST['details'] ?? '',
    'prix' => $_POST['prix'] ?? 0,
    'unit_price' => $_POST['unit_price'] ?? null,
    'quantity' => $_POST['quantity'] ?? 1,
    'image' => $_POST['image'] ?? '',
    'source_type' => $_POST['source_type'] ?? '',
    'source_id' => $_POST['source_id'] ?? 0,
]);

if (!$item) {
    redirect_with_flash('../' . $redirect, 'error', 'Impossible d’ajouter cet élément au panier.');
}

Panier::add($item);

redirect_with_flash('../' . $redirect, 'success', 'Élément ajouté au panier.');
