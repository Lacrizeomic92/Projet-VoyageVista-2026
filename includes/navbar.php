<?php
$user = current_user();
$cartCount = count(Panier::getItems());
$currentPage = $_GET['page'] ?? 'home';

function nav_class(string $page, string $currentPage, string $baseClass = ''): string
{
    $classes = trim($baseClass);

    if ($page === $currentPage) {
        $classes = trim($classes . ' is-active');
    }

    return $classes ? ' class="' . e($classes) . '"' : '';
}
?>

<nav class="navbar">
    <a href="index.php" class="brand">
        <img src="assets/images/logo-voyagevista.png" alt="Logo VoyageVista" class="site-logo">
    </a>

    <a href="index.php?page=panier"<?php echo nav_class('panier', $currentPage, 'cart-nav-btn'); ?>>
        <span>Panier</span>
        <span class="cart-count"><?php echo $cartCount; ?></span>
    </a>

    <button class="menu-toggle">☰</button>

    <div class="nav-links">
        <a href="index.php?page=destinations"<?php echo nav_class('destinations', $currentPage); ?>>Destinations</a>
        <a href="index.php?page=transports"<?php echo nav_class('transports', $currentPage); ?>>Transports</a>
        <a href="index.php?page=hebergements"<?php echo nav_class('hebergements', $currentPage); ?>>Hébergements</a>
        <a href="index.php?page=activites"<?php echo nav_class('activites', $currentPage); ?>>Activités</a>
        <a href="index.php?page=favoris"<?php echo nav_class('favoris', $currentPage); ?>>Favoris</a>

        <?php if ($user) { ?>
            <a href="index.php?page=profil"<?php echo nav_class('profil', $currentPage); ?>>Profil</a>

            <?php if (is_admin()) { ?>
                <a href="index.php?page=admin"<?php echo nav_class('admin', $currentPage); ?>>Admin</a>
            <?php } ?>

            <a href="actions/logout.php" class="login-btn">Déconnexion</a>
        <?php } else { ?>
            <a href="index.php?page=register"<?php echo nav_class('register', $currentPage); ?>>Inscription</a>
            <a href="index.php?page=login"<?php echo nav_class('login', $currentPage, 'login-btn'); ?>>Connexion</a>
        <?php } ?>
    </div>
</nav>
