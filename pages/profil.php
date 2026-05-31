<?php
$user = current_user();
$dbAvailable = db_is_available();
$reservations = $dbAvailable ? get_user_reservations((int) $user['id']) : [];
$notifications = $dbAvailable ? get_user_notifications((int) $user['id']) : [];
$unreadCount = count(array_filter($notifications, function ($notification) {
    return !(bool) $notification['is_read'];
}));
?>

<section class="profile-page">
    <div class="profile-hero">
        <div>
            <span>Espace voyageur</span>
            <h1><?php echo e($user['firstname'] . ' ' . $user['lastname']); ?></h1>
            <p>
                Gérez votre compte, suivez vos réservations et consultez vos notifications
                depuis un seul espace.
            </p>
        </div>

        <div class="profile-stats">
            <a href="#mes-reservations" class="profile-stat-link">
                <strong><?php echo count($reservations); ?></strong>
                <span>mes réservations</span>
            </a>
            <div>
                <strong><?php echo $unreadCount; ?></strong>
                <span>notification(s) non lue(s)</span>
            </div>
            <div>
                <strong><?php echo e($user['role']); ?></strong>
                <span>rôle actif</span>
            </div>
        </div>
    </div>

    <?php if (!$dbAvailable) { ?>
        <div class="app-alert app-alert-warning">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <div class="profile-layout">
        <div class="profile-column">
            <section class="profile-card">
                <h2>Mes informations</h2>

                <form action="actions/update_profile.php" method="POST" class="profile-form">
                    <div class="profile-form-grid">
                        <div>
                            <label for="profile-firstname">Prénom</label>
                            <input id="profile-firstname" type="text" name="firstname" value="<?php echo e($user['firstname']); ?>" required>
                        </div>

                        <div>
                            <label for="profile-lastname">Nom</label>
                            <input id="profile-lastname" type="text" name="lastname" value="<?php echo e($user['lastname']); ?>" required>
                        </div>

                        <div class="full-width">
                            <label for="profile-email">Email</label>
                            <input id="profile-email" type="email" name="email" value="<?php echo e($user['email']); ?>" required>
                        </div>
                    </div>

                    <button type="submit">Mettre à jour mon profil</button>
                </form>
            </section>

            <section class="profile-card">
                <h2>Changer mon mot de passe</h2>
                <p>
                    Pour sécuriser votre compte, renseignez votre mot de passe actuel
                    avant d’en choisir un nouveau.
                </p>

                <form action="actions/update_password.php" method="POST" class="profile-form">
                    <label for="current-password">Mot de passe actuel</label>
                    <input id="current-password" type="password" name="current_password" required>

                    <div class="profile-form-grid password-grid">
                        <div>
                            <label for="new-password">Nouveau mot de passe</label>
                            <input id="new-password" type="password" name="new_password" minlength="6" required>
                        </div>

                        <div>
                            <label for="new-password-confirmation">Confirmation</label>
                            <input id="new-password-confirmation" type="password" name="password_confirmation" minlength="6" required>
                        </div>
                    </div>

                    <button type="submit">Mettre à jour mon mot de passe</button>
                </form>
            </section>

            <section class="profile-card" id="mes-reservations">
                <div class="profile-card-title">
                    <div>
                        <span>Suivi voyage</span>
                        <h2>Mes réservations</h2>
                    </div>

                    <strong><?php echo count($reservations); ?></strong>
                </div>

                <?php if (empty($reservations)) { ?>
                    <p class="empty-state">Aucune réservation enregistrée pour le moment.</p>
                <?php } else { ?>
                    <div class="reservation-list">
                        <?php foreach ($reservations as $reservation) { ?>
                            <article class="reservation-card">
                                <div class="reservation-top">
                                    <div>
                                        <span><?php echo e($reservation['reference']); ?></span>
                                        <h3><?php echo e($reservation['stay_label']); ?></h3>
                                    </div>

                                    <strong class="reservation-status <?php echo $reservation['status'] === 'annulée' ? 'cancelled' : 'confirmed'; ?>">
                                        <?php echo e($reservation['status']); ?>
                                    </strong>
                                </div>

                                <p>
                                    Du <?php echo format_date_fr($reservation['start_date']); ?>
                                    au <?php echo format_date_fr($reservation['end_date']); ?>
                                    • <?php echo (int) $reservation['travelers']; ?> voyageur(s)
                                </p>

                                <ul class="reservation-items">
                                    <?php foreach ($reservation['items'] as $reservationItem) { ?>
                                        <li>
                                            <?php echo e($reservationItem['item_type']); ?> :
                                            <?php echo e($reservationItem['title']); ?>
                                            (<?php echo (int) $reservationItem['quantity']; ?> x <?php echo format_price($reservationItem['unit_price']); ?> €)
                                        </li>
                                    <?php } ?>
                                </ul>

                                <div class="reservation-bottom">
                                    <strong><?php echo format_price($reservation['total_amount']); ?> €</strong>

                                    <a href="index.php?page=recu_reservation&id=<?php echo (int) $reservation['id']; ?>">
                                        Récap PDF
                                    </a>

                                    <?php if ($reservation['status'] !== 'annulée') { ?>
                                        <a href="index.php?page=modifier_reservation&id=<?php echo (int) $reservation['id']; ?>">
                                            Modifier
                                        </a>

                                        <a href="actions/cancel_reservation.php?id=<?php echo (int) $reservation['id']; ?>">
                                            Annuler la réservation
                                        </a>
                                    <?php } ?>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>
        </div>

        <div class="profile-column">
            <section class="profile-card">
                <h2>Notifications</h2>

                <?php if (empty($notifications)) { ?>
                    <p class="empty-state">Aucune notification pour le moment.</p>
                <?php } else { ?>
                    <div class="notification-list">
                        <?php foreach ($notifications as $notification) { ?>
                            <article class="notification-card <?php echo (int) $notification['is_read'] === 1 ? 'is-read' : ''; ?>">
                                <div class="notification-top">
                                    <div>
                                        <span><?php echo format_date_fr($notification['created_at']); ?></span>
                                        <h3><?php echo e($notification['title']); ?></h3>
                                    </div>

                                    <?php if ((int) $notification['is_read'] === 0) { ?>
                                        <a href="actions/mark_notification_read.php?id=<?php echo (int) $notification['id']; ?>">
                                            Marquer comme lu
                                        </a>
                                    <?php } ?>
                                </div>

                                <p><?php echo e($notification['message']); ?></p>
                            </article>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>

            <section class="profile-card">
                <h2>Informations du compte</h2>
                <p><strong>Email :</strong> <?php echo e($user['email']); ?></p>
                <p><strong>Rôle :</strong> <?php echo e($user['role']); ?></p>
                <p><strong>Compte créé :</strong> <?php echo format_date_fr($user['created_at']); ?></p>
                <a class="profile-logout-link" href="actions/logout.php">Se déconnecter</a>
            </section>
        </div>
    </div>
</section>
