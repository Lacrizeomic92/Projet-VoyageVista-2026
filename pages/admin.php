<?php
$stats = db_is_available() ? get_dashboard_stats() : ['users' => 0, 'reservations' => 0, 'destinations' => 0];
$recentReservations = db_is_available() ? get_recent_reservations(5) : [];
$users = db_is_available() ? list_users() : [];
$destinations = db_is_available() ? get_destinations(['sort' => 'nom']) : [];
?>

<section class="admin-page">
    <div class="admin-hero">
        <div>
            <span>Administration</span>
            <h1>Tableau de bord VoyageVista</h1>
            <p>
                Espace réservé au rôle admin pour suivre l’activité, gérer les utilisateurs
                et piloter rapidement le catalogue de destinations.
            </p>
        </div>
    </div>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <div class="admin-stats">
        <article class="admin-stat-card">
            <strong><?php echo (int) $stats['users']; ?></strong>
            <span>utilisateurs</span>
        </article>

        <article class="admin-stat-card">
            <strong><?php echo (int) $stats['reservations']; ?></strong>
            <span>réservations</span>
        </article>

        <article class="admin-stat-card">
            <strong><?php echo (int) $stats['destinations']; ?></strong>
            <span>destinations</span>
        </article>
    </div>

    <div class="admin-layout">
        <div class="admin-column">
            <section class="admin-card">
                <h2>Dernières réservations</h2>

                <?php if (empty($recentReservations)) { ?>
                    <p class="empty-state">Aucune réservation récente à afficher.</p>
                <?php } else { ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Utilisateur</th>
                                    <th>Séjour</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentReservations as $reservation) { ?>
                                    <tr>
                                        <td><?php echo e($reservation['reference']); ?></td>
                                        <td><?php echo e(trim($reservation['firstname'] . ' ' . $reservation['lastname'])); ?></td>
                                        <td><?php echo e($reservation['stay_label']); ?></td>
                                        <td><?php echo format_price($reservation['total_amount']); ?> €</td>
                                        <td><?php echo e($reservation['status']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </section>

            <section class="admin-card">
                <h2>Utilisateurs</h2>

                <?php if (empty($users)) { ?>
                    <p class="empty-state">Aucun utilisateur trouvé.</p>
                <?php } else { ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $listedUser) { ?>
                                    <tr>
                                        <td><?php echo e($listedUser['firstname'] . ' ' . $listedUser['lastname']); ?></td>
                                        <td><?php echo e($listedUser['email']); ?></td>
                                        <td><?php echo e($listedUser['role']); ?></td>
                                        <td>
                                            <?php if ($listedUser['role'] === 'admin') { ?>
                                                <a href="actions/toggle_admin.php?id=<?php echo (int) $listedUser['id']; ?>&role=voyageur">
                                                    Retirer admin
                                                </a>
                                            <?php } else { ?>
                                                <a href="actions/toggle_admin.php?id=<?php echo (int) $listedUser['id']; ?>&role=admin">
                                                    Passer admin
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </section>
        </div>

        <div class="admin-column">
            <section class="admin-card">
                <h2>Ajouter une destination</h2>

                <form action="actions/save_destination.php" method="POST" class="admin-form">
                    <div class="admin-form-grid">
                        <div>
                            <label for="destination-name">Nom</label>
                            <input id="destination-name" type="text" name="name" required>
                        </div>

                        <div>
                            <label for="destination-country">Pays</label>
                            <input id="destination-country" type="text" name="country" required>
                        </div>

                        <div>
                            <label for="destination-category">Catégorie</label>
                            <input id="destination-category" type="text" name="category" placeholder="plage culture ville" required>
                        </div>

                        <div>
                            <label for="destination-tag">Tag</label>
                            <input id="destination-tag" type="text" name="student_tag" placeholder="Bon plan">
                        </div>

                        <div>
                            <label for="destination-duration">Durée</label>
                            <select id="destination-duration" name="duration_type">
                                <option value="court">Court séjour</option>
                                <option value="long">Long séjour</option>
                            </select>
                        </div>

                        <div>
                            <label for="destination-budget-level">Budget</label>
                            <select id="destination-budget-level" name="budget_level">
                                <option value="economique">Économique</option>
                                <option value="moyen">Moyen</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>

                        <div>
                            <label for="destination-base-price">Prix de base</label>
                            <input id="destination-base-price" type="number" step="0.01" name="base_price" value="199">
                        </div>

                        <div>
                            <label for="destination-daily-budget">Budget / jour</label>
                            <input id="destination-daily-budget" type="number" step="0.01" name="daily_budget" value="45">
                        </div>

                        <div>
                            <label for="destination-score">Score étudiant</label>
                            <input id="destination-score" type="number" step="0.1" name="student_score" value="8.5">
                        </div>

                        <div>
                            <label for="destination-audience">Audience</label>
                            <input id="destination-audience" type="text" name="audience" value="etudiant jeunesse">
                        </div>

                        <div class="full-width">
                            <label for="destination-image">Image URL</label>
                            <input id="destination-image" type="url" name="image_url" required>
                        </div>

                        <div class="full-width">
                            <label for="destination-description">Description</label>
                            <textarea id="destination-description" name="description" rows="4" required></textarea>
                        </div>
                    </div>

                    <button type="submit">Ajouter la destination</button>
                </form>
            </section>

            <section class="admin-card">
                <h2>Catalogue existant</h2>

                <?php if (empty($destinations)) { ?>
                    <p class="empty-state">Aucune destination enregistrée.</p>
                <?php } else { ?>
                    <div class="admin-destination-list">
                        <?php foreach ($destinations as $destination) { ?>
                            <article class="admin-destination-row">
                                <div>
                                    <strong><?php echo e($destination['name']); ?></strong>
                                    <span><?php echo e($destination['country']); ?> • <?php echo e($destination['budget_level']); ?></span>
                                </div>

                                <a href="actions/delete_destination.php?id=<?php echo (int) $destination['id']; ?>">
                                    Supprimer
                                </a>
                            </article>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</section>
