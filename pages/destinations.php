<section class="page destinations-page">

    <div class="section-title center">
        <p>Assistant destination</p>
        <h1>Trouvez la destination adaptée à votre séjour</h1>

        <span class="destinations-subtitle">
            Répondez à quelques critères simples : VoyageVista vous recommande automatiquement les destinations les plus cohérentes.
        </span>
    </div>

    <section class="destination-assistant">

        <div class="assistant-card">

            <h2>Que recherchez-vous ?</h2>

            <div class="choice-section">
                <span class="choice-title">Ambiance</span>

                <div class="choice-group" data-filter="type">
                    <button type="button" class="choice-btn active" data-value="all">Toutes</button>
                    <button type="button" class="choice-btn" data-value="plage">Plage</button>
                    <button type="button" class="choice-btn" data-value="montagne">Montagne</button>
                    <button type="button" class="choice-btn" data-value="ville">Ville</button>
                    <button type="button" class="choice-btn" data-value="nature">Nature</button>
                    <button type="button" class="choice-btn" data-value="culture">Culture</button>
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
                    <button type="button" class="choice-btn" data-value="jeunesse">Séjour jeunesse</button>
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
                8 destinations disponibles
            </strong>

            <p id="destination-result-text">
                Choisissez vos critères pour obtenir une sélection personnalisée.
            </p>
        </div>

    </section>

    <div class="destination-results">

        <article class="destination-card"
            data-type="plage culture"
            data-duration="court long"
            data-budget="economique"
            data-public="etudiant jeunesse">

            <div class="destination-image"
                style="background-image:url('https://images.unsplash.com/photo-1543783207-ec64e4d95325?q=80&w=1200&auto=format&fit=crop');">
            </div>

            <div class="destination-content">

                <span>Étudiant</span>

                <h3>Barcelone</h3>

                <p>
                    Plage, culture et soirées étudiantes pour un séjour accessible et animé.
                </p>

                <strong>À partir de 189€</strong>

                <a href="index.php?page=circuit">
                    Voir le circuit
                </a>

            </div>

        </article>

        <article class="destination-card"
            data-type="plage nature"
            data-duration="court long"
            data-budget="economique"
            data-public="etudiant famille">

            <div class="destination-image"
                style="background-image:url('https://images.unsplash.com/photo-1516483638261-f4dbaf036963?q=80&w=1200&auto=format&fit=crop');">
            </div>

            <div class="destination-content">

                <span>Petit budget</span>

                <h3>Lisbonne</h3>

                <p>
                    Soleil, surf et ambiance chill avec des logements parfaits pour étudiants.
                </p>

                <strong>À partir de 159€</strong>

                <a href="index.php?page=circuit">
                    Voir le circuit
                </a>

            </div>

        </article>

        <article class="destination-card"
            data-type="plage nature"
            data-duration="long"
            data-budget="economique"
            data-public="etudiant jeunesse">

            <div class="destination-image"
                style="background-image:url('https://images.unsplash.com/photo-1521295121783-8a321d551ad2?q=80&w=1200&auto=format&fit=crop');">
            </div>

            <div class="destination-content">

                <span>Nouvelle tendance</span>

                <h3>Albanie</h3>

                <p>
                    Riviera turquoise, restaurants pas chers et road trip parfait entre amis.
                </p>

                <strong>À partir de 209€</strong>

                <a href="index.php?page=circuit">
                    Voir le circuit
                </a>

            </div>

        </article>

        <article class="destination-card"
            data-type="culture ville"
            data-duration="court"
            data-budget="moyen"
            data-public="etudiant famille">

            <div class="destination-image"
                style="background-image:url('https://images.unsplash.com/photo-1525874684015-58379d421a52?q=80&w=1200&auto=format&fit=crop');">
            </div>

            <div class="destination-content">

                <span>Culture</span>

                <h3>Rome</h3>

                <p>
                    Ville historique idéale pour un séjour culturel avec budget raisonnable.
                </p>

                <strong>À partir de 240€</strong>

                <a href="index.php?page=circuit">
                    Voir le circuit
                </a>

            </div>

        </article>

        <article class="destination-card"
            data-type="plage culture"
            data-duration="court long"
            data-budget="economique"
            data-public="etudiant jeunesse famille">

            <div class="destination-image"
                style="background-image:url('https://images.unsplash.com/photo-1533105079780-92b9be482077?q=80&w=1200&auto=format&fit=crop');">
            </div>

            <div class="destination-content">

                <span>Soleil</span>

                <h3>Marrakech</h3>

                <p>
                    Rooftops, souks et hébergements très abordables pour un séjour dépaysant.
                </p>

                <strong>À partir de 220€</strong>

                <a href="index.php?page=circuit">
                    Voir le circuit
                </a>

            </div>

        </article>

        <article class="destination-card"
            data-type="plage nature"
            data-duration="long"
            data-budget="premium"
            data-public="famille etudiant">

            <div class="destination-image"
                style="background-image:url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop');">
            </div>

            <div class="destination-content">

                <span>Premium</span>

                <h3>Grèce</h3>

                <p>
                    Îles grecques, ferrys et coucher de soleil pour un vrai circuit méditerranéen.
                </p>

                <strong>À partir de 399€</strong>

                <a href="index.php?page=circuit">
                    Voir le circuit
                </a>

            </div>

        </article>

    </div>

</section>
