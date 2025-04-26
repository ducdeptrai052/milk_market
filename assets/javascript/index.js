document.addEventListener('DOMContentLoaded', function() {
    // --- Slider Functionality ---
    const slider = document.querySelector('.banner-items');
    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.querySelector('.slider-dots');
    let currentSlide = 0;

    if (slider && slides.length > 0 && dotsContainer) { // Check if slider elements exist
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function updateDots() {
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateDots();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            goToSlide(currentSlide);
        }
        setInterval(nextSlide, 3000); // Auto slide
    }
    // --- End Slider Functionality ---

    // --- Add to Cart & Pop-up Functionality ---
    const productLists = document.querySelectorAll('.product-list');
    const cartCountElement = document.querySelector('.header-actions .cart .cart-count'); 
    const popup = document.getElementById('add-to-cart-popup');
    const popupMessage = popup ? popup.querySelector('.popup-message') : null;
    const popupCloseBtn = popup ? popup.querySelector('.popup-close-btn') : null;
    let popupTimeout; 

    // Function to show the pop-up
    function showPopup(message) {
        if (!popup || !popupMessage) return; // Don't run if popup elements don't exist

        // Clear any existing timeout to prevent multiple popups overlapping badly
        clearTimeout(popupTimeout);

        popupMessage.textContent = message;
        popup.classList.add('show');

        // Automatically hide after 3 seconds
        popupTimeout = setTimeout(() => {
            hidePopup();
        }, 3000);
    }

    // Function to hide the pop-up
    function hidePopup() {
        if (!popup) return;
        popup.classList.remove('show');
        clearTimeout(popupTimeout); // Clear timeout if closed manually
    }

    // Add event listener to close button
    if (popupCloseBtn) {
        popupCloseBtn.addEventListener('click', hidePopup);
    }

    // Function to update cart count in header
    function updateHeaderCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalItems = 0;
        cart.forEach(item => {
            totalItems += parseInt(item.quantity) || 0;
        });
        if (cartCountElement) {
            cartCountElement.textContent = totalItems; // Update only the number
        }
    }

    // Function to handle adding product to cart
    function handleAddToCart(event) {
        if (!event.target.classList.contains('add-to-cart-btn')) {
            return;
        }

        const button = event.target;
        const productItem = button.closest('.product-item');
        const quantityInput = productItem ? productItem.querySelector('.quantity-input') : null; // Check if productItem exists

        if (!productItem || !quantityInput) {
            console.error("Could not find product item or quantity input.");
            return;
        }

        const productId = productItem.dataset.productId;
        const productName = productItem.dataset.productName;
        const productPrice = parseFloat(productItem.dataset.productPrice);
        const productImage = productItem.dataset.productImage;
        const quantity = parseInt(quantityInput.value);

        if (isNaN(quantity) || quantity < 1) {
            // Use the custom popup for errors too, maybe with a different style later
            showPopup('Số lượng không hợp lệ. Vui lòng nhập số lớn hơn 0.');
            quantityInput.value = 1;
            return;
        }

        if (!productId || !productName || isNaN(productPrice)) {
             console.error("Missing product data attributes (ID, Name, or Price).");
             showPopup('Lỗi: Không thể thêm sản phẩm do thiếu thông tin.');
             return;
        }


        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingProductIndex = cart.findIndex(item => item.id === productId);

        if (existingProductIndex > -1) {
            cart[existingProductIndex].quantity += quantity;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: quantity
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateHeaderCartCount();

        // Show custom pop-up instead of alert
        showPopup(`${quantity} "${productName}" đã được thêm vào giỏ hàng!`);

        quantityInput.value = 1; // Reset quantity input
    }

    // Add event listener using delegation on product lists
    productLists.forEach(list => {
        list.addEventListener('click', handleAddToCart);
    });

    // Initial cart count update on page load
    updateHeaderCartCount();
    // --- End Add to Cart & Pop-up Functionality ---

}); // End DOMContentLoaded