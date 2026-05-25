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
            <strong id="destination-result-count">16 destinations disponibles</strong>
            <p id="destination-result-text">
                Choisissez vos critères pour obtenir une sélection personnalisée.
            </p>
        </div>
    </section>

    <section class="destination-grid destination-results">

        <article class="destination-card bali"
            data-type="plage nature culture"
            data-duration="long"
            data-budget="premium"
            data-public="etudiant famille">
            <div class="badge">Populaire</div>
            <h3>Bali</h3>
            <p>Plages, temples et rizières pour un séjour dépaysant et complet.</p>
            <strong>À partir de 736€</strong>
        </article>

        <article class="destination-card interlaken"
            data-type="montagne nature"
            data-duration="court long"
            data-budget="moyen premium"
            data-public="etudiant jeunesse famille">
            <div class="badge blue-badge">Montagne</div>
            <h3>Interlaken</h3>
            <p>Lacs, montagne et aventure pour les groupes sportifs.</p>
            <strong>À partir de 429€</strong>
        </article>

        <article class="destination-card dubai"
            data-type="ville culture"
            data-duration="court"
            data-budget="premium"
            data-public="etudiant famille">
            <div class="badge purple-badge">City break</div>
            <h3>Dubaï</h3>
            <p>Ville, luxe et désert pour un séjour intense et moderne.</p>
            <strong>À partir de 899€</strong>
        </article>

        <article class="destination-card lisbon"
            data-type="plage ville culture"
            data-duration="court"
            data-budget="economique"
            data-public="etudiant famille">
            <div class="badge green-badge">Étudiant</div>
            <h3>Lisbonne</h3>
            <p>Soleil, amis et petit budget : parfait pour partir en groupe.</p>
            <strong>À partir de 389€</strong>
        </article>

        <article class="destination-card barcelona"
            data-type="plage ville culture"
            data-duration="court"
            data-budget="economique moyen"
            data-public="etudiant famille">
            <div class="badge green-badge">Étudiant</div>
            <h3>Barcelone</h3>
            <p>Plage, culture et sorties : idéal pour un court séjour étudiant.</p>
            <strong>À partir de 450€</strong>
        </article>

        <article class="destination-card annecy"
            data-type="montagne nature"
            data-duration="court long"
            data-budget="moyen"
            data-public="jeunesse famille etudiant">
            <div class="badge blue-badge">Nature</div>
            <h3>Annecy</h3>
            <p>Lac, montagne et activités sportives pour groupes et séjours jeunesse.</p>
            <strong>À partir de 620€</strong>
        </article>

        <article class="destination-card chamonix"
            data-type="montagne nature"
            data-duration="long"
            data-budget="moyen premium"
            data-public="jeunesse famille">
            <div class="badge purple-badge">Long séjour</div>
            <h3>Chamonix</h3>
            <p>Randonnées, encadrement et grands espaces pour un séjour organisé.</p>
            <strong>À partir de 790€</strong>
        </article>

        <article class="destination-card berlin"
            data-type="ville culture"
            data-duration="court"
            data-budget="economique moyen"
            data-public="etudiant">
            <div class="badge">City break</div>
            <h3>Berlin</h3>
            <p>Culture, histoire et vie nocturne : très solide pour un groupe étudiant.</p>
            <strong>À partir de 430€</strong>
        </article>

        <article class="destination-card montreal"
            data-type="ville nature culture"
            data-duration="long"
            data-budget="premium"
            data-public="etudiant jeunesse">
            <div class="badge green-badge">Linguistique</div>
            <h3>Montréal</h3>
            <p>Un séjour long mêlant découverte, langue et activités urbaines.</p>
            <strong>À partir de 1450€</strong>
        </article>

        <article class="destination-card pyrenees"
            data-type="montagne nature"
            data-duration="long"
            data-budget="economique moyen"
            data-public="jeunesse famille">
            <div class="badge blue-badge">Colonie</div>
            <h3>Pyrénées</h3>
            <p>Une destination adaptée aux colonies, groupes et activités sportives.</p>
            <strong>À partir de 680€</strong>
        </article>

        <article class="destination-card tokyo"
            data-type="ville culture"
            data-duration="long"
            data-budget="premium"
            data-public="etudiant famille">
            <div class="badge purple-badge">Premium</div>
            <h3>Tokyo</h3>
            <p>Un séjour culturel long, dépaysant et très marquant.</p>
            <strong>À partir de 1800€</strong>
        </article>

        <article class="destination-card nice"
            data-type="plage ville"
            data-duration="court"
            data-budget="moyen"
            data-public="etudiant famille">
            <div class="badge green-badge">Plage</div>
            <h3>Nice</h3>
            <p>Méditerranée, soleil et accès simple pour un séjour rapide.</p>
            <strong>À partir de 360€</strong>
        </article>

        <article class="destination-card amsterdam"
            data-type="ville culture"
            data-duration="court"
            data-budget="moyen"
            data-public="etudiant">
            <div class="badge">Culture</div>
            <h3>Amsterdam</h3>
            <p>Musées, canaux et ambiance jeune pour un city trip européen.</p>
            <strong>À partir de 410€</strong>
        </article>

        <article class="destination-card corse"
            data-type="plage nature"
            data-duration="long"
            data-budget="moyen premium"
            data-public="jeunesse famille etudiant">
            <div class="badge blue-badge">Nature</div>
            <h3>Corse</h3>
            <p>Mer, randonnée et activités nautiques pour un séjour de groupe.</p>
            <strong>À partir de 720€</strong>
        </article>

        <article class="destination-card londres"
            data-type="ville culture"
            data-duration="court"
            data-budget="moyen premium"
            data-public="etudiant famille">
            <div class="badge purple-badge">Culture</div>
            <h3>Londres</h3>
            <p>Une destination urbaine parfaite pour un séjour culturel ou linguistique.</p>
            <strong>À partir de 520€</strong>
        </article>

        <article class="destination-card grece"
            data-type="plage culture"
            data-duration="long"
            data-budget="moyen"
            data-public="etudiant famille">
            <div class="badge green-badge">Été</div>
            <h3>Grèce</h3>
            <p>Îles, mer claire et patrimoine pour un séjour équilibré.</p>
            <strong>À partir de 690€</strong>
        </article>

    </section>

    <div class="no-destination-message" id="no-destination-message">
        <h2>Aucune destination trouvée</h2>
        <p>Essayez de modifier vos critères pour obtenir plus de résultats.</p>
    </div>

</section>
