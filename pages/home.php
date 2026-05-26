<?php
$featuredDestinations = [
    [
        "city" => "Lisbonne",
        "country" => "Portugal",
        "price" => 389,
        "score" => 9.4,
        "daily" => 42,
        "tag" => "TOP ÉTUDIANT",
        "image" => "https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=1200&q=90"
    ],
    [
        "city" => "Barcelone",
        "country" => "Espagne",
        "price" => 459,
        "score" => 8.9,
        "daily" => 48,
        "tag" => "ENTRE AMIS",
        "image" => "https://images.unsplash.com/photo-1583422409516-2895a77efded?auto=format&fit=crop&w=1200&q=90"
    ],
    [
        "city" => "Budapest",
        "country" => "Hongrie",
        "price" => 329,
        "score" => 9.5,
        "daily" => 34,
        "tag" => "ULTRA BUDGET",
        "image" => "https://images.unsplash.com/photo-1541849546-216549ae216d?auto=format&fit=crop&w=1200&q=90"
    ],
    [
        "city" => "Prague",
        "country" => "République tchèque",
        "price" => 349,
        "score" => 9.1,
        "daily" => 36,
        "tag" => "CITY TRIP",
        "image" => "https://images.unsplash.com/photo-1519677100203-a0e668c92439?auto=format&fit=crop&w=1200&q=90"
    ]
];
?>

<section class="vv-hero">
    <div class="vv-hero-left">
        <div class="vv-badge-logo">

            <img
            src="assets/images/logo-voyagevista.png"
            alt="VoyageVista"
            >

        <span>
            Student Budget
        </span>

    </div>

        <h1>Voyagez plus,<br>dépensez moins.</h1>

        <p>
            Comparez les meilleures destinations étudiantes selon votre budget,
            votre groupe et votre style de voyage.
        </p>

        <form action="index.php" method="GET" class="vv-search">
            <input type="hidden" name="page" value="circuit">

            <input
                type="text"
                name="depart"
                placeholder="Ville de départ"
                required
            >

        <input
            type="text"
            name="destination"
            placeholder="Destination ou pays"
            required
        >

        <input
            type="date"
            name="date_depart"
            required
        >

        <input
            type="date"
            name="date_retour"
            required
        >

        <select name="voyageurs" required>
            <option value="1">1 voyageur</option>
            <option value="2">2 voyageurs</option>
            <option value="3">3 voyageurs</option>
            <option value="4">4 voyageurs</option>
            <option value="5">5+ voyageurs</option>
        </select>

        <select name="budget" required>
            <option value="">Budget max</option>
            <option value="300">Moins de 300€</option>
            <option value="500">Moins de 500€</option>
            <option value="800">Moins de 800€</option>
            <option value="1200">Moins de 1200€</option>
        </select>

        <button type="submit">
            Créer mon circuit
        </button>
        </form>
    </div>
</section>

<section class="vv-categories">

    <a href="index.php?page=offres&budget=500" class="vv-category-card">
        <div class="vv-cat-icon"><i data-lucide="piggy-bank"></i></div>
        <div>
            <h3>Moins de 500€</h3>
            <p>Des séjours réalistes avec transport, logement et activités inclus.</p>
        </div>
    </a>

    <a href="index.php?page=offres&student_filter[]=Groupe" class="vv-category-card">
        <div class="vv-cat-icon"><i data-lucide="users"></i></div>
        <div>
            <h3>Voyages entre amis</h3>
            <p>Prix par personne, logements adaptés et activités de groupe.</p>
        </div>
    </a>

    <a href="index.php?page=offres&student_filter[]=Sans voiture" class="vv-category-card">
        <div class="vv-cat-icon"><i data-lucide="train-front"></i></div>
        <div>
            <h3>Sans voiture</h3>
            <p>Destinations accessibles en train, bus et transports locaux.</p>
        </div>
    </a>

    <a href="index.php?page=offres&sort=score" class="vv-category-card">
        <div class="vv-cat-icon"><i data-lucide="graduation-cap"></i></div>
        <div>
            <h3>Bons plans étudiants</h3>
            <p>Les meilleures offres classées par score étudiant.</p>
        </div>
    </a>

</section>

<section class="vv-popular">
    <div class="vv-section-title">
        <span>DESTINATIONS POPULAIRES</span>
        <h2>Des idées qui donnent envie de partir</h2>
    </div>

    <div class="vv-destination-grid">
        <?php foreach($featuredDestinations as $destination){ ?>
            <article class="vv-destination-card">
                <div class="vv-destination-img" style="background-image:url('<?php echo $destination['image']; ?>')">
                    <span><?php echo $destination['tag']; ?></span>
                </div>

                <div class="vv-destination-content">
                    <h3>
                        <?php echo $destination['city']; ?>,
                        <?php echo $destination['country']; ?>
                    </h3>

                    <div class="vv-destination-row">
                        <p>⭐ <?php echo $destination['score']; ?>/10</p>
                        <strong>Dès <?php echo $destination['price']; ?>€</strong>
                    </div>

                    <p class="vv-daily">💸 Budget moyen : <?php echo $destination['daily']; ?>€/jour</p>

                    <a href="index.php?page=offres&destination=<?php echo urlencode($destination['city']); ?>">
                        Voir les offres
                    </a>
                </div>
            </article>
        <?php } ?>
    </div>
</section>

<section class="vv-advantages">

    <div class="vv-adv-card">
        <div class="vv-adv-icon"><i data-lucide="graduation-cap"></i></div>
        <h3>Pensé pour les étudiants</h3>
        <p>Prix accessibles et bons plans exclusifs.</p>
    </div>

    <div class="vv-adv-card">
        <div class="vv-adv-icon"><i data-lucide="users"></i></div>
        <h3>Parfait pour les groupes</h3>
        <p>Économisez encore plus en voyageant à plusieurs.</p>
    </div>

    <div class="vv-adv-card">
        <div class="vv-adv-icon"><i data-lucide="shield-check"></i></div>
        <h3>Réservation sécurisée</h3>
        <p>Paiement simulé sécurisé et assistance dédiée.</p>
    </div>

    <div class="vv-adv-card">
        <div class="vv-adv-icon"><i data-lucide="message-circle"></i></div>
        <h3>Support étudiant</h3>
        <p>Une équipe disponible avant, pendant et après votre voyage.</p>
    </div>

</section>
