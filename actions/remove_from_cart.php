<?php
require_once '../classes/Panier.php';

$id = $_GET['id'] ?? null;

if ($id) {
    Panier::remove($id);
}

header('Location: ../index.php?page=panier');
exit;