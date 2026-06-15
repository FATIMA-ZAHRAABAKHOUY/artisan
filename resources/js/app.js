import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.payment-card').forEach((card) => {
        const radio = card.querySelector('input[type="radio"]');
        if (!radio) return;

        const sync = () => {
            document.querySelectorAll('.payment-card').forEach((c) => c.classList.remove('selected'));
            if (radio.checked) {
                card.classList.add('selected');
            }
        };

        radio.addEventListener('change', sync);
        card.addEventListener('click', (e) => {
            if (e.target.tagName !== 'INPUT') {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });

        sync();
    });

    document.querySelectorAll('.product-gallery-thumb').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const main = document.getElementById('product-main-image');
            if (!main) return;

            document.querySelectorAll('.product-gallery-thumb').forEach((t) => t.classList.remove('active'));
            thumb.classList.add('active');
            main.src = thumb.dataset.src;
        });
    });

    const roleClient = document.getElementById('role_client');
    const roleArtisan = document.getElementById('role_artisan');
    const artisanFields = document.getElementById('artisan-fields');

    if (roleClient && roleArtisan && artisanFields) {
        const toggleArtisanFields = () => {
            artisanFields.style.display = roleArtisan.checked ? 'block' : 'none';
        };

        roleClient.addEventListener('change', toggleArtisanFields);
        roleArtisan.addEventListener('change', toggleArtisanFields);
        toggleArtisanFields();
    }
});
