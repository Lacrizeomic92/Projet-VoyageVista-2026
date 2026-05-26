<?php
require_once 'classes/Panier.php';

Panier::init();

$items = Panier::getItems();
$total = Panier::getTotal();

$voyageurs = $_SESSION['voyageurs'] ?? 1;

if ($voyageurs <= 0) {
    $voyageurs = 1;
}

$totalParPersonne = $total / $voyageurs;
?>

<section class="cart-page">

    <div class="cart-header">
        <span>Budget planner</span>
        <h1>Votre panier de voyage</h1>
        <p>
            Retrouvez ici les transports, logements et activités ajoutés à votre circuit étudiant.
        </p>
    </div>

    <?php if (empty($items)) { ?>

        <div class="cart-empty">
            <h2>Votre panier est vide</h2>
            <p>Ajoutez un transport, un logement ou une activité depuis votre circuit.</p>
            <a href="index.php">Créer un circuit</a>
        </div>

    <?php } else { ?>

        <div class="cart-layout">

            <div class="cart-items">

                <?php foreach ($items as $item) { ?>

                    <article class="cart-item">

                        <div>
                            <span class="cart-item-type">
                                <?php echo htmlspecialchars($item['type']); ?>
                            </span>

                            <h3>
                                <?php echo htmlspecialchars($item['nom']); ?>
                            </h3>

                            <p>
                                <?php echo htmlspecialchars($item['details']); ?>
                            </p>
                        </div>

                        <div class="cart-item-price">
                            <strong><?php echo htmlspecialchars($item['prix']); ?>€</strong>

                            <a href="actions/remove_from_cart.php?id=<?php echo urlencode($item['id']); ?>">
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
                    <strong><?php echo number_format($total, 2); ?>€</strong>
                </div>

                <div class="summary-line">
                    <span>Voyageurs</span>
                    <strong><?php echo $voyageurs; ?></strong>
                </div>

                <div class="summary-line total">
                    <span>Total / personne</span>
                    <strong><?php echo number_format($totalParPersonne, 2); ?>€</strong>
                </div>

                <button>
                    Valider la réservation simulée
                </button>

            </aside>

        </div>

    <?php } ?>

</section>