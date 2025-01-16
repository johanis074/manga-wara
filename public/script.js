const itemsPerPage = 50;
        const items = Array.from({ length: 150 }, (_, i) => `Élément ${i + 1}`); // Exemple avec 150 éléments

        function displayItems(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageItems = items.slice(start, end);
            document.getElementById('content').innerHTML = pageItems.join('<br>');
        }

        function setupPagination(totalItems) {
            const pageCount = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            for (let i = 1; i <= pageCount; i++) {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.textContent = i;
                a.href = '#';
                a.addEventListener('click', (event) => {
                    event.preventDefault();
                    displayItems(i);
                    document.querySelectorAll('.pagination li a').forEach(link => link.classList.remove('active'));
                    a.classList.add('active');
                });
                li.appendChild(a);
                pagination.appendChild(li);
            }

            // Afficher la première page par défaut
            document.querySelector('.pagination li a').click();
        }

        setupPagination(items.length);