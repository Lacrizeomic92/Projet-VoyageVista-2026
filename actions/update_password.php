<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=profil');
}

if (!db_is_available()) {
    redirect_with_flash('../index.php?page=profil', 'error', db_error_message());
}

$currentPassword = (string) ($_POST['current_password'] ?? '');
$newPassword = (string) ($_POST['new_password'] ?? '');
$passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');
$currentUser = current_user();
$user = find_user_by_email((string) ($currentUser['email'] ?? ''));

if (!$user) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Compte utilisateur introuvable.');
}

if ($currentPassword === '' || $newPassword === '' || $passwordConfirmation === '') {
    redirect_with_flash('../index.php?page=profil', 'error', 'Tous les champs du mot de passe sont obligatoires.');
}

if (!password_verify($currentPassword, $user['password_hash'])) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Le mot de passe actuel est incorrect.');
}

if (strlen($newPassword) < 6) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Le nouveau mot de passe doit contenir au moins 6 caractères.');
}

if ($newPassword !== $passwordConfirmation) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Les deux nouveaux mots de passe ne correspondent pas.');
}

if (!update_user_password((int) $currentUser['id'], password_hash($newPassword, PASSWORD_DEFAULT))) {
    redirect_with_flash('../index.php?page=profil', 'error', 'Impossible de modifier le mot de passe.');
}

redirect_with_flash('../index.php?page=profil', 'success', 'Mot de passe mis à jour.');
