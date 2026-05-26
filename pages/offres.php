<?php
$destination = strtolower(trim($_GET['destination'] ?? ''));
$date_depart = $_GET['date_depart'] ?? '';
$voyageurs = (int)($_GET['voyageurs'] ?? 2);
$budget_max = $_GET['budget'] ?? '';

$type_filters = $_GET['type'] ?? [];
$student_filters = $_GET['student_filter'] ?? [];
$sort = $_GET['sort'] ?? 'pertinence';

if ($voyageurs <= 0) {
    $voyageurs = 1;
}

$offers = [
    [
        "city" => "Lisbonne",
        "country" => "Portugal",
        "img" => "https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=1200&q=90",
        "tag" => "TOP ÉTUDIANT",
        "type" => "Vol + Auberge",
        "title" => "Lisbonne entre amis",
        "place" => "Lisbonne, Portugal",
        "features" => ["Petit budget", "Sans voiture", "Vie nocturne", "Groupe"],
        "duration" => "5 jours / 4 nuits",
        "price" => 389,
        "rating" => 4.7,
        "student_score" => 9.4,
        "daily_budget" => 42,
        "car_free_score" => 9,
        "nightlife_score" => 9
    ],
    [
        "city" => "Barcelone",
        "country" => "Espagne",
        "img" => "https://images.unsplash.com/photo-1583422409516-2895a77efded?auto=format&fit=crop&w=1200&q=90",
        "tag" => "ENTRE AMIS",
        "type" => "Vol + Hôtel",
        "title" => "Barcelona Student Break",
        "place" => "Barcelone, Espagne",
        "features" => ["Plage", "Sans voiture", "Vie nocturne", "Groupe"],
        "duration" => "5 jours / 4 nuits",
        "price" => 459,
        "rating" => 4.6,
        "student_score" => 8.9,
        "daily_budget" => 48,
        "car_free_score" => 10,
        "nightlife_score" => 10
    ],
    [
        "city" => "Valence",
        "country" => "Espagne",
        "img" => "https://images.unsplash.com/photo-1624262149711-397fc3a9d3fb?auto=format&fit=crop&w=1200&q=90",
        "tag" => "PETIT BUDGET",
        "type" => "Vol + Hôtel",
        "title" => "Valence plage & budget",
        "place" => "Valence, Espagne",
        "features" => ["Petit budget", "Plage", "Sans voiture"],
        "duration" => "5 jours / 4 nuits",
        "price" => 399,
        "rating" => 4.4,
        "student_score" => 8.8,
        "daily_budget" => 39,
        "car_free_score" => 9,
        "nightlife_score" => 8
    ],
    [
        "city" => "Budapest",
        "country" => "Hongrie",
        "img" => "https://images.unsplash.com/photo-1541849546-216549ae216d?auto=format&fit=crop&w=1200&q=90",
        "tag" => "ULTRA BUDGET",
        "type" => "Vol + Auberge",
        "title" => "Budapest low cost",
        "place" => "Budapest, Hongrie",
        "features" => ["Petit budget", "Vie nocturne", "Groupe"],
        "duration" => "4 jours / 3 nuits",
        "price" => 329,
        "rating" => 4.5,
        "student_score" => 9.5,
        "daily_budget" => 34,
        "car_free_score" => 8,
        "nightlife_score" => 9
    ],
    [
        "city" => "Prague",
        "country" => "République tchèque",
        "img" => "https://images.unsplash.com/photo-1541849546-216549ae216d?auto=format&fit=crop&w=1200&q=90",
        "tag" => "CITY TRIP",
        "type" => "Vol + Auberge",
        "title" => "Prague petit prix",
        "place" => "Prague, République tchèque",
        "features" => ["Petit budget", "Culture", "Sans voiture", "Groupe"],
        "duration" => "4 jours / 3 nuits",
        "price" => 349,
        "rating" => 4.5,
        "student_score" => 9.1,
        "daily_budget" => 36,
        "car_free_score" => 9,
        "nightlife_score" => 8
    ],
    [
        "city" => "Marrakech",
        "country" => "Maroc",
        "img" => "https://images.unsplash.com/photo-1597212618440-806262de4f6b?auto=format&fit=crop&w=1200&q=90",
        "tag" => "SOLEIL BUDGET",
        "type" => "Vol + Riad",
        "title" => "Marrakech entre étudiants",
        "place" => "Marrakech, Maroc",
        "features" => ["Petit budget", "Culture", "Groupe"],
        "duration" => "5 jours / 4 nuits",
        "price" => 429,
        "rating" => 4.6,
        "student_score" => 8.7,
        "daily_budget" => 32,
        "car_free_score" => 7,
        "nightlife_score" => 7
    ],
    [
        "city" => "Athènes",
        "country" => "Grèce",
        "img" => "https://images.unsplash.com/photo-1555993539-1732b0258235?auto=format&fit=crop&w=1200&q=90",
        "tag" => "CULTURE",
        "type" => "Vol + Hôtel",
        "title" => "Athènes culture & soleil",
        "place" => "Athènes, Grèce",
        "features" => ["Culture", "Sans voiture", "Petit budget"],
        "duration" => "4 jours / 3 nuits",
        "price" => 489,
        "rating" => 4.5,
        "student_score" => 8.4,
        "daily_budget" => 45,
        "car_free_score" => 8,
        "nightlife_score" => 7
    ],
    [
        "city" => "Naples",
        "country" => "Italie",
        "img" => "https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1200&q=90",
        "tag" => "ITALIE PAS CHÈRE",
        "type" => "Train + Hôtel",
        "title" => "Naples food trip",
        "place" => "Naples, Italie",
        "features" => ["Petit budget", "Culture", "Sans voiture"],
        "duration" => "5 jours / 4 nuits",
        "price" => 449,
        "rating" => 4.4,
        "student_score" => 8.6,
        "daily_budget" => 41,
        "car_free_score" => 8,
        "nightlife_score" => 7
    ],
    [
        "city" => "Paris",
        "country" => "France",
        "img" => "https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1200&q=90",
        "tag" => "WEEK-END",
        "type" => "Train + Hôtel",
        "title" => "Paris week-end étudiant",
        "place" => "Paris, France",
        "features" => ["Sans voiture", "Culture", "Court séjour"],
        "duration" => "3 jours / 2 nuits",
        "price" => 299,
        "rating" => 4.3,
        "student_score" => 7.9,
        "daily_budget" => 58,
        "car_free_score" => 10,
        "nightlife_score" => 9
    ],
    [
        "city" => "Lyon",
        "country" => "France",
        "img" => "https://images.unsplash.com/photo-1526256262350-7da7584cf5eb?auto=format&fit=crop&w=1200&q=90",
        "tag" => "TRAIN EASY",
        "type" => "Train + Hôtel",
        "title" => "Lyon sans voiture",
        "place" => "Lyon, France",
        "features" => ["Sans voiture", "Culture", "Court séjour"],
        "duration" => "3 jours / 2 nuits",
        "price" => 249,
        "rating" => 4.2,
        "student_score" => 8.1,
        "daily_budget" => 46,
        "car_free_score" => 9,
        "nightlife_score" => 8
    ]
];

$filtered = array_filter($offers, function ($offer) use ($destination, $type_filters, $student_filters, $budget_max) {
    $matchDestination = true;

    if ($destination !== '') {
        $matchDestination =
            str_contains(strtolower($offer['city']), $destination) ||
            str_contains(strtolower($offer['country']), $destination) ||
            str_contains(strtolower($offer['place']), $destination);
    }

    $matchType = empty($type_filters) || in_array($offer['type'], $type_filters);

    $matchStudentFilters = true;
    foreach ($student_filters as $filter) {
        if (!in_array($filter, $offer['features'])) {
            $matchStudentFilters = false;
        }
    }

    $matchBudget = true;
    if ($budget_max !== '' && is_numeric($budget_max)) {
        $matchBudget = $offer['price'] <= (int)$budget_max;
    }

    return $matchDestination && $matchType && $matchStudentFilters && $matchBudget;
});

$filtered = array_values($filtered);

if ($sort === 'prix') {
    usort($filtered, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($sort === 'score') {
    usort($filtered, fn($a, $b) => $b['student_score'] <=> $a['student_score']);
} elseif ($sort === 'budget_jour') {
    usort($filtered, fn($a, $b) => $a['daily_budget'] <=> $b['daily_budget']);
} else {
    usort($filtered, fn($a, $b) => $b['student_score'] <=> $a['student_score']);
}
?>

<section class="offers-page">

    <div class="offers-header">
        <div>
            <h1>Bons plans étudiants</h1>
            <p>
                <?php echo $destination ? htmlspecialchars(ucfirst($destination)) : 'Toutes les destinations étudiantes'; ?>
                • <?php echo $date_depart ? date('d/m/Y', strtotime($date_depart)) : 'Dates flexibles'; ?>
                • <?php echo htmlspecialchars($voyageurs); ?> voyageur(s)
                <?php if ($budget_max !== '') { ?>
                    • Budget max : <?php echo htmlspecialchars($budget_max); ?>€
                <?php } ?>
            </p>
        </div>

        <form method="GET">
            <input type="hidden" name="page" value="offres">
            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="date_depart" value="<?php echo htmlspecialchars($date_depart); ?>">
            <input type="hidden" name="voyageurs" value="<?php echo htmlspecialchars($voyageurs); ?>">
            <input type="hidden" name="budget" value="<?php echo htmlspecialchars($budget_max); ?>">

            <select name="sort" onchange="this.form.submit()">
                <option value="pertinence" <?php if ($sort === 'pertinence') echo 'selected'; ?>>
                    Pertinence étudiante
                </option>
                <option value="score" <?php if ($sort === 'score') echo 'selected'; ?>>
                    Meilleur score étudiant
                </option>
                <option value="prix" <?php if ($sort === 'prix') echo 'selected'; ?>>
                    Prix croissant
                </option>
                <option value="budget_jour" <?php if ($sort === 'budget_jour') echo 'selected'; ?>>
                    Budget / jour
                </option>
            </select>
        </form>
    </div>

    <div class="student-intro-box">
        <h2>VoyageVista Student Budget</h2>
        <p>
            Notre sélection privilégie les destinations accessibles aux étudiants :
            prix raisonnables, transports faciles, activités de groupe et coût réel sur place.
        </p>
    </div>

    <div class="offers-layout">

        <aside class="offers-filters">
            <form method="GET">
                <input type="hidden" name="page" value="offres">
                <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                <input type="hidden" name="date_depart" value="<?php echo htmlspecialchars($date_depart); ?>">
                <input type="hidden" name="voyageurs" value="<?php echo htmlspecialchars($voyageurs); ?>">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                <h2>Filtres étudiants</h2>

                <h3>Budget maximum</h3>
                <select name="budget" class="budget-filter-select">
                    <option value="">Tous les budgets</option>
                    <option value="300" <?php if ($budget_max === '300') echo 'selected'; ?>>Moins de 300€</option>
                    <option value="500" <?php if ($budget_max === '500') echo 'selected'; ?>>Moins de 500€</option>
                    <option value="800" <?php if ($budget_max === '800') echo 'selected'; ?>>Moins de 800€</option>
                    <option value="1200" <?php if ($budget_max === '1200') echo 'selected'; ?>>Moins de 1200€</option>
                </select>

                <h3>Type de séjour</h3>
                <?php foreach (["Vol + Auberge", "Vol + Hôtel", "Train + Hôtel", "Vol + Riad"] as $type) { ?>
                    <label>
                        <input
                            type="checkbox"
                            name="type[]"
                            value="<?php echo $type; ?>"
                            <?php if (in_array($type, $type_filters)) echo 'checked'; ?>
                        >
                        <?php echo $type; ?>
                    </label>
                <?php } ?>

                <h3>Priorités étudiantes</h3>
                <?php foreach (["Petit budget", "Sans voiture", "Vie nocturne", "Groupe", "Culture", "Plage"] as $filter) { ?>
                    <label>
                        <input
                            type="checkbox"
                            name="student_filter[]"
                            value="<?php echo $filter; ?>"
                            <?php if (in_array($filter, $student_filters)) echo 'checked'; ?>
                        >
                        <?php echo $filter; ?>
                    </label>
                <?php } ?>

                <button class="filter-apply-btn" type="submit">Appliquer les filtres</button>

                <a class="filter-reset-btn" href="index.php?page=offres">
                    Réinitialiser
                </a>
            </form>
        </aside>

        <div class="offers-list">

            <?php if (empty($filtered)) { ?>
                <div class="no-offer">
                    <h2>Aucune offre trouvée</h2>
                    <p>
                        Essayez un budget plus large ou une destination comme Lisbonne,
                        Barcelone, Budapest, Prague, Marrakech, Athènes, Naples ou Paris.
                    </p>
                </div>
            <?php } ?>

            <?php foreach ($filtered as $offer) {
                $price_per_person = $offer['price'];
                $estimated_total_group = $price_per_person * $voyageurs;
            ?>
                <article class="big-offer-card">

                    <div class="big-offer-img" style="background-image:url('<?php echo $offer['img']; ?>')">
                        <span><?php echo $offer['tag']; ?></span>
                    </div>

                    <div class="big-offer-content">
                        <p class="offer-type"><?php echo $offer['type']; ?></p>

                        <h2><?php echo $offer['title']; ?></h2>

                        <p class="offer-place">
                            📍 <?php echo $offer['place']; ?>
                        </p>

                        <div class="student-badges">
                            <?php foreach ($offer['features'] as $feature) { ?>
                                <span class="badge">
                                    <?php echo $feature; ?>
                                </span>
                            <?php } ?>
                        </div>

                        <div class="student-score-box">
                            <p>
                                ⭐ Score étudiant :
                                <strong><?php echo $offer['student_score']; ?>/10</strong>
                            </p>

                            <p>
                                💸 Budget moyen sur place :
                                <strong><?php echo $offer['daily_budget']; ?>€/jour</strong>
                            </p>

                            <p>
                                🚆 Accessibilité sans voiture :
                                <strong><?php echo $offer['car_free_score']; ?>/10</strong>
                            </p>

                            <p>
                                🎉 Vie étudiante :
                                <strong><?php echo $offer['nightlife_score']; ?>/10</strong>
                            </p>
                        </div>
                    </div>

                    <div class="big-offer-price">
                        <small>Dès</small>

                        <strong><?php echo $offer['price']; ?>€</strong>

                        <p>par personne</p>

                        <span><?php echo $offer['duration']; ?></span>

                        <div class="group-price">
                            Pour <?php echo $voyageurs; ?> voyageur(s) :
                            <strong><?php echo $estimated_total_group; ?>€</strong>
                        </div>

                        <a
                            class="offer-button"
                            href="index.php?page=circuit&destination=<?php echo urlencode($offer['city']); ?>&date_depart=<?php echo urlencode($date_depart); ?>&voyageurs=<?php echo urlencode((string) $voyageurs); ?>&budget=<?php echo urlencode((string) $budget_max); ?>"
                        >
                            Ajouter au budget planner
                        </a>
                    </div>

                </article>
            <?php } ?>

        </div>
    </div>
</section>
