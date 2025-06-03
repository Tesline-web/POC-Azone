document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour automatique des quantités dans le panier
    const quantityForms = document.querySelectorAll('.quantity-form');
    quantityForms.forEach(form => {
        const input = form.querySelector('input[type="number"]');
        if (input) {
            input.addEventListener('change', () => form.submit());
        }
    });

    // Animation des cards au survol
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.transition = 'transform 0.3s ease';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });

    // Gestion des messages flash
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });

    // Validation du formulaire de commande
    const checkoutForm = document.querySelector('#checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires');
            }
        });
    }

    // Système de notation dynamique
    const ratingInputs = document.querySelectorAll('.rating input');
    const ratingLabels = document.querySelectorAll('.rating label');

    ratingLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const currentStar = this.getAttribute('for').replace('star', '');
            ratingLabels.forEach(l => {
                const star = l.getAttribute('for').replace('star', '');
                if (star >= currentStar) {
                    l.style.color = '#ffd700';
                }
            });
        });

        label.addEventListener('mouseout', function() {
            ratingLabels.forEach(l => {
                const input = document.querySelector('#' + l.getAttribute('for'));
                if (!input.checked) {
                    l.style.color = '#ddd';
                }
            });
        });
    });

    // Filtres de produits dynamiques
    const filterButtons = document.querySelectorAll('[data-filter]');
    const productItems = document.querySelectorAll('.product-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;

            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            productItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Gestion du panier avec localStorage
    function updateCartCount() {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            const cartItems = JSON.parse(localStorage.getItem('cart') || '[]');
            cartBadge.textContent = cartItems.length;
        }
    }

    // Mise à jour initiale du compteur du panier
    updateCartCount();
});
