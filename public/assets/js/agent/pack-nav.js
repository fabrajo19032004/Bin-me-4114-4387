/**
 * Navigation flottante en bas avec sliding indicator
 * À inclure dans toutes les pages
 */
(function() {
    'use strict';

    // Flag pour éviter les doubles initialisations
    let isInitialized = false;

    function initBottomNav() {
        // Éviter les doubles initialisations
        if (isInitialized) return;
        
        const nav = document.querySelector('.bottom-nav');
        if (!nav) return;

        const items = nav.querySelectorAll('.nav-item');
        let slider = nav.querySelector('.slider');

        // Créer le slider s'il n'existe pas
        if (!slider) {
            slider = document.createElement('div');
            slider.className = 'slider';
            nav.appendChild(slider);
        }

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

        function handleItemClick(e) {
            const target = e.currentTarget;
            const href = target.getAttribute('href');
            
            if (href) {
                e.preventDefault();
                
                // Retirer la classe active de tous
                items.forEach(item => item.classList.remove('active'));
                target.classList.add('active');
                
                // Mettre à jour le slider avec animation
                updateSlider(target, true);
                
                // Animation de l'icône au clic
                const icon = target.querySelector('svg');
                if (icon) {
                    icon.style.transform = 'scale(0.8) rotate(-6deg)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1.1)';
                    }, 150);
                    setTimeout(() => {
                        icon.style.transform = '';
                        // Rediriger après l'animation
                        window.location.href = href;
                    }, 350);
                } else {
                    setTimeout(() => {
                        window.location.href = href;
                    }, 300);
                }
            }
        }

        // Attacher les événements de clic
        items.forEach((item) => {
            // Supprimer les anciens écouteurs pour éviter les doublons
            item.removeEventListener('click', handleItemClick);
            item.addEventListener('click', handleItemClick);
            
            item.removeEventListener('mouseenter', handleMouseEnter);
            item.removeEventListener('mouseleave', handleMouseLeave);
            item.addEventListener('mouseenter', handleMouseEnter);
            item.addEventListener('mouseleave', handleMouseLeave);
        });

        function handleMouseEnter(e) {
            const icon = this.querySelector('svg');
            if (icon && !this.classList.contains('active')) {
                icon.style.transform = 'scale(1.1) rotate(-3deg)';
            }
        }

        function handleMouseLeave(e) {
            const icon = this.querySelector('svg');
            if (icon && !this.classList.contains('active')) {
                icon.style.transform = '';
            }
        }

        // Initialisation du slider
        function initSlider() {
            const activeItem = nav.querySelector('.nav-item.active');
            if (activeItem) {
                updateSlider(activeItem, false);
                return;
            }
            
            // Vérifier l'URL pour déterminer l'item actif
            const currentUrl = window.location.href;
            let found = false;
            
            items.forEach(item => {
                const href = item.getAttribute('href');
                if (href && currentUrl.includes(href)) {
                    item.classList.add('active');
                    found = true;
                }
            });
            
            if (!found && items.length > 0) {
                items[0].classList.add('active');
            }
            
            const newActive = nav.querySelector('.nav-item.active');
            if (newActive) {
                updateSlider(newActive, false);
            }
        }

        // Initialiser
        isInitialized = true;
        initSlider();

        // Réinitialiser après le chargement complet
        if (document.readyState !== 'complete') {
            window.addEventListener('load', function() {
                setTimeout(initSlider, 100);
            });
        }

        // Mettre à jour le slider au redimensionnement
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const currentActive = nav.querySelector('.nav-item.active');
                if (currentActive) {
                    updateSlider(currentActive, false);
                }
            }, 100);
        });
    }
   
    // ===== INITIALISATION UNIQUE =====
    // Ne s'exécute qu'une seule fois
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBottomNav);
    } else {
        initBottomNav();
    }

})();