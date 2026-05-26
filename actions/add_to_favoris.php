<?php
require_once '../classes/Favoris.php';

Favoris::init();

$item = [
    'type' => $_POST['type'] ?? 'Élément',
    'nom' => $_POST['nom'] ?? 'Sans nom',
    'details' => $_POST['details'] ?? '',
    'image' => $_POST['image'] ?? ''
];

Favoris::add($item);

$_SESSION['last_favori'] = $item['type'] . '-' . $item['nom'];

$redirect = $_POST['redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? '../index.php?page=favoris');

header('Location: ' . $redirect);
exit;