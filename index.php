<?php
$page = $_GET['page'] ?? 'home';

$allowed = [
    'home',
    'destinations',
    'transports',
    'hebergements',
    'activites',
    'favoris',
    'panier',
    'profil',
    'login',
    'admin'
];

if (!in_array($page, $allowed)) {
    $page = 'home';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoyageVista</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <script defer src="assets/js/app.js"></script>
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <main>
        <?php include "pages/$page.php"; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>