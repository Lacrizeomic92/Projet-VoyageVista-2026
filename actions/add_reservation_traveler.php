<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=profil#mes-reservations');
}

$reservationId = (int) ($_POST['reservation_id'] ?? 0);
$redirectUrl = '../index.php?page=modifier_reservation&id=' . $reservationId;

if ($reservationId <= 0) {
    redirect_with_flash('../index.php?page=profil#mes-reservations', 'error', 'Réservation introuvable.');
}

$added = add_reservation_traveler($reservationId, (int) current_user()['id'], [
    'firstname' => $_POST['firstname'] ?? '',
    'lastname' => $_POST['lastname'] ?? '',
    'email' => $_POST['email'] ?? '',
]);

if (!$added) {
    redirect_with_flash($redirectUrl, 'error', 'Impossible d’ajouter ce voyageur.');
}

redirect_with_flash($redirectUrl, 'success', 'Voyageur associé ajouté au séjour.');
