<?php
$search = trim((string) ($_GET['q'] ?? ''));
$sort = trim((string) ($_GET['sort'] ?? 'score'));
$destinations = db_is_available()
    ? get_destinations([
        'search' => $search,
        'sort' => $sort,
    ])
    : [];
?>

<section class="page destinations-page">
    <div class="section-title center">
        <p>Assistant destination</p>
        <h1>Trouvez la destination adaptée à votre séjour</h1>

        <span class="destinations-subtitle">
            Répondez à quelques critères simples : VoyageVista vous recommande
            automatiquement les destinations les plus cohérentes.
        </span>
    </div>

    <?php if (!db_is_available()) { ?>
        <div class="app-alert app-alert-warning app-alert-centered">
            <?php echo e(db_error_message()); ?>
        </div>
    <?php } ?>

    <section class="destination-assistant">
        <div class="assistant-card">
            <h2>Recherche rapide</h2>

            <form action="index.php" method="GET" class="destination-search-form">
                <input type="hidden" name="page" value="destinations">

                <input
                    type="text"
                    name="q"
                    value="<?php echo e($search); ?>"
                    placeholder="Rechercher une ville, un pays ou une ambiance"
                >

                <select name="sort">
                    <option value="score" <?php echo $sort === 'score' ? 'selected' : ''; ?>>Meilleur score</option>
                    <option value="prix" <?php echo $sort === 'prix' ? 'selected' : ''; ?>>Prix croissant</option>
                    <option value="budget_jour" <?php echo $sort === 'budget_jour' ? 'selected' : ''; ?>>Budget / jour</option>
                    <option value="nom" <?php echo $sort === 'nom' ? 'selected' : ''; ?>>Ordre alphabétique</option>
                </select>

                <button type="submit">Rechercher</button>
            </form>

            <div class="choice-section">
                <span class="choice-title">Ambiance</span>
                <div class="choice-group" data-filter="type">
                    <button type="button" class="choice-btn active" data-value="all">Toutes</button>
                    <button type="button" class="choice-btn" data-value="plage">Plage</button>
                    <button type="button" class="choice-btn" data-value="ville">Ville</button>
                    <button type="button" class="choice-btn" data-value="culture">Culture</button>
                    <button type="button" class="choice-btn" data-value="soleil">Soleil</button>
                </div>
            </div>

            <div class="choice-section">
                <span class="choice-title">Durée</span>
                <div class="choice-group" data-filter="duration">
                    <button type="button" class="choice-btn active" data-value="all">Toutes</button>
                    <button type="button" class="choice-btn" data-value="court">Court séjour</button>
                    <button type="button" class="choice-btn" data-value="long">Long séjour</button>
                </div>
            </div>

            <div class="choice-section">
                <span class="choice-title">Budget</span>
                <div class="choice-group" data-filter="budget">
                    <button type="button" class="choice-btn active" data-value="all">Tous</button>
                    <button type="button" class="choice-btn" data-value="economique">Économique</button>
                    <button type="button" class="choice-btn" data-value="moyen">Moyen</button>
                    <button type="button" class="choice-btn" data-value="premium">Premium</button>
                </div>
            </div>

            <div class="choice-section">
                <span class="choice-title">Public</span>
                <div class="choice-group" data-filter="public">
                    <button type="button" class="choice-btn active" data-value="all">Tous</button>
                    <button type="button" class="choice-btn" data-value="etudiant">Étudiant</button>
                    <button type="button" class="choice-btn" data-value="jeunesse">Jeunesse</button>
                    <button type="button" class="choice-btn" data-value="groupe">Groupe</button>
                    <button type="button" class="choice-btn" data-value="famille">Famille</button>
                </div>
            </div>

            <div class="assistant-actions">
                <button type="button" class="assistant-btn" id="apply-destination-filters">
                    Voir les recommandations
                </button>

                <button type="button" class="assistant-reset" id="reset-destination-filters">
                    Réinitialiser
                </button>
            </div>
        </div>

        <div class="assistant-result-box">
            <span>Résultat</span>

            <strong id="destination-result-count">
                <?php echo count($destinations); ?> destination(s) disponible(s)
            </strong>

            <p id="destination-result-text">
                Lancez une recherche puis affinez avec les filtres visuels.
            </p>
        </div>
    </section>

    <?php if (empty($destinations)) { ?>
        <div class="no-destination-message" style="display:block;">
            <h2>Aucune destination trouvée</h2>
            <p>La base est vide ou aucun résultat ne correspond à votre recherche.</p>
        </div>
    <?php } else { ?>
        <div class="destination-results">
            <?php foreach ($destinations as $destination) { ?>
                <article
                    class="destination-card"
                    data-type="<?php echo e($destination['category']); ?>"
                    data-duration="<?php echo e($destination['duration_type']); ?>"
                    data-budget="<?php echo e($destination['budget_level']); ?>"
                    data-public="<?php echo e($destination['audience']); ?>"
                >
                    <div
                        class="destination-image"
                        style="background-image:url('<?php echo e($destination['image_url']); ?>');"
                    ></div>

                    <div class="destination-content">
                        <span><?php echo e($destination['student_tag']); ?></span>

                        <h3><?php echo e($destination['name']); ?></h3>

                        <p>
                            <?php echo e($destination['country']); ?> •
                            <?php echo e($destination['description']); ?>
                        </p>

                        <p>
                            Score étudiant : <strong><?php echo e($destination['student_score']); ?>/10</strong>
                            • Budget / jour : <strong><?php echo format_price($destination['daily_budget']); ?> €</strong>
                        </p>

                        <strong>À partir de <?php echo format_price($destination['base_price']); ?> €</strong>

                        <a href="index.php?page=circuit&destination=<?php echo urlencode($destination['name']); ?>">
                            Voir le circuit
                        </a>
                    </div>
                </article>
            <?php } ?>
        </div>

        <div class="no-destination-message" id="no-destination-message">
            <h2>Aucune destination ne correspond à ces filtres</h2>
            <p>Essayez un autre mix de critères ou lancez une nouvelle recherche.</p>
        </div>
    <?php } ?>
</section>
