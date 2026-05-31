<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=profil#mes-reservations');
}

$reservationId = (int) ($_POST['reservation_id'] ?? 0);
$reservationItemId = (int) ($_POST['reservation_item_id'] ?? 0);

if ($reservationId <= 0 || $reservationItemId <= 0) {
    redirect_with_flash('../index.php?page=profil#mes-reservations', 'error', 'Transport introuvable.');
}

$redirectUrl = '../index.php?page=modifier_reservation&id=' . $reservationId;

if (!cancel_reservation_transport($reservationId, $reservationItemId, (int) current_user()['id'])) {
    redirect_with_flash($redirectUrl, 'error', 'Impossible d’annuler ce transport.');
}

redirect_with_flash($redirectUrl, 'success', 'Transport annulé. Les places disponibles ont été remises à jour.');
