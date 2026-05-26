<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=panier');
}

if (!db_is_available()) {
    redirect_with_flash('../index.php?page=panier', 'error', db_error_message());
}

$stayContext = get_stay_context();

if (!($stayContext['dates_valid'] ?? true)) {
    redirect_with_flash('../index.php?page=panier', 'error', 'Les dates du séjour sont incohérentes. La réservation a été bloquée.');
}

$cardName = trim((string) ($_POST['card_name'] ?? ''));
$cardNumber = preg_replace('/\D+/', '', (string) ($_POST['card_number'] ?? ''));
$cardExpiry = trim((string) ($_POST['card_expiry'] ?? ''));
$cardCvv = preg_replace('/\D+/', '', (string) ($_POST['card_cvv'] ?? ''));

if ($cardName === '' || strlen($cardNumber) < 12 || $cardExpiry === '' || strlen($cardCvv) < 3) {
    redirect_with_flash('../index.php?page=panier', 'error', 'Merci de remplir le bloc de paiement simulé avant validation.');
}

$items = Panier::getItems();

if (empty($items)) {
    redirect_with_flash('../index.php?page=panier', 'error', 'Votre panier est vide.');
}

$reservation = create_reservation_from_cart(
    (int) current_user()['id'],
    $items,
    [
        'card_name' => $cardName,
        'card_number' => $cardNumber,
        'card_expiry' => $cardExpiry,
        'card_cvv' => $cardCvv,
    ],
    $stayContext
);

if (!$reservation) {
    redirect_with_flash('../index.php?page=panier', 'error', 'Impossible de valider la réservation pour le moment.');
}

Panier::clear();

redirect_with_flash(
    '../index.php?page=profil',
    'success',
    'Réservation confirmée avec la référence ' . $reservation['reference'] . '.'
);
