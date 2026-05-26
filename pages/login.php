<?php
$user = current_user();
$redirectTarget = trim((string) ($_GET['redirect'] ?? ''));
?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-intro">
            <span>Connexion</span>
            <h1>Retrouvez votre espace voyageur</h1>
            <p>
                Connectez-vous pour suivre vos réservations, vos notifications
                et finaliser votre séjour sur VoyageVista.
            </p>
        </div>

        <?php if ($user) { ?>
            <div class="auth-already">
                <h2>Vous êtes déjà connecté</h2>
                <p>
                    Bonjour <?php echo e($user['firstname']); ?>,
                    vous pouvez accéder directement à votre profil.
                </p>
                <div class="auth-actions">
                    <a href="index.php?page=profil">Voir mon profil</a>
                    <a href="actions/logout.php" class="secondary">Se déconnecter</a>
                </div>
            </div>
        <?php } else { ?>
            <form action="actions/login_action.php" method="POST" class="auth-form">
                <input type="hidden" name="redirect" value="<?php echo e($redirectTarget); ?>">

                <label for="login-email">Email</label>
                <input
                    id="login-email"
                    type="email"
                    name="email"
                    placeholder="test@voyagevista.fr"
                    required
                >

                <label for="login-password">Mot de passe</label>
                <input
                    id="login-password"
                    type="password"
                    name="password"
                    placeholder="Votre mot de passe"
                    required
                >

                <button type="submit">Se connecter</button>
            </form>

            <div class="auth-side-info">
                <h2>Comptes de démonstration</h2>
                <p><strong>Admin :</strong> admin@voyagevista.fr / admin123</p>
                <p><strong>Voyageur :</strong> test@voyagevista.fr / test123</p>

                <div class="auth-actions">
                    <a href="index.php?page=register">Créer un compte</a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
