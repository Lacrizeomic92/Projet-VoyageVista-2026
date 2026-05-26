<?php
$favoris = Favoris::getItems();

if (is_logged_in() && db_is_available()) {
    $databaseFavorites = get_user_favorites((int) current_user()['id']);

    foreach ($databaseFavorites as $favorite) {
        $favoris[] = [
            'id' => 'db-' . $favorite['id'],
            'db_id' => (int) $favorite['id'],
            'type' => $favorite['item_type'],
            'nom' => $favorite['title'],
            'details' => $favorite['details'],
            'image' => $favorite['image_url'],
            'source_type' => $favorite['source_type'],
            'source_id' => $favorite['source_id'],
        ];
    }

    $favoris = array_values(array_reduce($favoris, function ($carry, $item) {
        $key = ($item['type'] ?? '') . '|' . ($item['nom'] ?? '') . '|' . (int)($item['source_id'] ?? 0);
        $carry[$key] = $item;
        return $carry;
    }, []));
}
?>

<section class="favorites-page">

    <div class="favorites-header">
        <span>Mes favoris</span>
        <h1>Vos idées de voyage sauvegardées</h1>
        <p>
            Retrouvez ici les destinations, logements, activités et circuits que vous avez gardés de côté.
        </p>
    </div>

    <?php if (empty($favoris)) { ?>

        <div class="favorites-empty">
            <h2>Aucun favori pour le moment</h2>
            <p>Ajoutez des éléments à vos favoris pour les retrouver ici.</p>
            <a href="index.php?page=destinations">Explorer les destinations</a>
        </div>

    <?php } else { ?>

        <div class="favorites-grid">

            <?php foreach ($favoris as $item) { ?>

                <article class="favorite-card">

                    <?php if (!empty($item['image'])) { ?>
                        <div
                            class="favorite-img"
                            style="background-image:url('<?php echo htmlspecialchars($item['image']); ?>')">
                        </div>
                    <?php } ?>

                    <div class="favorite-content">
                        <span><?php echo htmlspecialchars($item['type']); ?></span>

                        <h3><?php echo htmlspecialchars($item['nom']); ?></h3>

                        <p><?php echo htmlspecialchars($item['details']); ?></p>

                        <a href="actions/remove_from_favoris.php?id=<?php echo urlencode($item['id']); ?>&db_id=<?php echo urlencode((string)($item['db_id'] ?? '')); ?>&redirect=<?php echo urlencode('index.php?page=favoris'); ?>">
                            Retirer
                        </a>
                    </div>

                </article>

            <?php } ?>

        </div>

    <?php } ?>

</section>
