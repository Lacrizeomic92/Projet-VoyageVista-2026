<?php
$destination = strtolower(trim($_GET['destination'] ?? ''));
$date_depart = $_GET['date_depart'] ?? '';
$voyageurs = $_GET['voyageurs'] ?? '2';

$type_filters = $_GET['type'] ?? [];
$service_filters = $_GET['service'] ?? [];
$sort = $_GET['sort'] ?? 'pertinence';

$offers = [
    ["city"=>"Paris", "country"=>"France", "img"=>"https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1200&q=90", "tag"=>"CITY BREAK", "type"=>"Hôtel seul", "title"=>"Hôtel Paris Montmartre 4★", "place"=>"Paris, France", "features"=>["Wifi","Petit déjeuner","Centre-ville"], "duration"=>"3 jours / 2 nuits", "price"=>329, "rating"=>4.6],
    ["city"=>"Paris", "country"=>"France", "img"=>"https://images.unsplash.com/photo-1499856871958-5b9627545d1a?auto=format&fit=crop&w=1200&q=90", "tag"=>"TOP VENTE", "type"=>"Vol + Hôtel", "title"=>"Séjour Paris Élégance 4★", "place"=>"Paris, France", "features"=>["Wifi","Restaurant","Centre-ville"], "duration"=>"4 jours / 3 nuits", "price"=>489, "rating"=>4.7],
    ["city"=>"Lisbonne", "country"=>"Portugal", "img"=>"https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=1200&q=90", "tag"=>"PETIT BUDGET", "type"=>"Vol + Hôtel", "title"=>"Lisbonne Student Trip 3★", "place"=>"Lisbonne, Portugal", "features"=>["Wifi","Petit déjeuner"], "duration"=>"5 jours / 4 nuits", "price"=>389, "rating"=>4.4],
    ["city"=>"Barcelone", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1583422409516-2895a77efded?auto=format&fit=crop&w=1200&q=90", "tag"=>"SOLEIL", "type"=>"Vol + Hôtel", "title"=>"Barcelona Beach Break 4★", "place"=>"Barcelone, Espagne", "features"=>["Piscine","Wifi","Restaurant"], "duration"=>"5 jours / 4 nuits", "price"=>459, "rating"=>4.5],
    ["city"=>"Ibiza", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=90", "tag"=>"TOP VENTE", "type"=>"Vol + Hôtel + Transfert", "title"=>"Hôtel Insotel Club Tarida Playa 4★", "place"=>"Ibiza, Baléares", "features"=>["Piscine","Restaurant","Wifi","Club enfant"], "duration"=>"9 jours / 8 nuits", "price"=>1363, "rating"=>4.8],
    ["city"=>"Majorque", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1200&q=90", "tag"=>"FAMILLE", "type"=>"Vol + Hôtel", "title"=>"Hôtel Torre Azul & Spa 4★", "place"=>"Majorque, Espagne", "features"=>["Spa","Piscine","Climatisation","Petit déjeuner"], "duration"=>"7 jours / 6 nuits", "price"=>604, "rating"=>4.3],
    ["city"=>"Corfou", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=90", "tag"=>"PETIT BUDGET", "type"=>"Hôtel seul", "title"=>"Blue Sea Beach Resort 3★", "place"=>"Corfou, Grèce", "features"=>["Plage proche","Wifi","Restaurant"], "duration"=>"5 jours / 4 nuits", "price"=>389, "rating"=>4.1],
    ["city"=>"Bali", "country"=>"Indonésie", "img"=>"https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=90", "tag"=>"AVENTURE", "type"=>"Vol + Hôtel + Activités", "title"=>"Bali Tropical Escape 4★", "place"=>"Bali, Indonésie", "features"=>["Excursions","Guide local","Piscine","Petit déjeuner"], "duration"=>"10 jours / 9 nuits", "price"=>1249, "rating"=>4.9],
    ["city"=>"Santorin", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?auto=format&fit=crop&w=1200&q=90", "tag"=>"PREMIUM", "type"=>"Séjour tout compris", "title"=>"Santorini White Suites 5★", "place"=>"Santorin, Grèce", "features"=>["Piscine","Petit déjeuner","Vue mer","Wifi"], "duration"=>"6 jours / 5 nuits", "price"=>1590, "rating"=>4.9],
    ["city"=>"Marrakech", "country"=>"Maroc", "img"=>"https://images.unsplash.com/photo-1597212618440-806262de4f6b?auto=format&fit=crop&w=1200&q=90", "tag"=>"CULTURE", "type"=>"Vol + Hôtel", "title"=>"Riad Marrakech Palace 4★", "place"=>"Marrakech, Maroc", "features"=>["Spa","Restaurant","Petit déjeuner","Wifi"], "duration"=>"5 jours / 4 nuits", "price"=>529, "rating"=>4.6],
    ["city"=>"Dubaï", "country"=>"Émirats Arabes Unis", "img"=>"https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=90", "tag"=>"LUXE", "type"=>"Vol + Hôtel", "title"=>"Dubai Skyline Resort 5★", "place"=>"Dubaï, Émirats Arabes Unis", "features"=>["Piscine","Spa","Restaurant","Wifi"], "duration"=>"7 jours / 6 nuits", "price"=>1189, "rating"=>4.7],
    ["city"=>"Rome", "country"=>"Italie", "img"=>"https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=1200&q=90", "tag"=>"CULTURE", "type"=>"Hôtel seul", "title"=>"Roma Centro Hôtel 4★", "place"=>"Rome, Italie", "features"=>["Centre-ville","Wifi","Petit déjeuner"], "duration"=>"4 jours / 3 nuits", "price"=>449, "rating"=>4.5],
    ["city"=>"Venise", "country"=>"Italie", "img"=>"https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?auto=format&fit=crop&w=1200&q=90", "tag"=>"ROMANTIQUE", "type"=>"Vol + Hôtel", "title"=>"Venise Canal Hôtel 4★", "place"=>"Venise, Italie", "features"=>["Wifi","Petit déjeuner","Centre-ville"], "duration"=>"4 jours / 3 nuits", "price"=>579, "rating"=>4.6],
    ["city"=>"Florence", "country"=>"Italie", "img"=>"https://images.unsplash.com/photo-1541370976299-4d24ebbc9077?auto=format&fit=crop&w=1200&q=90", "tag"=>"CULTURE", "type"=>"Hôtel seul", "title"=>"Florence Art Stay 4★", "place"=>"Florence, Italie", "features"=>["Wifi","Restaurant","Centre-ville"], "duration"=>"3 jours / 2 nuits", "price"=>349, "rating"=>4.5],
    ["city"=>"Naples", "country"=>"Italie", "img"=>"https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1200&q=90", "tag"=>"SOLEIL", "type"=>"Vol + Hôtel", "title"=>"Naples Bay Resort 4★", "place"=>"Naples, Italie", "features"=>["Piscine","Restaurant","Wifi"], "duration"=>"5 jours / 4 nuits", "price"=>499, "rating"=>4.4],
    ["city"=>"Milan", "country"=>"Italie", "img"=>"https://images.unsplash.com/photo-1545157000-85f257f7b040?auto=format&fit=crop&w=1200&q=90", "tag"=>"CITY BREAK", "type"=>"Hôtel seul", "title"=>"Milano Design Hôtel 4★", "place"=>"Milan, Italie", "features"=>["Wifi","Petit déjeuner","Centre-ville"], "duration"=>"3 jours / 2 nuits", "price"=>399, "rating"=>4.3],
    ["city"=>"Nice", "country"=>"France", "img"=>"https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1200&q=90", "tag"=>"SOLEIL", "type"=>"Hôtel seul", "title"=>"Nice Riviera Hôtel 4★", "place"=>"Nice, France", "features"=>["Piscine","Wifi","Petit déjeuner"], "duration"=>"4 jours / 3 nuits", "price"=>459, "rating"=>4.5],
    ["city"=>"Annecy", "country"=>"France", "img"=>"https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=90", "tag"=>"NATURE", "type"=>"Hôtel + Activités", "title"=>"Annecy Lake Lodge 4★", "place"=>"Annecy, France", "features"=>["Wifi","Spa","Petit déjeuner"], "duration"=>"4 jours / 3 nuits", "price"=>529, "rating"=>4.7],
    ["city"=>"Biarritz", "country"=>"France", "img"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=90", "tag"=>"PLAGE", "type"=>"Vol + Hôtel", "title"=>"Biarritz Surf Hôtel 3★", "place"=>"Biarritz, France", "features"=>["Plage proche","Wifi","Restaurant"], "duration"=>"5 jours / 4 nuits", "price"=>549, "rating"=>4.4],
    ["city"=>"Madrid", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1539037116277-4db20889f2d4?auto=format&fit=crop&w=1200&q=90", "tag"=>"CITY BREAK", "type"=>"Vol + Hôtel", "title"=>"Madrid Centro Hôtel 4★", "place"=>"Madrid, Espagne", "features"=>["Wifi","Centre-ville","Petit déjeuner"], "duration"=>"4 jours / 3 nuits", "price"=>439, "rating"=>4.5],
    ["city"=>"Séville", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1559564484-e48b3e040ff4?auto=format&fit=crop&w=1200&q=90", "tag"=>"CULTURE", "type"=>"Hôtel seul", "title"=>"Séville Patio Palace 4★", "place"=>"Séville, Espagne", "features"=>["Wifi","Restaurant","Climatisation"], "duration"=>"4 jours / 3 nuits", "price"=>379, "rating"=>4.6],
    ["city"=>"Valence", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1539037116277-4db20889f2d4?auto=format&fit=crop&w=1200&q=90", "tag"=>"PETIT BUDGET", "type"=>"Vol + Hôtel", "title"=>"Valencia Beach Hôtel 3★", "place"=>"Valence, Espagne", "features"=>["Wifi","Plage proche","Petit déjeuner"], "duration"=>"5 jours / 4 nuits", "price"=>399, "rating"=>4.3],
    ["city"=>"Malaga", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=90", "tag"=>"SOLEIL", "type"=>"Vol + Hôtel + Transfert", "title"=>"Malaga Costa Resort 4★", "place"=>"Malaga, Espagne", "features"=>["Piscine","Restaurant","Wifi"], "duration"=>"7 jours / 6 nuits", "price"=>649, "rating"=>4.5],
    ["city"=>"Tenerife", "country"=>"Espagne", "img"=>"https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=1200&q=90", "tag"=>"TOUT COMPRIS", "type"=>"Séjour tout compris", "title"=>"Tenerife Ocean Club 4★", "place"=>"Tenerife, Espagne", "features"=>["Piscine","Restaurant","Club enfant","Wifi"], "duration"=>"8 jours / 7 nuits", "price"=>899, "rating"=>4.6],
    ["city"=>"Agadir", "country"=>"Maroc", "img"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=90", "tag"=>"PLAGE", "type"=>"Vol + Hôtel", "title"=>"Agadir Beach Resort 4★", "place"=>"Agadir, Maroc", "features"=>["Piscine","Restaurant","Wifi"], "duration"=>"7 jours / 6 nuits", "price"=>629, "rating"=>4.4],
    ["city"=>"Essaouira", "country"=>"Maroc", "img"=>"https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1200&q=90", "tag"=>"AUTHENTIQUE", "type"=>"Hôtel seul", "title"=>"Essaouira Riad Bleu 4★", "place"=>"Essaouira, Maroc", "features"=>["Wifi","Petit déjeuner","Restaurant"], "duration"=>"5 jours / 4 nuits", "price"=>349, "rating"=>4.5],
    ["city"=>"Fès", "country"=>"Maroc", "img"=>"https://images.unsplash.com/photo-1579019163248-e7761241d85e?auto=format&fit=crop&w=1200&q=90", "tag"=>"CULTURE", "type"=>"Vol + Hôtel", "title"=>"Fès Médina Palace 4★", "place"=>"Fès, Maroc", "features"=>["Guide local","Wifi","Petit déjeuner"], "duration"=>"5 jours / 4 nuits", "price"=>499, "rating"=>4.6],
    ["city"=>"Casablanca", "country"=>"Maroc", "img"=>"https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?auto=format&fit=crop&w=1200&q=90", "tag"=>"CITY BREAK", "type"=>"Hôtel seul", "title"=>"Casablanca Urban Hôtel 4★", "place"=>"Casablanca, Maroc", "features"=>["Wifi","Restaurant","Climatisation"], "duration"=>"4 jours / 3 nuits", "price"=>389, "rating"=>4.2],
    ["city"=>"Athènes", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1555993539-1732b0258235?auto=format&fit=crop&w=1200&q=90", "tag"=>"CULTURE", "type"=>"Vol + Hôtel", "title"=>"Athènes Acropole Hôtel 4★", "place"=>"Athènes, Grèce", "features"=>["Wifi","Centre-ville","Petit déjeuner"], "duration"=>"4 jours / 3 nuits", "price"=>489, "rating"=>4.5],
    ["city"=>"Mykonos", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1601581875309-fafbf2d3ed3a?auto=format&fit=crop&w=1200&q=90", "tag"=>"PREMIUM", "type"=>"Vol + Hôtel", "title"=>"Mykonos Blue Suites 5★", "place"=>"Mykonos, Grèce", "features"=>["Piscine","Vue mer","Wifi","Restaurant"], "duration"=>"6 jours / 5 nuits", "price"=>1390, "rating"=>4.8],
    ["city"=>"Crète", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=1200&q=90", "tag"=>"FAMILLE", "type"=>"Séjour tout compris", "title"=>"Crète Family Resort 4★", "place"=>"Crète, Grèce", "features"=>["Club enfant","Piscine","Restaurant","Wifi"], "duration"=>"8 jours / 7 nuits", "price"=>799, "rating"=>4.6],
    ["city"=>"Rhodes", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1200&q=90", "tag"=>"SOLEIL", "type"=>"Vol + Hôtel + Transfert", "title"=>"Rhodes Sea View Hôtel 4★", "place"=>"Rhodes, Grèce", "features"=>["Piscine","Plage proche","Petit déjeuner"], "duration"=>"7 jours / 6 nuits", "price"=>689, "rating"=>4.4],
    ["city"=>"Zakynthos", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=90", "tag"=>"PLAGE", "type"=>"Hôtel seul", "title"=>"Zakynthos Blue Bay 3★", "place"=>"Zakynthos, Grèce", "features"=>["Plage proche","Wifi","Restaurant"], "duration"=>"5 jours / 4 nuits", "price"=>429, "rating"=>4.3],
    ["city"=>"Paros", "country"=>"Grèce", "img"=>"https://images.unsplash.com/photo-1504512485720-7d83a16ee930?auto=format&fit=crop&w=1200&q=90", "tag"=>"COUP DE CŒUR", "type"=>"Vol + Hôtel", "title"=>"Paros Cyclades Hôtel 4★", "place"=>"Paros, Grèce", "features"=>["Wifi","Petit déjeuner","Vue mer"], "duration"=>"6 jours / 5 nuits", "price"=>749, "rating"=>4.7],
];

$filtered = array_filter($offers, function($offer) use ($destination, $type_filters, $service_filters) {
    $matchDestination = true;

    if ($destination !== '') {
        $matchDestination =
            str_contains(strtolower($offer['city']), $destination) ||
            str_contains(strtolower($offer['country']), $destination) ||
            str_contains(strtolower($offer['place']), $destination);
    }

    $matchType = empty($type_filters) || in_array($offer['type'], $type_filters);

    $matchServices = true;
    foreach ($service_filters as $service) {
        if (!in_array($service, $offer['features'])) {
            $matchServices = false;
        }
    }

    return $matchDestination && $matchType && $matchServices;
});

if ($sort === 'prix') {
    usort($filtered, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($sort === 'note') {
    usort($filtered, fn($a, $b) => $b['rating'] <=> $a['rating']);
} else {
    usort($filtered, fn($a, $b) => $b['rating'] <=> $a['rating']);
}
?>

<section class="offers-page">

    <div class="offers-header">
        <div>
            <h1>Bons plans voyage</h1>
            <p>
                <?php echo $destination ? htmlspecialchars(ucfirst($destination)) : 'Toutes les destinations'; ?>
                • <?php echo $date_depart ? date('d/m/Y', strtotime($date_depart)) : 'Dates flexibles'; ?>
                • <?php echo htmlspecialchars($voyageurs); ?> voyageur(s)
            </p>
        </div>

        <form method="GET">
            <input type="hidden" name="page" value="offres">
            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="date_depart" value="<?php echo htmlspecialchars($date_depart); ?>">
            <input type="hidden" name="voyageurs" value="<?php echo htmlspecialchars($voyageurs); ?>">

            <select name="sort" onchange="this.form.submit()">
                <option value="pertinence" <?php if($sort === 'pertinence') echo 'selected'; ?>>Pertinence</option>
                <option value="prix" <?php if($sort === 'prix') echo 'selected'; ?>>Prix croissant</option>
                <option value="note" <?php if($sort === 'note') echo 'selected'; ?>>Mieux notés</option>
            </select>
        </form>
    </div>

    <div class="offers-layout">

        <aside class="offers-filters">
            <form method="GET">
                <input type="hidden" name="page" value="offres">
                <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                <input type="hidden" name="date_depart" value="<?php echo htmlspecialchars($date_depart); ?>">
                <input type="hidden" name="voyageurs" value="<?php echo htmlspecialchars($voyageurs); ?>">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                <h2>Filtres</h2>

                <h3>Type de séjour</h3>
                <?php foreach (["Vol + Hôtel","Vol + Hôtel + Transfert","Vol + Hôtel + Activités","Hôtel seul","Séjour tout compris"] as $type) { ?>
                    <label>
                        <input type="checkbox" name="type[]" value="<?php echo $type; ?>" <?php if(in_array($type, $type_filters)) echo 'checked'; ?>>
                        <?php echo $type; ?>
                    </label>
                <?php } ?>

                <h3>Services</h3>
                <?php foreach (["Piscine","Petit déjeuner","Club enfant","Wifi","Restaurant","Spa"] as $service) { ?>
                    <label>
                        <input type="checkbox" name="service[]" value="<?php echo $service; ?>" <?php if(in_array($service, $service_filters)) echo 'checked'; ?>>
                        <?php echo $service; ?>
                    </label>
                <?php } ?>

                <button class="filter-apply-btn" type="submit">Appliquer les filtres</button>
                <a class="filter-reset-btn" href="index.php?page=offres&destination=<?php echo urlencode($destination); ?>&date_depart=<?php echo urlencode($date_depart); ?>&voyageurs=<?php echo urlencode($voyageurs); ?>">Réinitialiser</a>
            </form>
        </aside>

        <div class="offers-list">

            <?php if (empty($filtered)) { ?>
                <div class="no-offer">
                    <h2>Aucune offre trouvée</h2>
                    <p>Essayez une autre ville ou un pays comme Paris, Espagne, Grèce, Bali, Maroc ou Italie.</p>
                </div>
            <?php } ?>

            <?php foreach ($filtered as $offer) { ?>
                <article class="big-offer-card">
                    <div class="big-offer-img" style="background-image:url('<?php echo $offer['img']; ?>')">
                        <span><?php echo $offer['tag']; ?></span>
                    </div>

                    <div class="big-offer-content">
                        <p class="offer-type"><?php echo $offer['type']; ?></p>
                        <h2><?php echo $offer['title']; ?></h2>
                        <p class="offer-place"><?php echo $offer['place']; ?></p>
                        <p class="offer-rating">⭐ <?php echo $offer['rating']; ?>/5</p>

                        <div class="offer-tags">
                            <?php foreach ($offer['features'] as $feature) { ?>
                                <span><?php echo $feature; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="big-offer-price">
                        <small>Dès</small>
                        <strong><?php echo $offer['price']; ?>€</strong>
                        <p>par pers.</p>
                        <span><?php echo $offer['duration']; ?></span>
                        <button>Voir l’offre</button>
                    </div>
                </article>
            <?php } ?>

        </div>
    </div>
</section>