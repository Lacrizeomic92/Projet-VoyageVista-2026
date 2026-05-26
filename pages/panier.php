<?php
$items = Panier::getItems();
$total = Panier::getTotal();
$stayContext = get_stay_context();
$voyageurs = max(1, (int) ($stayContext['voyageurs'] ?? ($_SESSION['voyageurs'] ?? 1)));
$totalParPersonne = $voyageurs > 0 ? $total / $voyageurs : $total;
$datesValid = $stayContext['dates_valid'] ?? true;
?>

<section class="cart-page">
    <div class="cart-header">
        <span>Budget planner</span>
        <h1>Votre panier de voyage</h1>
        <p>
            Retrouvez ici les transports, logements, activités et itinéraires
            ajoutés à votre séjour. Le paiement reste simulé pour la démo.
        </p>
    </div>

    <?php if (!$datesValid) { ?>
        <div class="app-alert app-alert-error">
            Les dates choisies sont incohérentes. Corrigez-les depuis le circuit avant validation.
        </div>
    <?php } ?>

    <?php if (empty($items)) { ?>
        <div class="cart-empty">
            <h2>Votre panier est vide</h2>
            <p>Ajoutez un transport, un logement ou une activité depuis votre circuit.</p>
            <a href="index.php?page=circuit">Créer un circuit</a>
        </div>
    <?php } else { ?>
        <div class="cart-layout">
            <div class="cart-items">
                <?php foreach ($items as $item) { ?>
                    <?php
                    $quantity = max(1, (int) ($item['quantity'] ?? 1));
                    $unitPrice = (float) ($item['unit_price'] ?? $item['prix'] ?? 0);
                    $lineTotal = $unitPrice * $quantity;
                    ?>

                    <article class="cart-item">
                        <div>
                            <span class="cart-item-type"><?php echo e($item['type']); ?></span>

                            <h3><?php echo e($item['nom']); ?></h3>

                            <p><?php echo e($item['details']); ?></p>

                            <small class="cart-unit-price">
                                Prix unitaire : <?php echo format_price($unitPrice); ?> €
                            </small>
                        </div>

                        <div class="cart-item-price">
                            <strong><?php echo format_price($lineTotal); ?> €</strong>

                            <form action="actions/update_cart.php" method="POST" class="cart-update-form">
                                <input type="hidden" name="id" value="<?php echo e($item['id']); ?>">
                                <label for="qty-<?php echo e($item['id']); ?>">Quantité</label>
                                <input
                                    id="qty-<?php echo e($item['id']); ?>"
                                    type="number"
                                    name="quantity"
                                    min="1"
                                    max="9"
                                    value="<?php echo $quantity; ?>"
                                >
                                <button type="submit">Mettre à jour</button>
                            </form>

                            <a href="actions/remove_from_cart.php?id=<?php echo urlencode($item['id']); ?>&redirect=<?php echo urlencode('index.php?page=panier'); ?>">
                                Supprimer
                            </a>
                        </div>
                    </article>
                <?php } ?>
            </div>

            <aside class="cart-summary">
                <h2>Résumé budget</h2>

                <div class="summary-line">
                    <span>Total voyage</span>
                    <strong><?php echo format_price($total); ?> €</strong>
                </div>

                <div class="summary-line">
                    <span>Voyageurs</span>
                    <strong><?php echo $voyageurs; ?></strong>
                </div>

                <div class="summary-line total">
                    <span>Total / personne</span>
                    <strong><?php echo format_price($totalParPersonne); ?> €</strong>
                </div>

                <form action="actions/checkout.php" method="POST" class="checkout-form">
                    <h3>Paiement simulé</h3>
                    <p class="payment-note">
                        Aucune transaction réelle n’est effectuée. Ce bloc sert à démontrer le parcours de réservation.
                    </p>

                    <label for="card-name">Nom sur la carte</label>
                    <input id="card-name" type="text" name="card_name" placeholder="Alexis Voyageur" required>

                    <label for="card-number">Numéro de carte fictif</label>
                    <input id="card-number" type="text" name="card_number" placeholder="4242 4242 4242 4242" required>

                    <div class="checkout-inline-fields">
                        <div>
                            <label for="card-expiry">Date</label>
                            <input id="card-expiry" type="text" name="card_expiry" placeholder="12/28" required>
                        </div>

                        <div>
                            <label for="card-cvv">CVV</label>
                            <input id="card-cvv" type="text" name="card_cvv" placeholder="123" required>
                        </div>
                    </div>

                    <?php if (!is_logged_in()) { ?>
                        <div class="app-alert app-alert-warning">
                            Connectez-vous avant de valider votre réservation simulée.
                        </div>
                        <a class="checkout-login-link" href="index.php?page=login&redirect=<?php echo urlencode('index.php?page=panier'); ?>">
                            Me connecter
                        </a>
                    <?php } else { ?>
                        <button type="submit" <?php echo !$datesValid ? 'disabled' : ''; ?>>
                            Valider la réservation
                        </button>
                    <?php } ?>
                </form>
            </aside>
        </div>
    <?php } ?>
</section>
