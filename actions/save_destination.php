<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=admin');
}

$requiredFields = ['name', 'country', 'category', 'description', 'image_url'];

foreach ($requiredFields as $field) {
    if (trim((string) ($_POST[$field] ?? '')) === '') {
        redirect_with_flash('../index.php?page=admin', 'error', 'Merci de remplir les champs principaux de la destination.');
    }
}

$destinationId = create_destination([
    'name' => $_POST['name'] ?? '',
    'country' => $_POST['country'] ?? '',
    'category' => $_POST['category'] ?? '',
    'duration_type' => $_POST['duration_type'] ?? 'court',
    'budget_level' => $_POST['budget_level'] ?? 'economique',
    'audience' => $_POST['audience'] ?? 'etudiant',
    'student_tag' => $_POST['student_tag'] ?? 'Nouveau',
    'description' => $_POST['description'] ?? '',
    'base_price' => $_POST['base_price'] ?? 0,
    'daily_budget' => $_POST['daily_budget'] ?? 0,
    'student_score' => $_POST['student_score'] ?? 0,
    'image_url' => $_POST['image_url'] ?? '',
]);

if (!$destinationId) {
    redirect_with_flash('../index.php?page=admin', 'error', 'Impossible d’ajouter la destination.');
}

redirect_with_flash('../index.php?page=admin', 'success', 'Destination ajoutée avec succès.');
