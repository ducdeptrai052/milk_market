document.addEventListener('DOMContentLoaded', () => {
    const cartTableBody = document.querySelector('.cart-table tbody');
    const summarySubtotal = document.querySelector('.summary-box .subtotal-value');
    const summaryTotal = document.querySelector('.summary-box .total-value');
    const checkoutButton = document.querySelector('.checkout-button');

    // --- Helper Functions ---

    // Lấy giỏ hàng từ localStorage và lọc bỏ các mục không hợp lệ
    function getCart() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        // Lọc bỏ các mục không hợp lệ
        const validCart = cart.filter(item =>
            item &&
            typeof item.id === 'string' && item.id.length > 0 &&
            typeof item.name === 'string' && item.name.length > 0 &&
            typeof item.price === 'number' && !isNaN(item.price) &&
            typeof item.quantity === 'number' && !isNaN(item.quantity) && item.quantity >= 1
        );
        // Nếu có mục không hợp lệ bị xóa, lưu lại giỏ hàng đã lọc
        if (validCart.length !== cart.length) {
            saveCart(validCart);
        }
        return validCart;
    }

    // Lưu giỏ hàng vào localStorage
    function saveCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
        updateHeaderCartCount(); // Cập nhật số lượng header mỗi khi lưu
    }

    // Định dạng tiền tệ Việt Nam
    function formatCurrency(amount) {
        if (isNaN(amount)) return '0₫';
        return amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
    }

    // Cập nhật số lượng giỏ hàng trên header (cần có ở cả index.js và cart.js)
    function updateHeaderCartCount() {
        const cartCountElement = document.querySelector('.header-actions .cart .cart-count'); // Selector cho số lượng giỏ hàng ở header
        const cart = getCart(); // Lấy giỏ hàng đã được lọc
        let totalItems = 0;
        cart.forEach(item => {
            totalItems += parseInt(item.quantity) || 0;
        });
        if (cartCountElement) {
            cartCountElement.textContent = totalItems; // Cập nhật chỉ số lượng
        }
    }


    // --- Core Cart Functions ---

    // Cập nhật tổng cộng cho một hàng sản phẩm và lưu thay đổi số lượng
    function updateItemTotal(row) {
        const priceElement = row.querySelector('.product-price');
        const quantityInput = row.querySelector('input[type="number"]');
        const totalElement = row.querySelector('.item-total');
        const productId = row.dataset.productId; // Lấy ID sản phẩm từ data attribute

        if (!priceElement || !quantityInput || !totalElement || !productId) return;

        const price = parseFloat(priceElement.dataset.priceValue); // Lấy giá trị số gốc từ data attribute
        let quantity = parseInt(quantityInput.value);

        if (isNaN(price)) return; // Không thể tính nếu không có giá

        if (isNaN(quantity) || quantity < 1) {
            quantity = 1; // Đặt lại là 1 nếu không hợp lệ
            quantityInput.value = 1;
        }

        const total = price * quantity;
        totalElement.textContent = formatCurrency(total);

        // Cập nhật số lượng trong localStorage
        let cart = getCart(); // Lấy giỏ hàng đã được lọc
        const itemIndex = cart.findIndex(item => item.id === productId);
        if (itemIndex > -1) {
            cart[itemIndex].quantity = quantity;
            saveCart(cart); // Lưu lại giỏ hàng sau khi cập nhật số lượng
        }

        updateCartSummary(); // Cập nhật tóm tắt giỏ hàng
    }

    // Cập nhật tóm tắt giỏ hàng (tạm tính và tổng cộng)
    function updateCartSummary() {
        let subtotal = 0;
        const cart = getCart(); // Lấy dữ liệu mới nhất từ localStorage (đã lọc)

        cart.forEach(item => {
            const price = parseFloat(item.price);
            const quantity = parseInt(item.quantity);
            if (!isNaN(price) && !isNaN(quantity)) {
                subtotal += price * quantity;
            }
        });

        // Giả sử phí vận chuyển là miễn phí
        const shippingCost = 0;
        const total = subtotal + shippingCost;

        if (summarySubtotal) {
            summarySubtotal.textContent = formatCurrency(subtotal);
        }
        if (summaryTotal) {
            summaryTotal.textContent = formatCurrency(total);
        }
    }

    // Xóa một sản phẩm khỏi giỏ hàng (DOM và localStorage)
    function removeItem(productId) {
        let cart = getCart(); // Lấy giỏ hàng đã được lọc
        cart = cart.filter(item => item.id !== productId); // Lọc ra sản phẩm cần xóa
        saveCart(cart); // Lưu lại giỏ hàng mới
        loadCartItems(); // Tải lại giao diện giỏ hàng
    }

    // Tạo HTML cho một hàng sản phẩm trong giỏ hàng
    function createCartRowHTML(item) {
        const itemTotal = (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0);
        // Lưu giá trị số gốc vào data attribute để dễ dàng truy xuất
        return `
            <tr data-product-id="${item.id}">
                <td class="product-info">
                    <img src="${item.image || '../image/placeholder.png'}" alt="${item.name}">
                    <span>${item.name}</span>
                </td>
                <td class="product-price" data-price-value="${item.price || 0}">${formatCurrency(item.price || 0)}</td>
                <td><input type="number" value="${item.quantity}" min="1"></td>
                <td class="item-total">${formatCurrency(itemTotal)}</td>
                <td><span class="remove-item" style="cursor: pointer; color: red;">Xóa</span></td>
            </tr>
        `;
    }

    // Tải và hiển thị các sản phẩm trong giỏ hàng từ localStorage
    function loadCartItems() {
        if (!cartTableBody) return;

        const cart = getCart(); // Lấy giỏ hàng đã được lọc
        cartTableBody.innerHTML = ''; // Xóa các hàng hiện có (kể cả hàng mẫu)

        if (cart.length === 0) {
            cartTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Giỏ hàng của bạn đang trống.</td></tr>';
        } else {
            cart.forEach(item => {
                cartTableBody.innerHTML += createCartRowHTML(item);
            });
        }
        updateCartSummary(); // Cập nhật tóm tắt sau khi tải xong
    }

    // --- Event Listeners ---
    if (cartTableBody) {
        // Lắng nghe thay đổi số lượng
        cartTableBody.addEventListener('input', (event) => {
            if (event.target.type === 'number') {
                const row = event.target.closest('tr');
                if (row) {
                    updateItemTotal(row); // Hàm này đã bao gồm lưu localStorage và cập nhật summary
                }
            }
        });

        // Lắng nghe click để xóa sản phẩm
        cartTableBody.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-item')) {
                const row = event.target.closest('tr');
                if (row && row.dataset.productId) {
                    removeItem(row.dataset.productId); // Gọi hàm xóa với ID sản phẩm
                }
            }
        });
    }

    // Xử lý form "Đặt hàng"
    const orderForm = document.querySelector('.cart-summary form'); // Select the form

    if (orderForm) {
        orderForm.addEventListener('submit', (event) => {
            const cart = getCart(); // Lấy giỏ hàng đã được lọc

            if (cart.length === 0) {
                alert('Giỏ hàng của bạn đang trống!');
                event.preventDefault(); // Ngăn chặn gửi form nếu giỏ hàng trống
                return;
            }

            // Tạo một input ẩn để chứa dữ liệu giỏ hàng
            const cartDataInput = document.createElement('input');
            cartDataInput.type = 'hidden';
            cartDataInput.name = 'cart_data';
            cartDataInput.value = JSON.stringify(cart);

            // Thêm input ẩn vào form trước khi submit
            orderForm.appendChild(cartDataInput);

            // Form sẽ tự động submit sau khi thêm input ẩn
            // Server-side script (process_order.php) sẽ xử lý dữ liệu này
        });
    }

    // --- Initial Load ---
    loadCartItems(); // Tải giỏ hàng khi trang được tải
    updateHeaderCartCount(); // Cập nhật số lượng header ban đầu

});