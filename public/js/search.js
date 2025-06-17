
document.addEventListener("turbo:load", function () {
    const input = document.getElementById("search-input");
    const resultsContainer = document.getElementById("search-results");
    const booksContainer = document.querySelector(".cards-container");

    // Lorsqu'on tape dans la barre de recherche
    input.addEventListener("input", function () {
        const query = input.value.trim();

        if (query.length === 0) {
            resultsContainer.innerHTML = "";
            return;
        }

        // Requête pour récupérer les résultats en direct
        fetch(`/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = "";
                booksContainer.innerHTML = ""; // Vider la liste avant d'afficher les résultats

                if (data.length === 0) {
                    booksContainer.innerHTML = "<p>Aucun résultat trouvé.</p>";
                    return;
                }

                // Affichage des résultats dynamiques (live search)
                data.forEach(book => {
                    const bookElement = document.createElement("li");
                    bookElement.classList.add("card");

                    bookElement.innerHTML = `
                        <a href="/books/${book.id}">
                            <h3>${book.name}</h3>
                            <img src="/uploads/pictureProduct/${book.picture}" alt="${book.name}">
                            <p>Prix: ${book.price} €</p>
                        </a>
                        <a href="/cart/add/${book.id}" class="add-to-cart" data-id="${book.id}">
                            ➕ Ajouter au panier
                        </a>
                    `;

                    booksContainer.appendChild(bookElement);
                });
            })
            .catch(error => console.error("Erreur lors de la recherche :", error));
    });

    // Gérer la touche Entrée pour rediriger vers la page de résultats
    input.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            const query = input.value.trim();
            if (query.length > 0) {
                window.location.href = `/search/results?q=${encodeURIComponent(query)}`; // Rediriger vers la page de résultats
            }
        }
    });

    // Gérer la fermeture des résultats de recherche si on clique à l'extérieur
    document.addEventListener('click', (e) => {
        if (resultsContainer && !document.getElementById('search-container').contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
});
