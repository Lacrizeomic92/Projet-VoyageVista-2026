<?php
require_once '../classes/Panier.php';

Panier::init();

$item = [
    'type' => $_POST['type'] ?? 'Élément',
    'nom' => $_POST['nom'] ?? 'Sans nom',
    'details' => $_POST['details'] ?? '',
    'prix' => $_POST['prix'] ?? 0
];

Panier::add($item);

$redirect = $_POST['redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? '../index.php?page=panier');

header('Location: ' . $redirect);
exit;
