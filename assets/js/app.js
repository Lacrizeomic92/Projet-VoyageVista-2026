document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.querySelector(".menu-toggle");
    const links = document.querySelector(".nav-links");

    if (toggle && links) {
        toggle.addEventListener("click", () => {
            links.classList.toggle("open");
        });
    }

    const choiceGroups = document.querySelectorAll(".choice-group");
    const applyButton = document.getElementById("apply-destination-filters");
    const resetButton = document.getElementById("reset-destination-filters");

    const destinationCards = document.querySelectorAll(".destination-results .destination-card");
    const resultCount = document.getElementById("destination-result-count");
    const resultText = document.getElementById("destination-result-text");
    const noDestinationMessage = document.getElementById("no-destination-message");

    const selectedFilters = {
        type: "all",
        duration: "all",
        budget: "all",
        public: "all"
    };

    choiceGroups.forEach((group) => {
        const filterName = group.dataset.filter;
        const buttons = group.querySelectorAll(".choice-btn");

        buttons.forEach((button) => {
            button.addEventListener("click", () => {
                buttons.forEach((btn) => btn.classList.remove("active"));
                button.classList.add("active");

                selectedFilters[filterName] = button.dataset.value;
            });
        });
    });

    function matchCriteria(cardValue, selectedValue) {
        if (selectedValue === "all") {
            return true;
        }

        return cardValue.split(" ").includes(selectedValue);
    }

    function updateDestinations() {
        if (!destinationCards.length) {
            return;
        }

        let visibleCount = 0;

        destinationCards.forEach((card) => {
            const matchesType = matchCriteria(card.dataset.type, selectedFilters.type);
            const matchesDuration = matchCriteria(card.dataset.duration, selectedFilters.duration);
            const matchesBudget = matchCriteria(card.dataset.budget, selectedFilters.budget);
            const matchesPublic = matchCriteria(card.dataset.public, selectedFilters.public);

            const isVisible = matchesType && matchesDuration && matchesBudget && matchesPublic;

            card.style.display = isVisible ? "flex" : "none";

            if (isVisible) {
                visibleCount++;
            }
        });

        if (resultCount) {
            resultCount.textContent = visibleCount + (visibleCount > 1 ? " destinations recommandées" : " destination recommandée");
        }

        if (resultText) {
            resultText.textContent = visibleCount === 0
                ? "Aucune destination ne correspond exactement à ces critères."
                : "Voici les destinations les plus cohérentes avec votre recherche.";
        }

        if (noDestinationMessage) {
            noDestinationMessage.style.display = visibleCount === 0 ? "block" : "none";
        }
    }

    if (applyButton) {
        applyButton.addEventListener("click", updateDestinations);
    }

    if (resetButton) {
        resetButton.addEventListener("click", () => {
            Object.keys(selectedFilters).forEach((key) => {
                selectedFilters[key] = "all";
            });

            choiceGroups.forEach((group) => {
                const buttons = group.querySelectorAll(".choice-btn");

                buttons.forEach((button) => {
                    button.classList.remove("active");

                    if (button.dataset.value === "all") {
                        button.classList.add("active");
                    }
                });
            });

            updateDestinations();
        });
    }

});
<<<<<<< HEAD
=======

/* ========================================= */
/* ACTIVITÉS — Filtres + Recherche */
/* ========================================= */

(function () {

    const cards = document.querySelectorAll(".activites-grid .activite-card");
    if (!cards.length) return;

    const countEl   = document.getElementById("activites-count");
    const subEl     = document.getElementById("activites-count-sub");
    const noResult  = document.getElementById("activites-no-result");
    const searchInput = document.getElementById("activites-search-input");
    const searchBtn   = document.getElementById("activites-search-btn");
    const resetBtn    = document.getElementById("activites-reset");
    const resetBtn2   = document.getElementById("activites-reset-2");

    const selected = { saison: "all", type: "all", niveau: "all" };
    let searchQuery = "";

    /* Gestion des pills */
    document.querySelectorAll(".activites-filter-pills").forEach(function (group) {
        const filterName = group.dataset.filter;
        group.querySelectorAll(".filter-pill").forEach(function (pill) {
            pill.addEventListener("click", function () {
                group.querySelectorAll(".filter-pill").forEach(function (p) { p.classList.remove("active"); });
                pill.classList.add("active");
                selected[filterName] = pill.dataset.value;
                applyFilters();
            });
        });
    });

    /* Recherche */
    function doSearch() {
        searchQuery = searchInput ? searchInput.value.trim().toLowerCase() : "";
        applyFilters();
    }

    if (searchBtn)   searchBtn.addEventListener("click", doSearch);
    if (searchInput) searchInput.addEventListener("keydown", function (e) { if (e.key === "Enter") doSearch(); });

    /* Reset */
    function doReset() {
        selected.saison = "all";
        selected.type   = "all";
        selected.niveau = "all";
        searchQuery = "";
        if (searchInput) searchInput.value = "";

        document.querySelectorAll(".activites-filter-pills").forEach(function (group) {
            group.querySelectorAll(".filter-pill").forEach(function (p) {
                p.classList.toggle("active", p.dataset.value === "all");
            });
        });

        applyFilters();
    }

    if (resetBtn)  resetBtn.addEventListener("click",  doReset);
    if (resetBtn2) resetBtn2.addEventListener("click", doReset);

    function match(cardValue, selectedValue) {
        if (!selectedValue || selectedValue === "all") return true;
        return (cardValue || "").split(" ").includes(selectedValue);
    }

    function applyFilters() {
        let visible = 0;

        cards.forEach(function (card) {
            const ok =
                match(card.dataset.saison,  selected.saison) &&
                match(card.dataset.type,    selected.type)   &&
                match(card.dataset.niveau,  selected.niveau) &&
                (!searchQuery || (card.dataset.search || "").toLowerCase().includes(searchQuery) ||
                    (card.querySelector("h3") || {}).textContent.toLowerCase().includes(searchQuery) ||
                    (card.querySelector(".activite-loc") || {}).textContent.toLowerCase().includes(searchQuery));

            card.style.display = ok ? "" : "none";
            if (ok) visible++;
        });

        if (countEl) countEl.textContent = visible + (visible > 1 ? " activités disponibles" : " activité disponible");
        if (subEl) {
            const parts = [];
            if (selected.saison !== "all") parts.push(selected.saison);
            if (selected.type   !== "all") parts.push(selected.type);
            if (selected.niveau !== "all") parts.push(selected.niveau);
            if (searchQuery)               parts.push('"' + searchQuery + '"');
            subEl.textContent = parts.length ? "Filtrée par : " + parts.join(", ") : "Affichant toutes les activités";
        }
        if (noResult) noResult.style.display = visible === 0 ? "block" : "none";
    }

})();
>>>>>>> bc426346a05c330a826216313bd63e183d8fc5ca
