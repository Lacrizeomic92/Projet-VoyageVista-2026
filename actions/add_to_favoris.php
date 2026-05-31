<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=favoris');
}

$redirect = trim((string) ($_POST['redirect'] ?? 'index.php?page=favoris'));

if (str_contains($redirect, 'index.php')) {
    $redirect = 'index.php' . substr($redirect, strpos($redirect, 'index.php') + strlen('index.php'));
} else {
    $redirect = 'index.php?page=favoris'; // ou panier
}

$sourceType = strtolower((string) ($_POST['source_type'] ?? ''));
$sourceId = (int) ($_POST['source_id'] ?? 0);
$catalogItem = null;

if (in_array($sourceType, ['transport', 'hebergement', 'activite'], true) && $sourceId > 0) {
    $catalogItem = get_catalog_item($sourceType, $sourceId);
}

$item = [
    'type' => $_POST['type'] ?? cart_label_from_source_type($sourceType),
    'nom' => $_POST['nom'] ?? ($catalogItem['nom'] ?? 'Favori'),
    'details' => $_POST['details'] ?? ($catalogItem['details'] ?? ''),
    'image' => $_POST['image'] ?? ($catalogItem['image'] ?? ''),
    'source_type' => $sourceType,
    'source_id' => $sourceId,
];

Favoris::add($item);

if (is_logged_in()) {
    save_favorite_for_user((int) current_user()['id'], $item);
    sync_favorites_after_login((int) current_user()['id']);
}

redirect_with_flash('../' . $redirect, 'success', 'Élément ajouté aux favoris.');
