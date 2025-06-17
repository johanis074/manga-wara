
document.addEventListener("turbo:load", function () {
    const stripe = Stripe('pk_test_xxxxxxxxxxxxxxxxxxx');

    document.querySelector("#pay-button").addEventListener("click", () => {
        fetch('/create-checkout-session', { method: 'POST' })
            .then(res => res.json())
            .then(data => stripe.redirectToCheckout({ sessionId: data.id }));
    });
});
