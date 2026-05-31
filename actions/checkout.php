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
$expectedTravelers = max(1, (int) ($stayContext['voyageurs'] ?? 1));
$travelerFirstnames = $_POST['traveler_firstname'] ?? [];
$travelerLastnames = $_POST['traveler_lastname'] ?? [];
$travelerEmails = $_POST['traveler_email'] ?? [];
$travelers = [];

for ($index = 0; $index < $expectedTravelers; $index++) {
    $firstname = trim((string) ($travelerFirstnames[$index] ?? ''));
    $lastname = trim((string) ($travelerLastnames[$index] ?? ''));
    $email = trim((string) ($travelerEmails[$index] ?? ''));

    if ($firstname === '' || $lastname === '') {
        redirect_with_flash('../index.php?page=panier', 'error', 'Merci de renseigner le prénom et le nom de chaque voyageur.');
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_flash('../index.php?page=panier', 'error', 'Un email voyageur est invalide.');
    }

    $travelers[] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
    ];
}

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
        'travelers' => $travelers,
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
