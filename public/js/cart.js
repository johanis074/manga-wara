document.addEventListener("DOMContentLoaded", function () {
    const cartButtons = document.querySelectorAll(".add-to-cart");

    cartButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const productId = this.dataset.id;

            fetch(`/cart/add/${productId}`, { method: "POST" })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showCartPopup("Ajouté au panier ✅", data.totalItems, data.totalPrice);
                    } else {
                        alert("Erreur lors de l'ajout au panier !");
                    }
                })
                .catch(error => console.error("Erreur :", error));
        });
    });

    function showCartPopup(message, count, total) {
        let popup = document.querySelector(".cart-popup");
        if (!popup) {
            popup = document.createElement("div");
            popup.className = "cart-popup";
            document.body.appendChild(popup);
        }

        popup.innerHTML = `
            <p>${message}</p>
            <p>Articles dans le panier : ${count}</p>
            <p>Total : ${total.toFixed(2)} €</p>
        `;

        popup.style.display = "block";
        setTimeout(() => popup.style.display = "none", 3000);
    }
});
