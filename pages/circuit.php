<?php
$stayContext = get_stay_context();
$depart = trim((string) ($_GET['depart'] ?? ($stayContext['depart'] ?? 'Paris')));
$destinationQuery = trim((string) ($_GET['destination'] ?? ($stayContext['destination_name'] ?? '')));
$dateDepart = trim((string) ($_GET['date_depart'] ?? ($stayContext['date_depart'] ?? '')));
$dateRetour = trim((string) ($_GET['date_retour'] ?? ($stayContext['date_retour'] ?? '')));
$voyageurs = max(1, (int) ($_GET['voyageurs'] ?? ($stayContext['voyageurs'] ?? 1)));
$budget = max(100, (int) ($_GET['budget'] ?? ($stayContext['budget'] ?? 500)));

$_SESSION['voyageurs'] = $voyageurs;

$datesValid = are_dates_valid($dateDepart, $dateRetour);
$destination = db_is_available() ? find_destination_by_query($destinationQuery) : null;

if (!$destination && db_is_available()) {
    $destinationsFallback = get_destinations(['sort' => 'score']);
    $destination = $destinationsFallback[0] ?? null;
}

remember_stay_context([
    'destination_id' => $destination['id'] ?? null,
    'destination_name' => $destination['name'] ?? $destinationQuery,
    'depart' => $depart,
    'date_depart' => $dateDepart,
    'date_retour' => $dateRetour,
    'voyageurs' => $voyageurs,
    'budget' => $budget,
    'dates_valid' => $datesValid,
]);

$transports = [];
$hebergements = [];
$activites = [];
$circuits = [];

if ($destination) {
    $allTransports = get_transports([
        'destination_id' => (int) $destination['id'],
        'sort' => 'price',
    ]);

    $transports = array_values(array_filter($allTransports, function ($transport) use ($depart) {
        if ($depart === '') {
            return true;
        }

        return strcasecmp($transport['departure_city'], $depart) === 0;
    }));

    if (empty($transports)) {
        $transports = $allTransports;
    }

    $hebergements = get_hebergements([
        'destination_id' => (int) $destination['id'],
        'sort' => 'price',
    ]);

    $activites = get_activites([
        'destination_id' => (int) $destination['id'],
        'sort' => 'price',
    ]);

    $circuits = build_itinerary_suggestions($destination, $transports, $hebergements, $activites, get_stay_context());
}

$itemsBudget = Panier::getItems();
$totalBudget = Panier::getTotal();
$totalParPersonne = $voyageurs > 0 ? $totalBudget / $voyageurs : $totalBudget;
?>

<section class="circuit-page">
    <div class="circuit-hero">
        <span class="circuit-badge">Circuit étudiant personnalisé</span>

        <h1>
            <?php if ($destination) { ?>
                Ton circuit vers <?php echo e($destination['name']); ?>
            <?php } else { ?>
                Compose ton séjour VoyageVista
            <?php } ?>
        </h1>

        <p>
            Départ de <strong><?php echo e($depart ?: 'ville à préciser'); ?></strong>
            <?php if ($dateDepart && $dateRetour) { ?>
                du <strong><?php echo format_date_fr($dateDepart); ?></strong>
                au <strong><?php echo format_date_fr($dateRetour); ?></strong>
            <?php } ?>
            • <?php echo $voyageurs; ?> voyageur(s)
            • Budget max : <?php echo $budget; ?> €
        </p>

        <div class="budget-status <?php echo $totalParPersonne <= $budget ? 'ok' : 'warning'; ?>">
            Budget actuel : <?php echo format_price($totalParPersonne); ?> € / personne
            <?php echo $totalParPersonne <= $budget ? 'Compatible avec votre budget' : 'Budget à ajuster'; ?>
        </div>
    </div>

    <?php if (!$datesValid) { ?>
        <div class="app-alert app-alert-error">
            La date de retour doit être postérieure à la date de départ.
        </div>
    <?php } ?>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <?php if (!$destination) { ?>
        <div class="empty-listing-card">
            <h2>Aucune destination disponible</h2>
            <p>Importez la base ou ajoutez des destinations depuis l’espace admin.</p>
        </div>
    <?php } else { ?>
        <div class="circuit-tabs">
            <button class="tab-btn active" data-tab="transport">Transports</button>
            <button class="tab-btn" data-tab="circuit">Circuit</button>
            <button class="tab-btn" data-tab="logement">Logements</button>
            <button class="tab-btn" data-tab="activites">Activités</button>
            <button class="tab-btn" data-tab="budget">Budget</button>
        </div>

        <div class="tab-content active" id="transport">
            <div class="tab-title-row">
                <div>
                    <h2>Options de transport</h2>
                    <p>Trajets adaptés à la destination, au point de départ et au budget étudiant.</p>
                </div>
            </div>

            <?php if (empty($transports)) { ?>
                <p class="empty-state">Aucun transport disponible pour cette destination.</p>
            <?php } else { ?>
                <div class="transport-grid">
                    <?php foreach ($transports as $transport) { ?>
                        <?php
                        $transportTotal = (float) $transport['price'] + (float) $transport['return_price'];
                        $transportDetails = $transport['departure_city'] . ' -> ' . $transport['arrival_city'] . ' / retour inclus';
                        ?>

                        <article class="transport-card">
                            <div class="transport-img" style="background-image:url('<?php echo e($transport['image_url']); ?>')">
                                <span class="transport-type"><?php echo e($transport['transport_type']); ?></span>

                                <form action="actions/add_to_favoris.php" method="POST" class="favorite-form">
                                    <input type="hidden" name="type" value="Transport">
                                    <input type="hidden" name="nom" value="<?php echo e($transport['transport_type']); ?>">
                                    <input type="hidden" name="details" value="<?php echo e($transportDetails); ?>">
                                    <input type="hidden" name="image" value="<?php echo e($transport['image_url']); ?>">
                                    <input type="hidden" name="source_type" value="transport">
                                    <input type="hidden" name="source_id" value="<?php echo (int) $transport['id']; ?>">
                                    <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#transport">
                                    <button type="submit" class="favorite-btn <?php echo is_favorite_item('Transport', $transport['id'], $transport['transport_type']) ? 'is-favorite' : ''; ?>">
                                        ♥
                                    </button>
                                </form>
                            </div>

                            <div class="transport-content">
                                <h3>Trajet aller-retour</h3>

                                <div class="transport-route">
                                    <p><strong>Aller :</strong> <?php echo e($transport['departure_city']); ?> -> <?php echo e($transport['arrival_city']); ?></p>
                                    <p><strong>Retour :</strong> inclus dans le forfait transport</p>
                                </div>

                                <div class="transport-info">
                                    <span>Durée : <?php echo e($transport['duration']); ?></span>
                                    <span><?php echo e($transport['details']); ?></span>
                                    <span>Places restantes : <?php echo (int) $transport['available_seats']; ?></span>
                                </div>

                                <div class="transport-bottom">
                                    <div>
                                        <small>Aller-retour</small>
                                        <strong><?php echo format_price($transportTotal); ?> €</strong>
                                        <small>
                                            Aller : <?php echo format_price($transport['price']); ?> €
                                            • Retour : <?php echo format_price($transport['return_price']); ?> €
                                        </small>
                                    </div>

                                    <?php if ((int) $transport['available_seats'] > 0) { ?>
                                        <form action="actions/add_to_cart.php" method="POST">
                                            <input type="hidden" name="type" value="Transport">
                                            <input type="hidden" name="nom" value="<?php echo e($transport['transport_type']); ?>">
                                            <input type="hidden" name="details" value="<?php echo e($transportDetails); ?>">
                                            <input type="hidden" name="source_type" value="transport">
                                            <input type="hidden" name="source_id" value="<?php echo (int) $transport['id']; ?>">
                                            <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#transport">
                                            <button type="submit">Ajouter au budget</button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="catalog-disabled-badge">Complet</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="tab-content" id="circuit">
            <div class="tab-title-row">
                <div>
                    <h2>Circuits conseillés</h2>
                    <p>Deux propositions simples pour composer un séjour démontrable en démo.</p>
                </div>
            </div>

            <div class="circuit-options-grid">
                <?php foreach ($circuits as $circuit) { ?>
                    <article class="route-card">
                        <div class="route-header">
                            <div>
                                <span>Circuit étudiant</span>
                                <h3><?php echo e($circuit['name']); ?></h3>
                            </div>

                            <strong><?php echo format_price($circuit['budget']); ?> € estimés</strong>
                        </div>

                        <div class="route-steps">
                            <?php foreach ($circuit['steps'] as $step) { ?>
                                <div class="route-step">
                                    <div class="route-dot"></div>

                                    <div class="route-step-content">
                                        <span><?php echo e($step['days']); ?></span>
                                        <h4><?php echo e($step['city']); ?></h4>
                                        <p><?php echo e($step['desc']); ?></p>

                                        <?php if (!empty($step['move'])) { ?>
                                            <div class="route-move">
                                                <small>Étape suivante</small>
                                                <strong><?php echo e($step['move']); ?></strong>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="route-actions">
                            <form action="actions/add_to_cart.php" method="POST">
                                <input type="hidden" name="type" value="Itinéraire">
                                <input type="hidden" name="nom" value="<?php echo e($circuit['name']); ?>">
                                <input type="hidden" name="details" value="<?php
                                    $details = [];
                                    foreach ($circuit['steps'] as $step) {
                                        $details[] = $step['days'] . ' : ' . $step['city'];
                                    }
                                    echo e(implode(' | ', $details));
                                ?>">
                                <input type="hidden" name="prix" value="0">
                                <input type="hidden" name="source_type" value="itineraire">
                                <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#circuit">
                                <button type="submit" class="route-btn">Choisir ce circuit</button>
                            </form>

                            <form action="actions/add_to_favoris.php" method="POST">
                                <input type="hidden" name="type" value="Itinéraire">
                                <input type="hidden" name="nom" value="<?php echo e($circuit['name']); ?>">
                                <input type="hidden" name="details" value="<?php
                                    $details = [];
                                    foreach ($circuit['steps'] as $step) {
                                        $details[] = $step['days'] . ' : ' . $step['city'];
                                    }
                                    echo e(implode(' | ', $details));
                                ?>">
                                <input type="hidden" name="source_type" value="itineraire">
                                <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#circuit">
                                <button type="submit" class="route-fav-btn <?php echo is_favorite_item('Itinéraire', 0, $circuit['name']) ? 'is-favorite' : ''; ?>">
                                    Ajouter aux favoris
                                </button>
                            </form>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>

        <div class="tab-content" id="logement">
            <div class="tab-title-row">
                <div>
                    <h2>Logements petit budget</h2>
                    <p>Des hébergements simples, étudiants et réservables depuis le circuit.</p>
                </div>
            </div>

            <?php if (empty($hebergements)) { ?>
                <p class="empty-state">Aucun hébergement disponible pour cette destination.</p>
            <?php } else { ?>
                <div class="housing-grid">
                    <?php foreach ($hebergements as $hebergement) { ?>
                        <article class="housing-card">
                            <div class="housing-img" style="background-image:url('<?php echo e($hebergement['image_url']); ?>')">
                                <span><?php echo e($hebergement['type']); ?></span>

                                <form action="actions/add_to_favoris.php" method="POST" class="favorite-form">
                                    <input type="hidden" name="type" value="Hébergement">
                                    <input type="hidden" name="nom" value="<?php echo e($hebergement['name']); ?>">
                                    <input type="hidden" name="details" value="<?php echo e($hebergement['city'] . ' - ' . $hebergement['type']); ?>">
                                    <input type="hidden" name="image" value="<?php echo e($hebergement['image_url']); ?>">
                                    <input type="hidden" name="source_type" value="hebergement">
                                    <input type="hidden" name="source_id" value="<?php echo (int) $hebergement['id']; ?>">
                                    <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#logement">
                                    <button type="submit" class="favorite-btn <?php echo is_favorite_item('Hébergement', $hebergement['id'], $hebergement['name']) ? 'is-favorite' : ''; ?>">
                                        ♥
                                    </button>
                                </form>
                            </div>

                            <div class="housing-content">
                                <div class="housing-top">
                                    <div>
                                        <small><?php echo e($hebergement['city']); ?></small>
                                        <h3><?php echo e($hebergement['name']); ?></h3>
                                    </div>

                                    <strong><?php echo e($hebergement['rating']); ?>/5</strong>
                                </div>

                                <p><?php echo e($hebergement['description']); ?></p>

                                <div class="listing-extra-meta">
                                    <span>Capacité : <?php echo (int) $hebergement['capacity']; ?></span>
                                    <span>Chambres restantes : <?php echo (int) $hebergement['available_rooms']; ?></span>
                                </div>

                                <div class="housing-bottom">
                                    <div>
                                        <small>À partir de</small>
                                        <strong><?php echo format_price($hebergement['price_per_night']); ?> € / nuit</strong>
                                    </div>

                                    <?php if ((int) $hebergement['available_rooms'] > 0) { ?>
                                        <form action="actions/add_to_cart.php" method="POST">
                                            <input type="hidden" name="type" value="Hébergement">
                                            <input type="hidden" name="nom" value="<?php echo e($hebergement['name']); ?>">
                                            <input type="hidden" name="details" value="<?php echo e($hebergement['city'] . ' - ' . $hebergement['type']); ?>">
                                            <input type="hidden" name="source_type" value="hebergement">
                                            <input type="hidden" name="source_id" value="<?php echo (int) $hebergement['id']; ?>">
                                            <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#logement">
                                            <button type="submit">Ajouter au budget</button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="catalog-disabled-badge">Indisponible</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="tab-content" id="activites">
            <div class="tab-title-row">
                <div>
                    <h2>Activités étudiantes</h2>
                    <p>Ajoutez des expériences cohérentes avec votre destination et vos disponibilités.</p>
                </div>
            </div>

            <?php if (empty($activites)) { ?>
                <p class="empty-state">Aucune activité disponible pour cette destination.</p>
            <?php } else { ?>
                <div class="activity-grid">
                    <?php foreach ($activites as $activite) { ?>
                        <article class="activity-card">
                            <div class="activity-img" style="background-image:url('<?php echo e($activite['image_url']); ?>')">
                                <span><?php echo e($activite['type']); ?></span>

                                <form action="actions/add_to_favoris.php" method="POST" class="favorite-form">
                                    <input type="hidden" name="type" value="Activité">
                                    <input type="hidden" name="nom" value="<?php echo e($activite['name']); ?>">
                                    <input type="hidden" name="details" value="<?php echo e($activite['city'] . ' - ' . $activite['type']); ?>">
                                    <input type="hidden" name="image" value="<?php echo e($activite['image_url']); ?>">
                                    <input type="hidden" name="source_type" value="activite">
                                    <input type="hidden" name="source_id" value="<?php echo (int) $activite['id']; ?>">
                                    <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#activites">
                                    <button type="submit" class="favorite-btn <?php echo is_favorite_item('Activité', $activite['id'], $activite['name']) ? 'is-favorite' : ''; ?>">
                                        ♥
                                    </button>
                                </form>
                            </div>

                            <div class="activity-content">
                                <small><?php echo e($activite['city']); ?></small>
                                <h3><?php echo e($activite['name']); ?></h3>
                                <p><?php echo e($activite['description']); ?></p>

                                <div class="listing-extra-meta">
                                    <span>Niveau : <?php echo e($activite['level']); ?></span>
                                    <span>Places restantes : <?php echo (int) $activite['available_slots']; ?></span>
                                </div>

                                <div class="activity-bottom">
                                    <strong><?php echo format_price($activite['price']); ?> €</strong>

                                    <?php if ((int) $activite['available_slots'] > 0) { ?>
                                        <form action="actions/add_to_cart.php" method="POST">
                                            <input type="hidden" name="type" value="Activité">
                                            <input type="hidden" name="nom" value="<?php echo e($activite['name']); ?>">
                                            <input type="hidden" name="details" value="<?php echo e($activite['city'] . ' - ' . $activite['type']); ?>">
                                            <input type="hidden" name="source_type" value="activite">
                                            <input type="hidden" name="source_id" value="<?php echo (int) $activite['id']; ?>">
                                            <input type="hidden" name="redirect" value="<?php echo e($_SERVER['REQUEST_URI']); ?>#activites">
                                            <button type="submit">Ajouter au budget</button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="catalog-disabled-badge">Complet</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="tab-content" id="budget">
            <div class="tab-title-row">
                <div>
                    <h2>Budget planner</h2>
                    <p>Le coût total du séjour se met à jour automatiquement avec les éléments ajoutés.</p>
                </div>
            </div>

            <div class="budget-planner">
                <?php if (empty($itemsBudget)) { ?>
                    <p>Aucun élément ajouté pour le moment.</p>
                <?php } else { ?>
                    <?php foreach ($itemsBudget as $item) { ?>
                        <?php
                        $quantity = max(1, (int) ($item['quantity'] ?? 1));
                        $itemTotal = (float) ($item['unit_price'] ?? $item['prix'] ?? 0) * $quantity;
                        ?>
                        <p>
                            <span><?php echo e($item['type']); ?> - <?php echo e($item['nom']); ?> (x<?php echo $quantity; ?>)</span>
                            <strong><?php echo format_price($itemTotal); ?> €</strong>
                        </p>
                    <?php } ?>

                    <hr>

                    <p class="budget-final">
                        <span>Total</span>
                        <strong><?php echo format_price($totalBudget); ?> €</strong>
                    </p>

                    <p class="budget-final">
                        <span>Total / personne</span>
                        <strong><?php echo format_price($totalParPersonne); ?> €</strong>
                    </p>

                    <a class="budget-cart-link" href="index.php?page=panier">
                        Voir le panier complet
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</section>

<script>
const buttons = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

function openTab(tabName) {
    buttons.forEach((button) => button.classList.remove('active'));
    contents.forEach((content) => content.classList.remove('active'));

    const activeButton = document.querySelector('.tab-btn[data-tab="' + tabName + '"]');
    const activeContent = document.getElementById(tabName);

    if (activeButton && activeContent) {
        activeButton.classList.add('active');
        activeContent.classList.add('active');
    }
}

buttons.forEach((button) => {
    button.addEventListener('click', () => {
        openTab(button.dataset.tab);
    });
});

if (window.location.hash) {
    const tabFromHash = window.location.hash.replace('#', '');
    openTab(tabFromHash);
}
</script>
