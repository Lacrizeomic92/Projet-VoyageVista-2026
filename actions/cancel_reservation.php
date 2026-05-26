<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

$reservationId = (int) ($_GET['id'] ?? 0);

if ($reservationId <= 0) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Réservation introuvable.');
}

if (!cancel_reservation($reservationId, (int) current_user()['id'])) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Impossible d’annuler cette réservation.');
}

add_notification(
    (int) current_user()['id'],
    'Réservation annulée',
    'Votre réservation a été annulée depuis votre espace profil.'
);

redirect_with_flash('../index.php?page=profil', 'success', 'Réservation annulée.');
