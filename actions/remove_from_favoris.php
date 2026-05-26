<?php
require_once '../classes/Favoris.php';

$id = $_GET['id'] ?? null;

if ($id) {
    Favoris::remove($id);
}

header('Location: ../index.php?page=favoris');
exit;