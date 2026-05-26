<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=register');
}

$firstname = trim((string) ($_POST['firstname'] ?? ''));
$lastname = trim((string) ($_POST['lastname'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

if ($firstname === '' || $lastname === '' || $email === '' || $password === '') {
    redirect_with_flash('../index.php?page=register', 'error', 'Tous les champs sont obligatoires.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_flash('../index.php?page=register', 'error', 'Adresse email invalide.');
}

if (strlen($password) < 6) {
    redirect_with_flash('../index.php?page=register', 'error', 'Le mot de passe doit contenir au moins 6 caractères.');
}

if ($password !== $passwordConfirmation) {
    redirect_with_flash('../index.php?page=register', 'error', 'Les deux mots de passe ne correspondent pas.');
}

if (find_user_by_email($email)) {
    redirect_with_flash('../index.php?page=register', 'error', 'Un compte existe déjà avec cet email.');
}

$userId = create_user([
    'firstname' => $firstname,
    'lastname' => $lastname,
    'email' => $email,
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    'role' => 'voyageur',
]);

if (!$userId) {
    redirect_with_flash('../index.php?page=register', 'error', 'Impossible de créer le compte pour le moment.');
}

$_SESSION['user'] = find_user_by_id((int) $userId);
sync_favorites_after_login((int) $userId);
add_notification((int) $userId, 'Bienvenue sur VoyageVista', 'Votre compte a bien été créé. Vous pouvez maintenant composer votre séjour.');

redirect_with_flash('../index.php?page=profil', 'success', 'Compte créé avec succès.');
