<?php
$activites = db_is_available() ? get_activites(['sort' => 'nom']) : [];

$activityTypeToken = function ($value) {
    $value = strtolower((string) $value);
    $value = str_replace(['é', 'è', 'ê', 'à', 'â', 'î', 'ï', 'ô', 'û', 'ù', 'ç', ' '], ['e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u', 'c', ''], $value);

    return $value;
};

$activityTagClass = function ($value) use ($activityTypeToken) {
    $map = [
        'gratuit' => 'activite-tag--green',
        'culture' => 'activite-tag--blue',
        'surf' => 'activite-tag--orange',
        'plage' => 'activite-tag--orange',
        'petitbudget' => 'activite-tag--yellow',
        'vieetudiante' => 'activite-tag--purple',
        'experience' => 'activite-tag--pink',
        'detente' => 'activite-tag--cyan',
    ];

    $token = $activityTypeToken($value);

    return $map[$token] ?? 'activite-tag--blue';
};

$buildSeasonTokens = function ($value) {
    $value = strtolower((string) $value);

    if (str_contains($value, 'toutes')) {
        return 'hiver ete printemps automne';
    }

    return $value;
};
?>

<section class="page activites-page">
    <div class="activites-hero">
        <div class="activites-hero-bg"></div>
        <div class="activites-hero-content">
            <p class="activites-eyebrow">Explorez le monde autrement</p>
            <h1>Activités & Aventures</h1>
            <p class="activites-subtitle">
                Recherchez, filtrez et composez des expériences adaptées à votre séjour étudiant.
            </p>

            <div class="activites-searchbar">
                <div class="activites-search-icon">🔍</div>
                <input type="text" id="activites-search-input" placeholder="Rechercher une activité, une destination..." autocomplete="off">
                <button class="activites-search-btn" id="activites-search-btn">Rechercher</button>
            </div>
        </div>
    </div>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning app-alert-centered">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <div class="activites-filters-wrap">
        <div class="activites-filters-inner">
            <div class="activites-filter-group">
                <span class="activites-filter-label">Saison</span>
                <div class="activites-filter-pills" data-filter="saison">
                    <button class="filter-pill active" data-value="all">Toutes saisons</button>
                    <button class="filter-pill" data-value="hiver">Hiver</button>
                    <button class="filter-pill" data-value="ete">Été</button>
                    <button class="filter-pill" data-value="printemps">Printemps</button>
                    <button class="filter-pill" data-value="automne">Automne</button>
                </div>
            </div>

            <div class="activites-filter-group">
                <span class="activites-filter-label">Type</span>
                <div class="activites-filter-pills" data-filter="type">
                    <button class="filter-pill active" data-value="all">Tous</button>
                    <button class="filter-pill" data-value="culture">Culture</button>
                    <button class="filter-pill" data-value="gratuit">Gratuit</button>
                    <button class="filter-pill" data-value="surf">Surf</button>
                    <button class="filter-pill" data-value="petitbudget">Petit budget</button>
                    <button class="filter-pill" data-value="vieetudiante">Vie étudiante</button>
                </div>
            </div>

            <div class="activites-filter-group">
                <span class="activites-filter-label">Niveau</span>
                <div class="activites-filter-pills" data-filter="niveau">
                    <button class="filter-pill active" data-value="all">Tous niveaux</button>
                    <button class="filter-pill" data-value="debutant">Débutant</button>
                    <button class="filter-pill" data-value="intermediaire">Intermédiaire</button>
                    <button class="filter-pill" data-value="expert">Expert</button>
                </div>
            </div>

            <button class="activites-reset-btn" id="activites-reset">Réinitialiser</button>
        </div>
    </div>

    <div class="activites-result-bar">
        <strong id="activites-count"><?php echo count($activites); ?> activité(s) disponible(s)</strong>
        <span id="activites-count-sub">Affichant toutes les activités</span>
    </div>

    <?php if (empty($activites)) { ?>
        <div class="activites-no-result" style="display:block;">
            <div class="no-result-icon">🔍</div>
            <h2>Aucune activité trouvée</h2>
            <p>Importez la base de données ou ajoutez des activités depuis l’administration.</p>
        </div>
    <?php } else { ?>
        <div class="activites-grid" id="activites-grid">
            <?php foreach ($activites as $activite) { ?>
                <?php
                $typeToken = $activityTypeToken($activite['type']);
                $seasonTokens = $buildSeasonTokens($activite['season']);
                ?>

                <article
                    class="activite-card"
                    data-saison="<?php echo e($seasonTokens); ?>"
                    data-type="<?php echo e($typeToken); ?>"
                    data-niveau="<?php echo e($activite['level']); ?>"
                    data-search="<?php echo e($activite['search_tags'] . ' ' . $activite['city'] . ' ' . $activite['name']); ?>"
                >
                    <div class="activite-img" style="background-image:url('<?php echo e($activite['image_url']); ?>')"></div>
                    <div class="activite-tag <?php echo $activityTagClass($activite['type']); ?>">
                        <?php echo e($activite['type']); ?>
                    </div>
                    <div class="activite-body">
                        <div class="activite-top">
                            <h3><?php echo e($activite['name']); ?></h3>
                            <span class="activite-loc"><?php echo e($activite['city']); ?>, <?php echo e($activite['destination_country']); ?></span>
                        </div>

                        <p><?php echo e($activite['description']); ?></p>

                        <div class="activite-meta">
                            <span class="meta-pill meta-pill--summer"><?php echo e($activite['season']); ?></span>
                            <span class="meta-pill meta-pill--level"><?php echo e($activite['level']); ?></span>
                        </div>

                        <div class="activite-footer">
                            <strong><?php echo format_price($activite['price']); ?> €</strong>
                            <button
                                class="activite-btn"
                                type="button"
                                onclick="window.location.href='index.php?page=circuit&destination=<?php echo urlencode($activite['destination_name']); ?>#activites';"
                            >
                                Voir l'offre
                            </button>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>

        <div class="activites-no-result" id="activites-no-result">
            <div class="no-result-icon">🔍</div>
            <h2>Aucune activité trouvée</h2>
            <p>Essayez de modifier vos filtres ou votre recherche.</p>
            <button class="activite-btn" id="activites-reset-2">Voir toutes les activités</button>
        </div>
    <?php } ?>
</section>
