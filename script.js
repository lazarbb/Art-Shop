document.addEventListener('DOMContentLoaded', function() {
    const sortOptions = document.getElementById('sortOptions');
    const productsGrid = document.getElementById('productsGrid');

    sortOptions.addEventListener('change', function() {
        const sortValue = this.value;
        const items = Array.from(productsGrid.getElementsByClassName('item'));

        items.sort((a, b) => {
            let aValue, bValue;
            if (sortValue.includes('name')) {
                aValue = a.getAttribute('data-name').toLowerCase();
                bValue = b.getAttribute('data-name').toLowerCase();
                return sortValue === 'name-asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            } else {
                aValue = parseFloat(a.getAttribute('data-price'));
                bValue = parseFloat(b.getAttribute('data-price'));
                return sortValue === 'price-asc' ? aValue - bValue : bValue - aValue;
            }
        });

        // Clear the grid and append sorted items
        productsGrid.innerHTML = '';
        items.forEach(item => productsGrid.appendChild(item));
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.add-to-cart-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const formData = new FormData(this);
            const productId = this.getAttribute('data-product-id');

            formData.append('product_id', productId);

            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.cart_count;
                    alert('Product added to cart successfully!');
                } else {
                    alert('There was an error adding the product to the cart.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
