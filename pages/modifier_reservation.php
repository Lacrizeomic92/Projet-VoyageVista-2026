<?php
$reservationId = (int) ($_GET['id'] ?? 0);
$reservation = null;
$editableItemCount = 0;

if (db_is_available() && $reservationId > 0) {
    $reservation = get_user_reservation($reservationId, (int) current_user()['id']);
}
?>

<section class="reservation-edit-page">
    <div class="reservation-edit-hero">
        <span>Modification de réservation</span>
        <h1>Modifier votre séjour</h1>
        <p>
            Remplacez un transport, une activité ou un hébergement de votre réservation confirmée.
            Le total est recalculé automatiquement après validation. Un changement vers un avion
            ajoute une ligne de frais de 10€.
        </p>
    </div>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } elseif (!$reservation) { ?>
        <div class="reservation-edit-card">
            <h2>Réservation introuvable</h2>
            <p>Cette réservation n’existe pas ou ne vous appartient pas.</p>
            <a href="index.php?page=profil">Retour au profil</a>
        </div>
    <?php } else { ?>
        <div class="reservation-edit-card">
            <div class="reservation-edit-top">
                <div>
                    <span><?php echo e($reservation['reference']); ?></span>
                    <h2><?php echo e($reservation['stay_label']); ?></h2>
                    <p>
                        Du <?php echo format_date_fr($reservation['start_date']); ?>
                        au <?php echo format_date_fr($reservation['end_date']); ?>
                        • <?php echo (int) $reservation['travelers']; ?> voyageur(s)
                    </p>
                </div>

                <strong class="reservation-status <?php echo $reservation['status'] === 'annulée' ? 'cancelled' : 'confirmed'; ?>">
                    <?php echo e($reservation['status']); ?>
                </strong>
            </div>

            <?php if ($reservation['status'] === 'annulée') { ?>
                <div class="app-alert app-alert-warning">
                    Une réservation annulée ne peut plus être modifiée.
                </div>
            <?php } ?>

            <section class="reservation-history-panel">
                <div class="reservation-history-title">
                    <span>Historique</span>
                    <h3>Suivi des changements</h3>
                </div>

                <?php if (empty($reservation['history'])) { ?>
                    <p class="reservation-edit-note">
                        Aucun changement enregistré pour cette réservation.
                    </p>
                <?php } else { ?>
                    <ul class="reservation-history-list">
                        <?php foreach ($reservation['history'] as $historyItem) { ?>
                            <li>
                                <span><?php echo format_date_fr($historyItem['created_at']); ?></span>
                                <strong><?php echo e($historyItem['action_label']); ?></strong>
                                <p><?php echo e($historyItem['details']); ?></p>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </section>

            <div class="reservation-edit-items">
                <?php foreach ($reservation['items'] as $item) { ?>
                    <?php
                    $isEditableActivity = strtolower((string) ($item['source_type'] ?? '')) === 'activite'
                        && $reservation['status'] !== 'annulée';
                    $isEditableHebergement = strtolower((string) ($item['source_type'] ?? '')) === 'hebergement'
                        && $reservation['status'] !== 'annulée';
                    $isEditableTransport = strtolower((string) ($item['source_type'] ?? '')) === 'transport'
                        && $reservation['status'] !== 'annulée';
                    $currentActivity = $isEditableActivity ? get_activite_by_id((int) $item['source_id']) : null;
                    $currentHebergement = $isEditableHebergement ? get_hebergement_by_id((int) $item['source_id']) : null;
                    $currentTransport = $isEditableTransport ? get_transport_by_id((int) $item['source_id']) : null;
                    $activityFilters = [
                        'sort' => 'price',
                        'available_only' => true,
                    ];
                    $hebergementFilters = [
                        'sort' => 'price',
                        'available_only' => true,
                    ];
                    $transportFilters = [
                        'sort' => 'price',
                        'available_only' => true,
                    ];

                    if ($currentActivity && !empty($currentActivity['destination_id'])) {
                        $activityFilters['destination_id'] = (int) $currentActivity['destination_id'];
                    }

                    if ($currentHebergement && !empty($currentHebergement['destination_id'])) {
                        $hebergementFilters['destination_id'] = (int) $currentHebergement['destination_id'];
                    }

                    if ($currentTransport && !empty($currentTransport['destination_id'])) {
                        $transportFilters['destination_id'] = (int) $currentTransport['destination_id'];
                    }

                    $availableActivities = $isEditableActivity ? get_activites($activityFilters) : [];
                    $availableHebergements = $isEditableHebergement ? get_hebergements($hebergementFilters) : [];
                    $availableTransports = $isEditableTransport ? get_transports($transportFilters) : [];

                    if ($currentActivity) {
                        $hasCurrentActivity = false;

                        foreach ($availableActivities as $activityOption) {
                            if ((int) $activityOption['id'] === (int) $currentActivity['id']) {
                                $hasCurrentActivity = true;
                                break;
                            }
                        }

                        if (!$hasCurrentActivity) {
                            array_unshift($availableActivities, $currentActivity);
                        }
                    }

                    if ($currentHebergement) {
                        $hasCurrentHebergement = false;

                        foreach ($availableHebergements as $hebergementOption) {
                            if ((int) $hebergementOption['id'] === (int) $currentHebergement['id']) {
                                $hasCurrentHebergement = true;
                                break;
                            }
                        }

                        if (!$hasCurrentHebergement) {
                            array_unshift($availableHebergements, $currentHebergement);
                        }
                    }

                    if ($currentTransport) {
                        $hasCurrentTransport = false;

                        foreach ($availableTransports as $transportOption) {
                            if ((int) $transportOption['id'] === (int) $currentTransport['id']) {
                                $hasCurrentTransport = true;
                                break;
                            }
                        }

                        if (!$hasCurrentTransport) {
                            array_unshift($availableTransports, $currentTransport);
                        }
                    }

                    if ($isEditableActivity || $isEditableHebergement || $isEditableTransport) {
                        $editableItemCount++;
                    }
                    ?>

                    <article class="reservation-edit-item">
                        <div>
                            <span><?php echo e($item['item_type']); ?></span>
                            <h3><?php echo e($item['title']); ?></h3>
                            <p><?php echo e($item['details']); ?></p>
                            <strong>
                                <?php echo (int) $item['quantity']; ?> x
                                <?php echo format_price($item['unit_price']); ?> €
                            </strong>
                        </div>

                        <?php if ($isEditableActivity) { ?>
                            <div class="reservation-edit-transport-panel">
                                <form action="actions/update_reservation_activity.php" method="POST" class="reservation-edit-form">
                                    <input type="hidden" name="reservation_id" value="<?php echo (int) $reservation['id']; ?>">
                                    <input type="hidden" name="reservation_item_id" value="<?php echo (int) $item['id']; ?>">

                                    <label for="activity-<?php echo (int) $item['id']; ?>">Nouvelle activité</label>
                                    <select id="activity-<?php echo (int) $item['id']; ?>" name="activite_id" required>
                                        <?php foreach ($availableActivities as $activity) { ?>
                                            <option
                                                value="<?php echo (int) $activity['id']; ?>"
                                                <?php echo (int) $activity['id'] === (int) $item['source_id'] ? 'selected' : ''; ?>
                                            >
                                                <?php echo e($activity['name']); ?>
                                                • <?php echo e($activity['city']); ?>
                                                • <?php echo format_price($activity['price']); ?> €
                                                • <?php echo (int) $activity['available_slots']; ?> place(s)
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <button type="submit">Remplacer l’activité</button>
                                </form>

                                <form
                                    action="actions/cancel_reservation_activity.php"
                                    method="POST"
                                    class="reservation-edit-cancel-form"
                                    onsubmit="return confirm('Annuler uniquement cette activité et remettre les places disponibles ?');"
                                >
                                    <input type="hidden" name="reservation_id" value="<?php echo (int) $reservation['id']; ?>">
                                    <input type="hidden" name="reservation_item_id" value="<?php echo (int) $item['id']; ?>">
                                    <button type="submit">Annuler cette activité</button>
                                </form>
                            </div>
                        <?php } elseif ($isEditableHebergement) { ?>
                            <div class="reservation-edit-transport-panel">
                                <form action="actions/update_reservation_hebergement.php" method="POST" class="reservation-edit-form">
                                    <input type="hidden" name="reservation_id" value="<?php echo (int) $reservation['id']; ?>">
                                    <input type="hidden" name="reservation_item_id" value="<?php echo (int) $item['id']; ?>">

                                    <label for="housing-<?php echo (int) $item['id']; ?>">Nouvel hébergement</label>
                                    <select id="housing-<?php echo (int) $item['id']; ?>" name="hebergement_id" required>
                                        <?php foreach ($availableHebergements as $hebergement) { ?>
                                            <option
                                                value="<?php echo (int) $hebergement['id']; ?>"
                                                <?php echo (int) $hebergement['id'] === (int) $item['source_id'] ? 'selected' : ''; ?>
                                            >
                                                <?php echo e($hebergement['name']); ?>
                                                • <?php echo e($hebergement['city']); ?>
                                                • <?php echo e($hebergement['type']); ?>
                                                • <?php echo format_price($hebergement['price_per_night']); ?> €/nuit
                                                • <?php echo (int) $hebergement['available_rooms']; ?> chambre(s)
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <button type="submit">Remplacer l’hébergement</button>
                                </form>

                                <form
                                    action="actions/cancel_reservation_hebergement.php"
                                    method="POST"
                                    class="reservation-edit-cancel-form"
                                    onsubmit="return confirm('Annuler uniquement cet hébergement et remettre les chambres disponibles ?');"
                                >
                                    <input type="hidden" name="reservation_id" value="<?php echo (int) $reservation['id']; ?>">
                                    <input type="hidden" name="reservation_item_id" value="<?php echo (int) $item['id']; ?>">
                                    <button type="submit">Annuler cet hébergement</button>
                                </form>
                            </div>
                        <?php } elseif ($isEditableTransport) { ?>
                            <div class="reservation-edit-transport-panel">
                                <form action="actions/update_reservation_transport.php" method="POST" class="reservation-edit-form">
                                    <input type="hidden" name="reservation_id" value="<?php echo (int) $reservation['id']; ?>">
                                    <input type="hidden" name="reservation_item_id" value="<?php echo (int) $item['id']; ?>">

                                    <label for="transport-<?php echo (int) $item['id']; ?>">Nouveau transport</label>
                                    <select id="transport-<?php echo (int) $item['id']; ?>" name="transport_id" required>
                                        <?php foreach ($availableTransports as $transport) { ?>
                                            <?php $transportTotal = (float) $transport['price'] + (float) $transport['return_price']; ?>
                                            <option
                                                value="<?php echo (int) $transport['id']; ?>"
                                                <?php echo (int) $transport['id'] === (int) $item['source_id'] ? 'selected' : ''; ?>
                                            >
                                                <?php echo e($transport['transport_type']); ?>
                                                • <?php echo e($transport['departure_city']); ?> → <?php echo e($transport['arrival_city']); ?>
                                                • <?php echo format_price($transportTotal); ?> €
                                                • <?php echo (int) $transport['available_seats']; ?> place(s)
                                                <?php if (str_contains(strtolower((string) $transport['transport_type']), 'avion')) { ?>
                                                    • +10€ frais avion
                                                <?php } ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <p class="reservation-edit-fee-note">
                                        Si le nouveau transport est un avion, une ligne de frais de modification de 10€ sera ajoutée séparément.
                                    </p>

                                    <button type="submit">Remplacer le transport</button>
                                </form>

                                <form
                                    action="actions/cancel_reservation_transport.php"
                                    method="POST"
                                    class="reservation-edit-cancel-form"
                                    onsubmit="return confirm('Annuler uniquement ce transport et remettre les places disponibles ?');"
                                >
                                    <input type="hidden" name="reservation_id" value="<?php echo (int) $reservation['id']; ?>">
                                    <input type="hidden" name="reservation_item_id" value="<?php echo (int) $item['id']; ?>">
                                    <button type="submit">Annuler ce transport</button>
                                </form>
                            </div>
                        <?php } else { ?>
                            <p class="reservation-edit-note">
                                Cet élément n’est pas modifiable ici.
                            </p>
                        <?php } ?>
                    </article>
                <?php } ?>
            </div>

            <?php if ($editableItemCount === 0 && $reservation['status'] !== 'annulée') { ?>
                <div class="app-alert app-alert-warning">
                    Cette réservation ne contient pas de transport, d’activité ou d’hébergement modifiable.
                </div>
            <?php } ?>

            <div class="reservation-edit-bottom">
                <strong>Total actuel : <?php echo format_price($reservation['total_amount']); ?> €</strong>
                <a href="index.php?page=profil">Retour au profil</a>
            </div>
        </div>
    <?php } ?>
</section>
