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
