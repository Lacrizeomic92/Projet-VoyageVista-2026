<?php
$search = trim((string) ($_GET['q'] ?? ''));
$transportType = trim((string) ($_GET['type'] ?? 'all'));
$departureCity = trim((string) ($_GET['departure'] ?? 'all'));
$availableOnly = isset($_GET['available_only']) ? 1 : 0;
$sort = trim((string) ($_GET['sort'] ?? 'price'));
$dateDepart = trim((string) ($_GET['date_depart'] ?? ''));
$dateRetour = trim((string) ($_GET['date_retour'] ?? ''));
$datesValid = are_dates_valid($dateDepart, $dateRetour);
$datesComplete = $dateDepart !== '' && $dateRetour !== '';

$allTransports = db_is_available() ? get_transports(['sort' => 'type']) : [];
$transports = db_is_available()
    ? get_transports([
        'search' => $search,
        'transport_type' => $transportType,
        'departure_city' => $departureCity,
        'available_only' => $availableOnly,
        'sort' => $sort,
    ])
    : [];

$transportTypes = array_values(array_unique(array_column($allTransports, 'transport_type')));
$departureCities = array_values(array_unique(array_column($allTransports, 'departure_city')));
sort($transportTypes);
sort($departureCities);
?>

<section class="listing-page">
    <div class="listing-hero">
        <span>Catalogue transport</span>
        <h1>Rechercher un moyen de transport</h1>
        <p>
            Filtrez les trajets par type, ville de départ et disponibilités
            avant de les ajouter à votre séjour.
        </p>
    </div>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning app-alert-centered">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <form action="index.php" method="GET" class="listing-filter-form">
        <input type="hidden" name="page" value="transports">

        <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Rechercher un trajet ou une destination">

        <select name="type">
            <option value="all">Tous les types</option>
            <?php foreach ($transportTypes as $value) { ?>
                <option value="<?php echo e($value); ?>" <?php echo $transportType === $value ? 'selected' : ''; ?>>
                    <?php echo e($value); ?>
                </option>
            <?php } ?>
        </select>

        <select name="departure">
            <option value="all">Toutes les villes</option>
            <?php foreach ($departureCities as $value) { ?>
                <option value="<?php echo e($value); ?>" <?php echo $departureCity === $value ? 'selected' : ''; ?>>
                    <?php echo e($value); ?>
                </option>
            <?php } ?>
        </select>

        <select name="sort">
            <option value="price" <?php echo $sort === 'price' ? 'selected' : ''; ?>>Prix croissant</option>
            <option value="places" <?php echo $sort === 'places' ? 'selected' : ''; ?>>Plus de places</option>
            <option value="type" <?php echo $sort === 'type' ? 'selected' : ''; ?>>Type</option>
        </select>

        <input type="date" name="date_depart" value="<?php echo e($dateDepart); ?>" aria-label="Date de départ">

        <input type="date" name="date_retour" value="<?php echo e($dateRetour); ?>" aria-label="Date de retour">

        <label class="checkbox-filter">
            <input type="checkbox" name="available_only" value="1" <?php echo $availableOnly ? 'checked' : ''; ?>>
            Seulement disponibles
        </label>

        <button type="submit">Filtrer</button>
    </form>

    <?php if (!$datesValid) { ?>
        <div class="app-alert app-alert-error app-alert-centered">
            La date de retour doit être postérieure à la date de départ.
        </div>
    <?php } elseif ($datesComplete) { ?>
        <div class="app-alert app-alert-success app-alert-centered">
            Recherche de transport pour un aller le <?php echo format_date_fr($dateDepart); ?>
            et un retour le <?php echo format_date_fr($dateRetour); ?>.
        </div>
    <?php } ?>

    <?php if (empty($transports)) { ?>
        <div class="empty-listing-card">
            <h2>Aucun transport trouvé</h2>
            <p>Essayez une autre ville de départ ou retirez certains filtres.</p>
        </div>
    <?php } else { ?>
        <div class="transport-grid">
            <?php foreach ($transports as $transport) { ?>
                <?php $totalPrice = (float) $transport['price'] + (float) $transport['return_price']; ?>

                <article class="transport-card">
                    <div class="transport-img" style="background-image:url('<?php echo e($transport['image_url']); ?>')">
                        <span class="transport-type"><?php echo e($transport['transport_type']); ?></span>
                    </div>

                    <div class="transport-content">
                        <h3><?php echo e($transport['destination_name']); ?> depuis <?php echo e($transport['departure_city']); ?></h3>

                        <div class="transport-route">
                            <p><strong>Aller :</strong> <?php echo e($transport['departure_city']); ?> -> <?php echo e($transport['arrival_city']); ?></p>
                            <p><strong>Retour :</strong> inclus dans le total</p>
                        </div>

                        <div class="transport-info">
                            <span>Durée : <?php echo e($transport['duration']); ?></span>
                            <span><?php echo e($transport['details']); ?></span>
                            <?php if ($datesComplete && $datesValid) { ?>
                                <span class="date-meta">Aller : <?php echo format_date_fr($dateDepart); ?> • Retour : <?php echo format_date_fr($dateRetour); ?></span>
                            <?php } else { ?>
                                <span class="date-meta warning">Dates à préciser</span>
                            <?php } ?>
                            <span>Places restantes : <?php echo (int) $transport['available_seats']; ?></span>
                        </div>

                        <div class="transport-bottom">
                            <div>
                                <small>Aller-retour</small>
                                <strong><?php echo format_price($totalPrice); ?> €</strong>
                            </div>

                            <?php if ((int) $transport['available_seats'] > 0 && $datesComplete && $datesValid) { ?>
                                <a class="catalog-action-btn" href="index.php?page=circuit&depart=<?php echo urlencode($transport['departure_city']); ?>&destination=<?php echo urlencode($transport['destination_name']); ?>&date_depart=<?php echo urlencode($dateDepart); ?>&date_retour=<?php echo urlencode($dateRetour); ?>#transport">
                                    Sélectionner
                                </a>
                            <?php } elseif ((int) $transport['available_seats'] > 0) { ?>
                                <span class="catalog-disabled-badge">Dates requises</span>
                            <?php } else { ?>
                                <span class="catalog-disabled-badge">Complet</span>
                            <?php } ?>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } ?>
</section>
