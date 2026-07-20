<!-- Script pour la navigation flottante -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nav = document.querySelector('.bottom-nav');
    if (!nav) return;

    const items = nav.querySelectorAll('.nav-item');
    const slider = nav.querySelector('.slider');

    function updateSlider(activeItem, animate = true) {
        if (!activeItem) return;
        const navRect = nav.getBoundingClientRect();
        const itemRect = activeItem.getBoundingClientRect();
        const left = itemRect.left - navRect.left;
        const width = itemRect.width;
        slider.style.left = left + 'px';
        slider.style.width = width + 'px';
        if (!animate) {
            slider.style.transition = 'none';
            void slider.offsetHeight;
            slider.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
        }
    }

    items.forEach((item) => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            if (href) {
                // Animation avant la navigation
                const icon = this.querySelector('svg');
                if (icon) {
                    icon.style.transform = 'scale(0.8) rotate(-6deg)';
                    setTimeout(() => { icon.style.transform = 'scale(1.1)'; }, 150);
                    setTimeout(() => { 
                        icon.style.transform = '';
                        window.location.href = href;
                    }, 350);
                } else {
                    window.location.href = href;
                }
            }
        });
    });

    // Initialisation du slider
    const activeItem = nav.querySelector('.nav-item.active');
    if (activeItem) {
        requestAnimationFrame(() => updateSlider(activeItem, false));
    } else if (items.length > 0) {
        items[0].classList.add('active');
        requestAnimationFrame(() => updateSlider(items[0], false));
    }

    // Mise à jour au redimensionnement
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const currentActive = nav.querySelector('.nav-item.active');
            if (currentActive) updateSlider(currentActive, false);
        }, 100);
    });
});
</script>
