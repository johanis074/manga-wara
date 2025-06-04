import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


document.addEventListener("DOMContentLoaded", function () {
    const cartButtons = document.querySelectorAll(".add-to-cart");

    cartButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const productId = this.dataset.id;

            fetch(`/cart/add/${productId}`, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showCartPopup(data.totalItems, data.totalPrice);
                    } else {
                        alert("Erreur lors de l'ajout au panier !");
                    }
                })
                .catch(error => console.error("Erreur :", error));
        });
    });

    function showCartPopup(count, total) {
        const popup = document.querySelector("#cart-popup");
        if (!popup) return;

        document.getElementById("popup-total-items").textContent = count;
        document.getElementById("popup-total-price").textContent = total.toFixed(2);

        popup.classList.add("visible");

        setTimeout(() => popup.classList.remove("visible"), 3000);
    }
});



document.addEventListener("DOMContentLoaded", function () {
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




document.addEventListener("DOMContentLoaded", function () {
    let dropdownTrigger = document.querySelector(".dropdown-trigger");
    let dropdownMenu = document.querySelector(".dropdown-menu");

    dropdownTrigger.addEventListener("click", function (event) {
        event.preventDefault();
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function (event) {
        if (!dropdownTrigger.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show");
        }
    });
});

//stripe
const stripe = Stripe('pk_test_xxxxxxxxxxxxxxxxxxx');

document.querySelector("#pay-button").addEventListener("click", () => {
    fetch('/create-checkout-session', { method: 'POST' })
        .then(res => res.json())
        .then(data => stripe.redirectToCheckout({ sessionId: data.id }));
});

