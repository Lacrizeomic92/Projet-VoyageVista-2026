<?php
$search = trim((string) ($_GET['q'] ?? ''));
$type = trim((string) ($_GET['type'] ?? 'all'));
$availableOnly = isset($_GET['available_only']) ? 1 : 0;
$sort = trim((string) ($_GET['sort'] ?? 'price'));
$dateDepart = trim((string) ($_GET['date_depart'] ?? ''));
$dateRetour = trim((string) ($_GET['date_retour'] ?? ''));
$datesValid = are_dates_valid($dateDepart, $dateRetour);
$datesComplete = $dateDepart !== '' && $dateRetour !== '';
$nightsCount = $datesComplete && $datesValid
    ? max(1, (int) round((strtotime($dateRetour) - strtotime($dateDepart)) / 86400))
    : 0;

$allHebergements = db_is_available() ? get_hebergements(['sort' => 'price']) : [];
$hebergements = db_is_available()
    ? get_hebergements([
        'search' => $search,
        'type' => $type,
        'available_only' => $availableOnly,
        'sort' => $sort,
    ])
    : [];

$hebergementTypes = array_values(array_unique(array_column($allHebergements, 'type')));
sort($hebergementTypes);
?>

<section class="listing-page">
    <div class="listing-hero">
        <span>Catalogue hébergements</span>
        <h1>Trouver un logement adapté</h1>
        <p>
            Explorez les hébergements disponibles, filtrez-les et ajoutez le bon
            logement à votre itinéraire.
        </p>
    </div>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning app-alert-centered">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <form action="index.php" method="GET" class="listing-filter-form">
        <input type="hidden" name="page" value="hebergements">

        <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Rechercher une ville, un logement ou un type">

        <select name="type">
            <option value="all">Tous les types</option>
            <?php foreach ($hebergementTypes as $value) { ?>
                <option value="<?php echo e($value); ?>" <?php echo $type === $value ? 'selected' : ''; ?>>
                    <?php echo e($value); ?>
                </option>
            <?php } ?>
        </select>

        <select name="sort">
            <option value="price" <?php echo $sort === 'price' ? 'selected' : ''; ?>>Prix croissant</option>
            <option value="note" <?php echo $sort === 'note' ? 'selected' : ''; ?>>Meilleure note</option>
            <option value="places" <?php echo $sort === 'places' ? 'selected' : ''; ?>>Plus de chambres</option>
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
            Recherche pour un séjour du <?php echo format_date_fr($dateDepart); ?> au <?php echo format_date_fr($dateRetour); ?>
            • <?php echo $nightsCount; ?> nuit<?php echo $nightsCount > 1 ? 's' : ''; ?>.
        </div>
    <?php } ?>

    <?php if (empty($hebergements)) { ?>
        <div class="empty-listing-card">
            <h2>Aucun hébergement trouvé</h2>
            <p>Essayez une autre recherche ou ajoutez un logement depuis l’administration.</p>
        </div>
    <?php } else { ?>
        <div class="housing-grid">
            <?php foreach ($hebergements as $hebergement) { ?>
                <article class="housing-card">
                    <div class="housing-img" style="background-image:url('<?php echo e($hebergement['image_url']); ?>')">
                        <span><?php echo e($hebergement['type']); ?></span>
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
                            <?php if ($datesComplete && $datesValid) { ?>
                                <span>Du <?php echo format_date_fr($dateDepart); ?> au <?php echo format_date_fr($dateRetour); ?></span>
                                <span><?php echo $nightsCount; ?> nuit<?php echo $nightsCount > 1 ? 's' : ''; ?></span>
                            <?php } else { ?>
                                <span class="date-meta warning">Dates à préciser</span>
                            <?php } ?>
                        </div>

                        <div class="housing-bottom">
                            <div>
                                <small>À partir de</small>
                                <strong><?php echo format_price($hebergement['price_per_night']); ?> € / nuit</strong>
                            </div>

                            <?php if ((int) $hebergement['available_rooms'] > 0 && $datesComplete && $datesValid) { ?>
                                <a class="catalog-action-btn" href="index.php?page=circuit&destination=<?php echo urlencode($hebergement['destination_name']); ?>&date_depart=<?php echo urlencode($dateDepart); ?>&date_retour=<?php echo urlencode($dateRetour); ?>#logement">
                                    Réserver
                                </a>
                            <?php } elseif ((int) $hebergement['available_rooms'] > 0) { ?>
                                <span class="catalog-disabled-badge">Dates requises</span>
                            <?php } else { ?>
                                <span class="catalog-disabled-badge">Indisponible</span>
                            <?php } ?>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } ?>
</section>
