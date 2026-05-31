<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if (!is_logged_in()) {
    redirect_with_flash('../index.php?page=login', 'error', 'Connectez-vous pour modifier une réservation.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=profil');
}

if (!db_is_available()) {
    redirect_with_flash('../index.php?page=profil', 'error', db_error_message());
}

$reservationId = (int) ($_POST['reservation_id'] ?? 0);
$reservationItemId = (int) ($_POST['reservation_item_id'] ?? 0);
$transportId = (int) ($_POST['transport_id'] ?? 0);
$redirect = '../index.php?page=modifier_reservation&id=' . $reservationId;

if ($reservationId <= 0 || $reservationItemId <= 0 || $transportId <= 0) {
    redirect_with_flash($redirect, 'error', 'Impossible de modifier ce transport.');
}

$updated = update_reservation_transport(
    $reservationId,
    $reservationItemId,
    (int) current_user()['id'],
    $transportId
);

if (!$updated) {
    redirect_with_flash($redirect, 'error', 'Ce transport ne peut pas être remplacé.');
}

redirect_with_flash($redirect, 'success', 'Transport remplacé et total de réservation mis à jour.');
