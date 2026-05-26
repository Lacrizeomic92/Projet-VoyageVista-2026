<section class="page activites-page">

    <!-- HERO ACTIVITES -->
    <div class="activites-hero">
        <div class="activites-hero-bg"></div>
        <div class="activites-hero-content">
            <p class="activites-eyebrow">Explorez le monde autrement</p>
            <h1>Activités & Aventures</h1>
            <p class="activites-subtitle">Ski, randonnée, plongée, surf et bien plus — trouvez l'activité qui vous fait vibrer.</p>

            <!-- BARRE DE RECHERCHE -->
            <div class="activites-searchbar">
                <div class="activites-search-icon">🔍</div>
                <input type="text" id="activites-search-input" placeholder="Rechercher une activité, une destination..." autocomplete="off">
                <button class="activites-search-btn" id="activites-search-btn">Rechercher</button>
            </div>
        </div>
    </div>

    <!-- FILTRES RAPIDES -->
    <div class="activites-filters-wrap">
        <div class="activites-filters-inner">

            <div class="activites-filter-group">
                <span class="activites-filter-label">Saison</span>
                <div class="activites-filter-pills" data-filter="saison">
                    <button class="filter-pill active" data-value="all">🌍 Toutes saisons</button>
                    <button class="filter-pill" data-value="hiver">❄️ Hiver</button>
                    <button class="filter-pill" data-value="ete">☀️ Été</button>
                    <button class="filter-pill" data-value="printemps">🌸 Printemps</button>
                    <button class="filter-pill" data-value="automne">🍂 Automne</button>
                </div>
            </div>

            <div class="activites-filter-group">
                <span class="activites-filter-label">Type</span>
                <div class="activites-filter-pills" data-filter="type">
                    <button class="filter-pill active" data-value="all">Tous</button>
                    <button class="filter-pill" data-value="ski">🎿 Ski / Snow</button>
                    <button class="filter-pill" data-value="randonnee">🥾 Randonnée</button>
                    <button class="filter-pill" data-value="plage">🏖️ Plage & Mer</button>
                    <button class="filter-pill" data-value="plongee">🤿 Plongée</button>
                    <button class="filter-pill" data-value="velo">🚴 Vélo</button>
                    <button class="filter-pill" data-value="escalade">🧗 Escalade</button>
                    <button class="filter-pill" data-value="surf">🏄 Surf</button>
                </div>
            </div>

            <div class="activites-filter-group">
                <span class="activites-filter-label">Niveau</span>
                <div class="activites-filter-pills" data-filter="niveau">
                    <button class="filter-pill active" data-value="all">Tous niveaux</button>
                    <button class="filter-pill" data-value="debutant">🟢 Débutant</button>
                    <button class="filter-pill" data-value="intermediaire">🟡 Intermédiaire</button>
                    <button class="filter-pill" data-value="expert">🔴 Expert</button>
                </div>
            </div>

            <button class="activites-reset-btn" id="activites-reset">Réinitialiser</button>
        </div>
    </div>

    <!-- COMPTEUR RESULTATS -->
    <div class="activites-result-bar">
        <strong id="activites-count">20 activités disponibles</strong>
        <span id="activites-count-sub">Affichant toutes les activités</span>
    </div>

    <!-- GRILLE DES ACTIVITES -->
    <div class="activites-grid" id="activites-grid">

        <!-- SKI / SNOWBOARD -->

        <article class="activite-card"
            data-saison="hiver"
            data-type="ski"
            data-niveau="debutant intermediaire expert"
            data-search="ski chamonix alpes france neige montagne">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1551524559-8af4e6624178?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--blue">🎿 Ski</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Chamonix Mont-Blanc</h3>
                    <span class="activite-loc">📍 Alpes, France</span>
                </div>
                <p>Descentes mythiques, domaine skiable exceptionnel et panorama sur le Mont-Blanc.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--winter">❄️ Hiver</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 85€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="hiver"
            data-type="ski"
            data-niveau="debutant intermediaire"
            data-search="ski les deux alpes station neige debutant famille">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1548777123-e216912df7d8?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--blue">🎿 Ski</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Les Deux Alpes</h3>
                    <span class="activite-loc">📍 Isère, France</span>
                </div>
                <p>Station idéale pour familles et débutants, avec école de ski et pistes variées.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--winter">❄️ Hiver</span>
                    <span class="meta-pill meta-pill--level">🟢 Débutant</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 65€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="hiver"
            data-type="ski"
            data-niveau="intermediaire expert"
            data-search="ski zermatt suisse matterhorn alpes suisses snowboard">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--blue">🎿 Ski</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Zermatt</h3>
                    <span class="activite-loc">📍 Valais, Suisse</span>
                </div>
                <p>Au pied du Cervin, Zermatt offre un domaine skiable glacier ouvert toute l'année.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--winter">❄️ Hiver</span>
                    <span class="meta-pill meta-pill--level">🟡 Intermédiaire</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 110€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="hiver"
            data-type="ski"
            data-niveau="intermediaire expert"
            data-search="ski val thorens 3 vallees snowboard altitude neige">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1488591216678-8f0e0f4e6be3?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--purple">🏂 Snowboard</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Val Thorens — 3 Vallées</h3>
                    <span class="activite-loc">📍 Savoie, France</span>
                </div>
                <p>Le plus grand domaine skiable du monde avec snowpark et freestyle pour les riders.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--winter">❄️ Hiver</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 78€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="hiver"
            data-type="ski"
            data-niveau="debutant intermediaire expert"
            data-search="ski banff canada rocheuses canadiennes hors piste neige poudreuse">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1476842634003-7dcca8f832de?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--blue">🎿 Ski</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Banff — Lake Louise</h3>
                    <span class="activite-loc">📍 Alberta, Canada</span>
                </div>
                <p>Ski dans les Rocheuses canadiennes : poudreuse légendaire et paysages à couper le souffle.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--winter">❄️ Hiver</span>
                    <span class="meta-pill meta-pill--level">🟡 Intermédiaire</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 135€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <!-- RANDONNÉE -->

        <article class="activite-card"
            data-saison="ete printemps automne"
            data-type="randonnee"
            data-niveau="intermediaire expert"
            data-search="randonnee tour du mont blanc tmb alpes france italie suisse trekking">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--green">🥾 Randonnée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Tour du Mont-Blanc</h3>
                    <span class="activite-loc">📍 France / Italie / Suisse</span>
                </div>
                <p>170 km autour du toit de l'Europe, à travers 3 pays en 10 jours de trekking mythique.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 680€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="ete printemps"
            data-type="randonnee"
            data-niveau="debutant intermediaire"
            data-search="randonnee corse gr20 montagne nature paysage canyoning">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--green">🥾 Randonnée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>GR20 — Corse</h3>
                    <span class="activite-loc">📍 Haute-Corse, France</span>
                </div>
                <p>La grande traversée de la Corse sauvage entre forêts, cols et lacs glaciaires.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🟡 Intermédiaire</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 420€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="ete printemps automne"
            data-type="randonnee"
            data-niveau="debutant intermediaire"
            data-search="randonnee plitvice croatie lacs cascades nature parc national">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--green">🥾 Randonnée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Lacs de Plitvice</h3>
                    <span class="activite-loc">📍 Croatie</span>
                </div>
                <p>Sentiers enchanteurs entre lacs turquoise et cascades dans un parc naturel classé UNESCO.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🟢 Débutant</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 290€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="ete automne"
            data-type="randonnee"
            data-niveau="intermediaire expert"
            data-search="randonnee nepal himalaya everest base camp trekking altitude">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--green">🥾 Randonnée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Everest Base Camp</h3>
                    <span class="activite-loc">📍 Himalaya, Népal</span>
                </div>
                <p>Le trek ultime : rejoindre le camp de base de l'Everest à 5 364 m d'altitude en 14 jours.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--autumn">🍂 Automne</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 1 290€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="printemps ete"
            data-type="randonnee"
            data-niveau="debutant"
            data-search="randonnee camino de santiago chemin saint jacques pelerinage espagne">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1561361058-c24cecae35ca?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--green">🥾 Randonnée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Camino de Santiago</h3>
                    <span class="activite-loc">📍 Espagne</span>
                </div>
                <p>780 km de pèlerinage à travers la campagne espagnole jusqu'à la cathédrale de Compostelle.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--spring">🌸 Printemps</span>
                    <span class="meta-pill meta-pill--level">🟢 Débutant</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 590€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <!-- PLAGE & MER -->

        <article class="activite-card"
            data-saison="ete"
            data-type="plage surf"
            data-niveau="debutant intermediaire"
            data-search="surf biarritz atlantique vagues ecole surf cours france basque">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--orange">🏄 Surf</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Surf à Biarritz</h3>
                    <span class="activite-loc">📍 Pays Basque, France</span>
                </div>
                <p>La capitale européenne du surf : cours pour débutants et vagues mythiques pour les confirmés.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🟢 Débutant</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 55€/cours</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="ete"
            data-type="plage plongee"
            data-niveau="intermediaire expert"
            data-search="plongee maldives recif corail poissons tropicaux ocean indien">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--cyan">🤿 Plongée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Plongée aux Maldives</h3>
                    <span class="activite-loc">📍 Maldives</span>
                </div>
                <p>Récifs coralliens, raies manta et requins-baleines dans les eaux cristallines de l'atoll.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🟡 Intermédiaire</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 120€/plongée</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="ete printemps"
            data-type="plage plongee"
            data-niveau="debutant intermediaire"
            data-search="plongee snorkeling red sea mer rouge egypte dahab hurghada recif">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1437622368342-7a3d73a34c8f?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--cyan">🤿 Plongée</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Mer Rouge — Dahab</h3>
                    <span class="activite-loc">📍 Égypte</span>
                </div>
                <p>Fonds marins parmi les plus riches au monde — idéal pour débuter et progresser rapidement.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--spring">🌸 Printemps</span>
                    <span class="meta-pill meta-pill--level">🟢 Débutant</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 45€/plongée</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <!-- VÉLO -->

        <article class="activite-card"
            data-saison="ete printemps automne"
            data-type="velo"
            data-niveau="debutant intermediaire"
            data-search="velo loire a velo val de loire france chateau cyclisme route">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--yellow">🚴 Vélo</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>La Loire à Vélo</h3>
                    <span class="activite-loc">📍 Val de Loire, France</span>
                </div>
                <p>900 km le long de la Loire, entre châteaux, vignobles et villages pittoresques à vélo.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--spring">🌸 Printemps</span>
                    <span class="meta-pill meta-pill--level">🟢 Débutant</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 340€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="ete"
            data-type="velo"
            data-niveau="expert"
            data-search="velo alpes cols montagne cyclisme tour de france hauteur denivele">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1571068316344-75bc76f77890?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--yellow">🚴 Vélo</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Cols des Alpes à Vélo</h3>
                    <span class="activite-loc">📍 Alpes, France</span>
                </div>
                <p>Galibier, Alpe d'Huez, Izoard — les grands cols mythiques du Tour de France.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 490€</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <!-- ESCALADE -->

        <article class="activite-card"
            data-saison="printemps ete automne"
            data-type="escalade"
            data-niveau="intermediaire expert"
            data-search="escalade fontainebleau bleau bouldering bloc foret france">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1516592673884-4a382d1124c2?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--pink">🧗 Escalade</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Fontainebleau — Bleau</h3>
                    <span class="activite-loc">📍 Île-de-France, France</span>
                </div>
                <p>Le paradis mondial du bloc : des milliers de problèmes d'escalade en forêt pour tous niveaux.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--autumn">🍂 Automne</span>
                    <span class="meta-pill meta-pill--level">🟡 Intermédiaire</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 60€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <article class="activite-card"
            data-saison="printemps automne"
            data-type="escalade"
            data-niveau="expert"
            data-search="escalade dolomites italie via ferrata grande voie falaise">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--pink">🧗 Escalade</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>Dolomites — Via Ferrata</h3>
                    <span class="activite-loc">📍 Tyrol du Sud, Italie</span>
                </div>
                <p>Via ferratas spectaculaires dans les Dolomites avec panoramas à 3 000 m d'altitude.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--autumn">🍂 Automne</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 95€/jour</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

        <!-- SURF -->

        <article class="activite-card"
            data-saison="ete"
            data-type="surf"
            data-niveau="intermediaire expert"
            data-search="surf hawaii pipeline north shore vagues tubulaires ocean pacifique">
            <div class="activite-img" style="background-image:url('https://images.unsplash.com/photo-1455264745730-cb3b76250027?auto=format&fit=crop&w=900&q=90')"></div>
            <div class="activite-tag activite-tag--orange">🏄 Surf</div>
            <div class="activite-body">
                <div class="activite-top">
                    <h3>North Shore — Hawaï</h3>
                    <span class="activite-loc">📍 Oahu, Hawaï</span>
                </div>
                <p>Les vagues les plus puissantes du monde : Pipeline, Sunset Beach — pour les surfeurs confirmés.</p>
                <div class="activite-meta">
                    <span class="meta-pill meta-pill--summer">☀️ Été</span>
                    <span class="meta-pill meta-pill--level">🔴 Expert</span>
                </div>
                <div class="activite-footer">
                    <strong>À partir de 180€/semaine</strong>
                    <button class="activite-btn">Voir l'offre</button>
                </div>
            </div>
        </article>

    </div>

    <!-- MESSAGE AUCUN RESULTAT -->
    <div class="activites-no-result" id="activites-no-result">
        <div class="no-result-icon">🔍</div>
        <h2>Aucune activité trouvée</h2>
        <p>Essayez de modifier vos filtres ou votre recherche.</p>
        <button class="activite-btn" id="activites-reset-2">Voir toutes les activités</button>
    </div>

</section>
