<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=login');
}

$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$redirect = trim((string) ($_POST['redirect'] ?? ''));

if ($email === '' || $password === '') {
    redirect_with_flash('../index.php?page=login', 'error', 'Merci de renseigner votre email et votre mot de passe.');
}

if (!db_is_available()) {
    redirect_with_flash('../index.php?page=login', 'error', db_error_message());
}

$user = find_user_by_email($email);

if (!$user || !password_verify($password, $user['password_hash'])) {
    redirect_with_flash('../index.php?page=login', 'error', 'Identifiants invalides.');
}

$_SESSION['user'] = find_user_by_id((int) $user['id']);
sync_favorites_after_login((int) $user['id']);

if ($redirect !== '' && str_starts_with($redirect, 'index.php')) {
    redirect_with_flash('../' . $redirect, 'success', 'Connexion réussie.');
}

redirect_with_flash('../index.php?page=profil', 'success', 'Connexion réussie.');
