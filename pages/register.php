<?php
$user = current_user();
?>

<section class="auth-page">
    <div class="auth-card auth-card-register">
        <div class="auth-intro">
            <span>Inscription</span>
            <h1>Créez votre compte VoyageVista</h1>
            <p>
                En quelques secondes, vous pourrez enregistrer vos réservations,
                recevoir des notifications et gérer votre profil.
            </p>
        </div>

        <?php if ($user) { ?>
            <div class="auth-already">
                <h2>Compte déjà actif</h2>
                <p>
                    Votre session est ouverte avec l’adresse
                    <?php echo e($user['email']); ?>.
                </p>
                <div class="auth-actions">
                    <a href="index.php?page=profil">Voir mon profil</a>
                    <a href="actions/logout.php" class="secondary">Changer de compte</a>
                </div>
            </div>
        <?php } else { ?>
            <form action="actions/register_action.php" method="POST" class="auth-form auth-form-grid">
                <div>
                    <label for="register-firstname">Prénom</label>
                    <input id="register-firstname" type="text" name="firstname" required>
                </div>

                <div>
                    <label for="register-lastname">Nom</label>
                    <input id="register-lastname" type="text" name="lastname" required>
                </div>

                <div class="full-width">
                    <label for="register-email">Email</label>
                    <input id="register-email" type="email" name="email" required>
                </div>

                <div>
                    <label for="register-password">Mot de passe</label>
                    <input id="register-password" type="password" name="password" minlength="6" required>
                </div>

                <div>
                    <label for="register-password-confirmation">Confirmation</label>
                    <input id="register-password-confirmation" type="password" name="password_confirmation" minlength="6" required>
                </div>

                <button type="submit" class="full-width">Créer mon compte</button>
            </form>

            <div class="auth-side-info">
                <h2>Déjà inscrit ?</h2>
                <p>Connectez-vous pour finaliser un panier ou suivre vos notifications.</p>
                <div class="auth-actions">
                    <a href="index.php?page=login">Aller à la connexion</a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
