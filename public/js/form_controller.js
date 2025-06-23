import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    async submit(event) {
        event.preventDefault();

        const form = this.element;
        const url = form.action;
        const formData = new FormData(form);

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            const msgDiv = document.getElementById('form-messages');
            msgDiv.innerHTML = '';

            if (result.status === 'success') {
                msgDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                Turbo.visit(result.redirect);
            } else if (result.status === 'error') {
                msgDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                if (result.errors) {
                    for (const field in result.errors) {
                        result.errors[field].forEach(error => {
                            msgDiv.innerHTML += `<div class="alert alert-warning"><strong>${field}</strong>: ${error}</div>`;
                        });
                    }
                }
            }
        } catch (error) {
            console.error("Erreur AJAX :", error);
            document.getElementById('form-messages').innerHTML = `<div class="alert alert-danger">Erreur inattendue.</div>`;
        }
    }
}
