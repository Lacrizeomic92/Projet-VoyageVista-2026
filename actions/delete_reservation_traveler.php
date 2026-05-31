<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=profil#mes-reservations');
}

$reservationId = (int) ($_POST['reservation_id'] ?? 0);
$travelerId = (int) ($_POST['traveler_id'] ?? 0);
$redirectUrl = '../index.php?page=modifier_reservation&id=' . $reservationId;

if ($reservationId <= 0 || $travelerId <= 0) {
    redirect_with_flash('../index.php?page=profil#mes-reservations', 'error', 'Voyageur associé introuvable.');
}

if (!delete_reservation_traveler($travelerId, $reservationId, (int) current_user()['id'])) {
    redirect_with_flash($redirectUrl, 'error', 'Impossible de supprimer ce voyageur.');
}

redirect_with_flash($redirectUrl, 'success', 'Voyageur associé supprimé du séjour.');
