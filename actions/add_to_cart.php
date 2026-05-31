<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=panier');
}

$redirect = trim((string) ($_POST['redirect'] ?? 'index.php?page=panier'));

if (str_contains($redirect, 'index.php')) {
    $redirect = 'index.php' . substr($redirect, strpos($redirect, 'index.php') + strlen('index.php'));
} else {
    $redirect = 'index.php?page=favoris'; // ou panier
}

$stayContext = get_stay_context();

if (!($stayContext['dates_valid'] ?? true)) {
    redirect_with_flash('../' . $redirect, 'error', 'Les dates du séjour sont incohérentes. Corrigez-les avant d’ajouter un élément.');
}

$sourceType = strtolower((string) ($_POST['source_type'] ?? ''));
$dateDepart = trim((string) ($stayContext['date_depart'] ?? ''));
$dateRetour = trim((string) ($stayContext['date_retour'] ?? ''));
$dateDetails = '';

if (in_array($sourceType, ['transport', 'hebergement'], true) && ($dateDepart === '' || $dateRetour === '')) {
    redirect_with_flash(
        '../' . $redirect,
        'error',
        'Choisissez une date de départ et une date de retour avant d’ajouter un transport ou un hébergement.'
    );
}

if ($sourceType === 'transport') {
    $dateDetails = 'Aller le ' . format_date_fr($dateDepart) . ' / retour le ' . format_date_fr($dateRetour);
} elseif ($sourceType === 'hebergement') {
    $nights = max(1, (int) round((strtotime($dateRetour) - strtotime($dateDepart)) / 86400));
    $dateDetails = 'Séjour du ' . format_date_fr($dateDepart) . ' au ' . format_date_fr($dateRetour) . ' (' . $nights . ' nuit' . ($nights > 1 ? 's' : '') . ')';
}

$item = build_cart_item([
    'type' => $_POST['type'] ?? '',
    'nom' => $_POST['nom'] ?? '',
    'details' => $_POST['details'] ?? '',
    'prix' => $_POST['prix'] ?? 0,
    'unit_price' => $_POST['unit_price'] ?? null,
    'quantity' => $_POST['quantity'] ?? 1,
    'image' => $_POST['image'] ?? '',
    'source_type' => $sourceType,
    'source_id' => $_POST['source_id'] ?? 0,
    'date_details' => $dateDetails,
]);

if (!$item) {
    redirect_with_flash('../' . $redirect, 'error', 'Impossible d’ajouter cet élément au panier.');
}

Panier::add($item);

redirect_with_flash('../' . $redirect, 'success', 'Élément ajouté au panier.');
