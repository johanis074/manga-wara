document.addEventListener("turbo:load", function () {
    const dropdownTrigger = document.querySelector(".dropdown-toggle");
    const dropdownMenu = document.querySelector(".dropdown-menu");
    const dropdownItems = dropdownMenu.querySelectorAll("a");

    // Assurez-vous que le bouton du menu est focusable et qu'il a les attributs d'accessibilité
    dropdownTrigger.setAttribute('tabindex', '0');
    dropdownTrigger.setAttribute('aria-expanded', 'false');
    
    // Gérer l'ouverture/fermeture au clic
    dropdownTrigger.addEventListener("click", function (event) {
        event.preventDefault();
        toggleDropdownMenu();
    });

    // Gérer l'ouverture/fermeture avec les touches "Entrée" ou "Espace"
    dropdownTrigger.addEventListener("keydown", function (event) {
        if (event.key === "Enter" || event.key === " ") { // "Entrée" ou "Espace"
            event.preventDefault();
            toggleDropdownMenu();
        }
    });

    // Fermer le menu si l'utilisateur clique en dehors
    document.addEventListener("click", function (event) {
        if (!dropdownTrigger.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show");
            dropdownTrigger.setAttribute('aria-expanded', 'false');
        }
    });

    // Fonction pour ouvrir/fermer le menu
    function toggleDropdownMenu() {
        const isOpen = dropdownTrigger.getAttribute('aria-expanded') === 'true';
        dropdownTrigger.setAttribute('aria-expanded', !isOpen);
        dropdownMenu.classList.toggle('show', !isOpen);

        // Focus sur le premier élément si le menu est ouvert
        if (!isOpen) {
            dropdownItems[0].focus(); // Focus sur le premier élément du menu
        }
    }

    // Naviguer dans les éléments du menu avec la touche Tab
    dropdownMenu.addEventListener("keydown", function (event) {
        const firstItem = dropdownItems[0];
        const lastItem = dropdownItems[dropdownItems.length - 1];

        if (event.key === "Tab") {
            if (event.shiftKey && document.activeElement === firstItem) {
                // Si on est sur le premier élément et qu'on appuie sur Shift+Tab, focus sur le trigger
                dropdownTrigger.focus();
                event.preventDefault();
            } else if (!event.shiftKey && document.activeElement === lastItem) {
                // Si on est sur le dernier élément et qu'on appuie sur Tab, focus sur le trigger
                dropdownTrigger.focus();
                event.preventDefault();
            }
        }
    });
});
