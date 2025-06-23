document.addEventListener('turbo:submit-start', function(event) {
  const form = event.target;

  // On cible uniquement les formulaires avec cette classe
  if (!form.classList.contains('needs-validation')) return;

  // On empêche la soumission normale (Turbo par défaut)
  event.preventDefault();

  // Récupérer le préfixe du nom des champs (ex: "figurine")
  const firstInput = form.querySelector('input[name], select[name], textarea[name]');
  if (!firstInput) return;

  const rootNameMatch = firstInput.name.match(/^(\w+)\[/);
  const rootName = rootNameMatch ? rootNameMatch[1] : null;
  if (!rootName) return;

  // Préparer les données du formulaire
  const data = new FormData(form);

  fetch(form.action, {
    method: form.method,
    body: data,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    }
  })
  .then(async response => {
    clearErrors(form);

    let json;
    try {
      json = await response.json();
    } catch {
      alert('Réponse serveur invalide');
      return;
    }

    if (response.ok && json.success) {
      alert(json.message);

      if (json.redirectUrl) {
        // Redirection vers l'URL donnée
        window.location.href = json.redirectUrl;
      } else {
        // Sinon reset du formulaire
        form.reset();
      }
    } else {
      if (json.errors) {
        // Affichage des erreurs champ par champ
        for (const [field, messages] of Object.entries(json.errors)) {
          const input = form.querySelector(`[name^="${rootName}[${field}]"]`) || form.querySelector(`[name="${rootName}[${field}]"]`);
          if (input) {
            input.classList.add('error');

            // Ajouter un conteneur d'erreur sous l'input
            let errorDiv = document.createElement('div');
            errorDiv.className = 'form-error-message';
            errorDiv.innerText = messages.join(', ');
            input.parentNode.appendChild(errorDiv);
          }
        }
      }
      alert(json.message || 'Erreur formulaire');
    }
  })
  .catch(() => {
    alert('Erreur serveur');
  });
});

function clearErrors(form) {
  form.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
  form.querySelectorAll('.form-error-message').forEach(el => el.remove());
}
