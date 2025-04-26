document.addEventListener('DOMContentLoaded', () => {
    const addToCartButton = document.querySelector('.product-detail-actions .add-to-cart-btn');
    const quantityInput = document.querySelector('.product-detail-actions .quantity-input');

    // Hàm cập nhật số lượng giỏ hàng trên header (cần có ở nhiều trang)
    function updateHeaderCartCount() {
        const cartCountElement = document.querySelector('.header-actions .cart .cart-count'); // Selector cho số lượng giỏ hàng ở header
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalItems = 0;
        cart.forEach(item => {
            totalItems += parseInt(item.quantity) || 0;
        });
        if (cartCountElement) {
            cartCountElement.textContent = totalItems;
        }
    }

    // Xử lý sự kiện click nút "Thêm vào giỏ hàng"
    if (addToCartButton && quantityInput) {
        addToCartButton.addEventListener('click', () => {
            const productId = addToCartButton.dataset.productId;
            const productName = addToCartButton.dataset.productName;
            const productPrice = parseFloat(addToCartButton.dataset.productPrice);
            const productImage = addToCartButton.dataset.productImage;
            const quantity = parseInt(quantityInput.value);

            if (isNaN(quantity) || quantity < 1) {
                alert('Số lượng không hợp lệ. Vui lòng nhập số lớn hơn 0.');
                quantityInput.value = 1;
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
            updateHeaderCartCount(); // Cập nhật số lượng trên header
            alert(`${quantity} "${productName}" đã được thêm vào giỏ hàng!`);

            // Tùy chọn: Đặt lại ô số lượng về 1 sau khi thêm
            quantityInput.value = 1;
        });
    }

    // Cập nhật số lượng giỏ hàng trên header khi tải trang lần đầu
    updateHeaderCartCount();
});