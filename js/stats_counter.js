document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.stat-item h2[data-target], .stat-item h2 span[data-target]');
    const duration = 1500;

    const animateCounter = (el) => {
        const target = parseInt(el.dataset.target);
        const hasSuffix = el.dataset.suffix || '';
        const start = performance.now();

        const update = (now) => {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 2);
            el.textContent = Math.floor(eased * target) + hasSuffix;

            if (progress < 1) requestAnimationFrame(update);
        };

        requestAnimationFrame(update);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
});