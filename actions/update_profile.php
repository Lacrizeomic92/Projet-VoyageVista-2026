<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=profil');
}

$firstname = trim((string) ($_POST['firstname'] ?? ''));
$lastname = trim((string) ($_POST['lastname'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));

if ($firstname === '' || $lastname === '' || $email === '') {
    redirect_with_flash('../index.php?page=profil', 'error', 'Merci de compléter prénom, nom et email.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Adresse email invalide.');
}

$existingUser = find_user_by_email($email);
$currentUser = current_user();

if ($existingUser && (int) $existingUser['id'] !== (int) $currentUser['id']) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Cet email est déjà utilisé par un autre compte.');
}

if (!update_user_profile((int) $currentUser['id'], [
    'firstname' => $firstname,
    'lastname' => $lastname,
    'email' => $email,
])) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Impossible de mettre à jour le profil.');
}

refresh_session_user((int) $currentUser['id']);

redirect_with_flash('../index.php?page=profil', 'success', 'Profil mis à jour.');
