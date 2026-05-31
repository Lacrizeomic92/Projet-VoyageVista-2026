<?php
$reservationId = (int) ($_GET['id'] ?? 0);
$reservation = null;

if (db_is_available() && $reservationId > 0) {
    $reservation = get_user_reservation($reservationId, (int) current_user()['id']);
}
?>

<section class="receipt-page">
    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } elseif (!$reservation) { ?>
        <div class="receipt-card">
            <h1>Récapitulatif introuvable</h1>
            <p>Cette réservation n’existe pas ou ne vous appartient pas.</p>
            <a href="index.php?page=profil">Retour au profil</a>
        </div>
    <?php } else { ?>
        <div class="receipt-actions no-print">
            <a href="index.php?page=profil#mes-reservations">Retour aux réservations</a>
            <button type="button" onclick="window.print()">Imprimer / télécharger PDF</button>
        </div>

        <article class="receipt-card">
            <div class="receipt-header">
                <div>
                    <span>Récapitulatif de réservation</span>
                    <h1>VoyageVista</h1>
                    <p>Référence <?php echo e($reservation['reference']); ?></p>
                </div>

                <strong class="reservation-status <?php echo $reservation['status'] === 'annulée' ? 'cancelled' : 'confirmed'; ?>">
                    <?php echo e($reservation['status']); ?>
                </strong>
            </div>

            <div class="receipt-grid">
                <div>
                    <span>Séjour</span>
                    <strong><?php echo e($reservation['stay_label']); ?></strong>
                </div>
                <div>
                    <span>Dates</span>
                    <strong>
                        <?php echo format_date_fr($reservation['start_date']); ?>
                        au <?php echo format_date_fr($reservation['end_date']); ?>
                    </strong>
                </div>
                <div>
                    <span>Voyageurs</span>
                    <strong><?php echo (int) $reservation['travelers']; ?></strong>
                </div>
                <div>
                    <span>Paiement</span>
                    <strong>Carte **** <?php echo e($reservation['payment_card_last4']); ?></strong>
                </div>
            </div>

            <?php if (!empty($reservation['travelers_list'])) { ?>
                <section class="receipt-section">
                    <h2>Voyageurs renseignés</h2>
                    <ul class="receipt-list">
                        <?php foreach ($reservation['travelers_list'] as $traveler) { ?>
                            <li>
                                <span><?php echo e($traveler['firstname'] . ' ' . $traveler['lastname']); ?></span>
                                <?php if (!empty($traveler['email'])) { ?>
                                    <strong><?php echo e($traveler['email']); ?></strong>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </section>
            <?php } ?>

            <section class="receipt-section">
                <h2>Détail du séjour</h2>
                <ul class="receipt-list">
                    <?php foreach ($reservation['items'] as $item) { ?>
                        <li>
                            <span>
                                <?php echo e($item['item_type']); ?> :
                                <?php echo e($item['title']); ?>
                                <small><?php echo e($item['details']); ?></small>
                            </span>
                            <strong>
                                <?php echo (int) $item['quantity']; ?> x
                                <?php echo format_price($item['unit_price']); ?> €
                            </strong>
                        </li>
                    <?php } ?>
                </ul>
            </section>

            <div class="receipt-total">
                <span>Total payé</span>
                <strong><?php echo format_price($reservation['total_amount']); ?> €</strong>
            </div>

            <?php if (!empty($reservation['history'])) { ?>
                <section class="receipt-section">
                    <h2>Historique de la réservation</h2>
                    <ul class="receipt-timeline">
                        <?php foreach ($reservation['history'] as $historyItem) { ?>
                            <li>
                                <span><?php echo format_date_fr($historyItem['created_at']); ?></span>
                                <strong><?php echo e($historyItem['action_label']); ?></strong>
                                <p><?php echo e($historyItem['details']); ?></p>
                            </li>
                        <?php } ?>
                    </ul>
                </section>
            <?php } ?>
        </article>
    <?php } ?>
</section>
