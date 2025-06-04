
        function initCarousel() {
    const slides = document.querySelector('.carousel .slides');
    const images = document.querySelectorAll('.carousel .slides img');
    const indicatorsContainer = document.getElementById('indicators');
    const nextBtn = document.getElementById('next');
    const prevBtn = document.getElementById('prev');

    if (!slides || images.length === 0 || !indicatorsContainer) return;

    const totalSlides = images.length;
    let index = 0;
    let interval;

    function createIndicators() {
        indicatorsContainer.innerHTML = ''; // reset
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('span');
            dot.classList.add('indicator');
            if (i === 0) dot.classList.add('active');

            dot.addEventListener('click', () => {
                index = i;
                updateCarousel();
                resetInterval();
            });

            indicatorsContainer.appendChild(dot);
        }
    }

    function updateCarousel() {
        slides.style.transform = `translateX(-${index * 100}%)`;
        document.querySelectorAll('.indicator').forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    function startAutoSlide() {
        interval = setInterval(() => {
            index = (index + 1) % totalSlides;
            updateCarousel();
        }, 3000);
    }

    function resetInterval() {
        clearInterval(interval);
        startAutoSlide();
    }

    nextBtn?.addEventListener('click', () => {
        index = (index + 1) % totalSlides;
        updateCarousel();
        resetInterval();
    });

    prevBtn?.addEventListener('click', () => {
        index = (index - 1 + totalSlides) % totalSlides;
        updateCarousel();
        resetInterval();
    });

    createIndicators();
    updateCarousel();
    startAutoSlide();
}

document.addEventListener('turbo:load', initCarousel);
