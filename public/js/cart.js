function showCartPopup(count, total) {
    const popup = document.getElementById("cart-popup");
    if (!popup) return;

    popup.querySelector("#popup-total-items").textContent = count;
    popup.querySelector("#popup-total-price").textContent = total.toFixed(2);
    popup.style.display = "block";

    setTimeout(() => {
        popup.style.display = "none";
    }, 3000);
}

function handleAddToCartClick(event) {
    const button = event.target.closest(".add-to-cart");
    if (!button) return;

    event.preventDefault();

    const productId = button.dataset.id;
    const productType = button.dataset.type;

    fetch(`/cart/add/${productType}/${productId}`, {
        method: "GET"
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCartPopup(data.totalItems, data.totalPrice);
            } else {
                alert("Erreur lors de l’ajout au panier");
            }
        })
        .catch(error => console.error("Erreur fetch:", error));
}

function bindCartButtons() {
    // Supprimer les anciens écouteurs si besoin (sécurité)
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.removeEventListener("click", handleAddToCartClick);
        button.addEventListener("click", handleAddToCartClick);
    });
}

// ➤ Turbo (retour arrière inclus)
document.addEventListener("turbo:load", () => {
    bindCartButtons();
});

// ➤ Cas sans Turbo (page classique)
document.addEventListener("DOMContentLoaded", () => {
    bindCartButtons();
});
