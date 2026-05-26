<?php
$user = current_user();
$cartCount = count(Panier::getItems());
?>

<nav class="navbar">
    <a href="index.php" class="brand">
        <img src="assets/images/logo-voyagevista.png" alt="Logo VoyageVista" class="site-logo">
    </a>

    <a href="index.php?page=panier" class="cart-nav-btn">
        Panier (<?php echo $cartCount; ?>)
    </a>

    <button class="menu-toggle">☰</button>

    <div class="nav-links">
        <a href="index.php?page=destinations">Destinations</a>
        <a href="index.php?page=transports">Transports</a>
        <a href="index.php?page=hebergements">Hébergements</a>
        <a href="index.php?page=activites">Activités</a>
        <a href="index.php?page=favoris">Favoris</a>

        <?php if ($user) { ?>
            <a href="index.php?page=profil">Profil</a>

            <?php if (is_admin()) { ?>
                <a href="index.php?page=admin">Admin</a>
            <?php } ?>

            <a href="actions/logout.php" class="login-btn">Déconnexion</a>
        <?php } else { ?>
            <a href="index.php?page=register">Inscription</a>
            <a href="index.php?page=login" class="login-btn">Connexion</a>
        <?php } ?>
    </div>
</nav>
