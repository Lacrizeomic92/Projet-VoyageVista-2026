<section class="home-hero">
    <div class="hero-bg"></div>

    <div class="hero-content">
        <div class="big-logo">
            <img src="assets/images/logo-voyagevista.png" alt="Logo VoyageVista">
        </div>

        <h2>Votre prochain voyage commence ici.</h2>

        <p class="hero-subtitle">
            Créez un séjour complet selon votre budget : destination, transport,
            hébergement et activités dans une seule plateforme.
        </p>

        <form class="search-box" action="index.php" method="GET">
            <input type="hidden" name="page" value="destinations">

            <div class="search-item">
                <label for="destination">📍 Destination</label>
            <input 
                type="text" 
                id="destination" 
                name="destination" 
                placeholder="Ville ou pays"
            >
        </div>

        <div class="search-item">
            <label for="date_depart">📅 Départ</label>
            <input 
                type="date" 
                id="date_depart" 
                name="date_depart"
            >
        </div>

        <div class="search-item">
            <label for="voyageurs">👥 Voyageurs</label>
            <select id="voyageurs" name="voyageurs">
                <option value="1">1 personne</option>
                <option value="2" selected>2 personnes</option>
                <option value="3">3 personnes</option>
                <option value="4">4 personnes</option>
                <option value="5">5 personnes</option>
                <option value="6">6 personnes ou +</option>
            </select>
        </div>

        <button type="submit" class="search-btn">
            Rechercher
        </button>
    </form>
    </div>
</section>

<section class="quick-cards">
    <a href="index.php?page=transports" class="quick-card quick-vols">
        <div class="quick-overlay"></div>
        <div class="quick-content">
            <span>Transports</span>
            <h3>Vols & trajets</h3>
            <p>Comparez les meilleures options pour partir au bon prix.</p>
        </div>
    </a>

    <a href="index.php?page=hebergements" class="quick-card quick-hotels">
        <div class="quick-overlay"></div>
        <div class="quick-content">
            <span>Hébergements</span>
            <h3>Hôtels & logements</h3>
            <p>Trouvez un logement adapté à votre budget.</p>
        </div>
    </a>

    <a href="index.php?page=activites" class="quick-card quick-activities">
        <div class="quick-overlay"></div>
        <div class="quick-content">
            <span>Expériences</span>
            <h3>Activités</h3>
            <p>Ajoutez des souvenirs uniques à votre séjour.</p>
        </div>
    </a>

    <a href="index.php?page=panier" class="quick-card quick-itinerary">
        <div class="quick-overlay"></div>
        <div class="quick-content">
            <span>Organisation</span>
            <h3>Itinéraire</h3>
            <p>Regroupez transport, logement et activités.</p>
        </div>
    </a>
</section>

<section class="section">
    <div class="section-title">
        <p>Destinations populaires</p>
        <h2>Des idées qui donnent envie de partir</h2>
    </div>

    <div class="destination-grid">
        <article class="destination-card bali">
            <div class="badge">Populaire</div>
            <h3>Bali</h3>
            <p>Plages, temples et rizières</p>
            <strong>À partir de 736€</strong>
        </article>

        <article class="destination-card swiss">
            <div class="badge blue-badge">Montagne</div>
            <h3>Interlaken</h3>
            <p>Lacs, montagne et aventure</p>
            <strong>À partir de 429€</strong>
        </article>

        <article class="destination-card dubai">
            <div class="badge purple-badge">City break</div>
            <h3>Dubaï</h3>
            <p>Ville, luxe et désert</p>
            <strong>À partir de 899€</strong>
        </article>

        <article class="destination-card lisbon">
            <div class="badge green-badge">Étudiant</div>
            <h3>Lisbonne</h3>
            <p>Soleil, amis et petit budget</p>
            <strong>À partir de 389€</strong>
        </article>
    </div>
</section>

<section class="section colorful">
    <div class="section-title center">
        <p>Pourquoi VoyageVista ?</p>
        <h2>Une plateforme pensée pour organiser tout le voyage</h2>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon icon-profile"></div>
            <h3>Profil intelligent</h3>
            <p>Un questionnaire permet de proposer des séjours adaptés aux envies, au budget et au style de voyage de chacun.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-budget"></div>
            <h3>Budget maîtrisé</h3>
            <p>Les résultats sont pensés pour comparer facilement les prix et construire un voyage réaliste.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon icon-trip"></div>
            <h3>Expérience complète</h3>
            <p>Destination, transport, hébergement, activités et panier sont réunis dans une seule plateforme.</p>
        </div>
    </div>
</section>