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
$hebergementId = (int) ($_POST['hebergement_id'] ?? 0);
$redirect = '../index.php?page=modifier_reservation&id=' . $reservationId;

if ($reservationId <= 0 || $reservationItemId <= 0 || $hebergementId <= 0) {
    redirect_with_flash($redirect, 'error', 'Impossible de modifier cet hébergement.');
}

$updated = update_reservation_hebergement(
    $reservationId,
    $reservationItemId,
    (int) current_user()['id'],
    $hebergementId
);

if (!$updated) {
    redirect_with_flash($redirect, 'error', 'Cet hébergement ne peut pas être remplacé.');
}

redirect_with_flash($redirect, 'success', 'Hébergement remplacé et total de réservation mis à jour.');
