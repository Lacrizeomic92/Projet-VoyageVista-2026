<?php
require_once __DIR__ . '/config/bootstrap.php';

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
    'register',
    'admin',
    'offres',
    'circuit',
    'contact',
    'confidentialite',
    'conditions'
];

if (!in_array($page, $allowed)) {
    $page = 'home';
}

if ($page === 'profil') {
    require_login();
}

if ($page === 'admin') {
    require_login();
    require_admin();
}

$flash = get_flash();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoyageVista</title>

    <link rel="stylesheet" href="assets/css/style.css?v=5">

    
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
        <script defer src="assets/js/app.js"></script>
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <?php if ($flash) { ?>
        <div class="flash-message flash-<?php echo e($flash['type']); ?>">
            <?php echo e($flash['message']); ?>
        </div>
    <?php } ?>

    <main>
        <?php include "pages/$page.php"; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
