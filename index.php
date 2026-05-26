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
    'admin',
    'offres',
    'circuit'
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

    <link rel="stylesheet" href="assets/css/style.css?v=2">

    
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
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