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
                method: "POST", // ⬅️ IMPORTANT : Utiliser POST car la route Symfony l'attend
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

