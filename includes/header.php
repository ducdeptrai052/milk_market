<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/header.css">

<header>
    <!-- Main Header Section -->
    <div class="main-header">
        <div class="container">
            <div class="logo">
                <a href="../pages/index.php"><img src="../assets/image/logo.png" alt="logo"></a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="header-actions">
                <a href="#!" class="hotline">
                    <i class="fa-solid fa-phone"></i> 1800 545440
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="../admin/admin_dashboard.php" class="account"> <!-- Link to admin dashboard -->
                            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)
                        </a>
                    <?php else: ?>
                        <a href="#!" class="account"> <!-- Link có thể trỏ đến trang profile -->
                            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                    <?php endif; ?>
                    <span style="color: #ccc;margin-left: 15px;">|</span> <!-- Dấu phân cách -->
                    <a href="../process/logout.php" class="account">
                         Đăng xuất
                    </a>
                <?php else: ?>
                    <a href="../pages/login.php" class="account">
                        <i class="fa-solid fa-user"></i> Đăng nhập
                    </a>

                <?php endif; ?>
                <a href="../pages/cart.php" class="cart"><i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-text">Giỏ hàng</span> (<span class="cart-count">0</span>)
                </a>

            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav>
        <div class="container">
            <ul class="menu">
                <li>
                    <a href="#!" class="category-toggle"><i class="fa-solid fa-bars"></i>DANH MỤC</a>
                    <ul class="sub-menu">
                        <li><a href="../pages/vinamilk.php">Vinamilk</a></li>
                        <li><a href="../pages/thtruemilk.php">TH True Milk</a></li>
                        <li><a href="#!">Nutri Food</a></li>
                    </ul>
                </li>
                <li><a href="#!">SẢN PHẨM ƯA THÍCH</a></li>
                <li><a href="#!">SẢN PHẨM MỚI</a></li>
                <li><a href="#!">SẢN PHẨM KHUYẾN MẠI</a></li>
                <li><a href="#!">DANH SÁCH CỬA HÀNG</a></li>
            </ul>
        </div>
    </nav>
</header>


<script>
    function updateHeaderCartCountHeader() {
        const cartCountElement = document.querySelector('.header-actions .cart .cart-count'); // Target the span
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalItems = 0;
        cart.forEach(item => {
            totalItems += parseInt(item.quantity) || 0;
        });
        if (cartCountElement) {
            cartCountElement.textContent = totalItems; // Update only the number
        }
    }
    // Update count on initial load
    document.addEventListener('DOMContentLoaded', updateHeaderCartCountHeader);
    // Optional: Listen for custom event when cart updates elsewhere
    window.addEventListener('cartUpdated', updateHeaderCartCountHeader);
</script>