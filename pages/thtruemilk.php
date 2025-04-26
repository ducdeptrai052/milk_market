<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm TH TRUE MILK - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Add specific styles for this page if needed */
        .category-page-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px; /* Add padding for smaller screens */
        }
        .category-page-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        /* Reuse product-list and product-item styles from style.css */

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .pagination a:hover {
            background-color: #f2f2f2;
        }
        .pagination span.active {
            background-color: #ff4500; /* Orange color */
            color: white;
            border-color: #ff4500;
            font-weight: bold;
        }
        .pagination span.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/db_connect.php'; ?>

     <div class="category-page-container">
        <h1>Sản phẩm TH TRUE MILK</h1>

        <div class="product-list">
            <?php
            // --- PHP Pagination Logic ---

            $products_per_page = 12; // Số sản phẩm trên mỗi trang
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy số trang hiện tại từ URL

            // Đảm bảo số trang hợp lệ
            if ($current_page < 1) {
                $current_page = 1;
            }

            // Get the category ID for 'TH TRUE MILK'
            $category_name = 'TH TRUE MILK'; // Assuming the category name is 'TH TRUE MILK'
            $category_id = null;
            $sql_category = "SELECT id FROM categories WHERE name = ?";
            $stmt_category = mysqli_prepare($conn, $sql_category);
            mysqli_stmt_bind_param($stmt_category, "s", $category_name);
            mysqli_stmt_execute($stmt_category);
            $result_category = mysqli_stmt_get_result($stmt_category);

            if ($row_category = mysqli_fetch_assoc($result_category)) {
                $category_id = $row_category['id'];
            }
            mysqli_stmt_close($stmt_category);

            $total_products = 0;
            $products_on_page = [];
            $total_pages = 0;

            if ($category_id !== null) {
                // Get total number of products for pagination
                $sql_total = "SELECT COUNT(*) AS total FROM products WHERE category_id = ?";
                $stmt_total = mysqli_prepare($conn, $sql_total);
                mysqli_stmt_bind_param($stmt_total, "i", $category_id);
                mysqli_stmt_execute($stmt_total);
                $result_total = mysqli_stmt_get_result($stmt_total);
                $row_total = mysqli_fetch_assoc($result_total);
                $total_products = $row_total['total'];
                mysqli_stmt_close($stmt_total);

                $total_pages = ceil($total_products / $products_per_page); // Tổng số trang

                // Đảm bảo số trang hiện tại không vượt quá tổng số trang
                if ($current_page > $total_pages && $total_pages > 0) {
                    $current_page = $total_pages;
                } elseif ($total_pages == 0) {
                     $current_page = 1; // Handle case with no products
                }

                $offset = ($current_page - 1) * $products_per_page; // Vị trí bắt đầu lấy dữ liệu

                // Fetch products for the current page
                $sql_products = "SELECT id, name, price, image FROM products WHERE category_id = ? LIMIT ?, ?";
                $stmt_products = mysqli_prepare($conn, $sql_products);
                mysqli_stmt_bind_param($stmt_products, "iii", $category_id, $offset, $products_per_page);
                mysqli_stmt_execute($stmt_products);
                $result_products = mysqli_stmt_get_result($stmt_products);

                while ($row_product = mysqli_fetch_assoc($result_products)) {
                    $products_on_page[] = $row_product;
                }
                mysqli_stmt_close($stmt_products);
            }


            // --- Display Products ---
            if ($total_products > 0) {
                foreach ($products_on_page as $product) {
                    ?>
                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="product-item-link">
                        <div class="product-item" data-product-id="<?php echo $product['id']; ?>" data-product-name="<?php echo $product['name']; ?>" data-product-price="<?php echo $product['price']; ?>" data-product-image="../<?php echo $product['image']; ?>">
                            <img src="../<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="product-item-content">
                                <h3><?php echo $product['name']; ?></h3>
                                <p class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                            </div>
                            <div class="product-actions">
                                <input type="number" value="1" min="1" class="quantity-input">
                                <button class="add-to-cart-btn">Chọn mua</button>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            } else {
                echo "<p style='text-align: center; width: 100%;'>Không tìm thấy sản phẩm nào.</p>";
            }
            ?>
        </div>

        <?php
        // --- Pagination Links ---
        if ($total_pages > 1) {
            ?>
            <div class="pagination">
                <?php
                // Link "Trang đầu"
                if ($current_page > 1) {
                    echo '<a href="?page=1">Trang đầu</a>';
                } else {
                    echo '<span class="disabled">Trang đầu</span>';
                }

                // Link "Trước"
                if ($current_page > 1) {
                    echo '<a href="?page=' . ($current_page - 1) . '">Trước</a>';
                } else {
                    echo '<span class="disabled">Trước</span>';
                }

                // Các liên kết số trang
                // Hiển thị một vài trang xung quanh trang hiện tại
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $current_page) {
                        echo '<span class="active">' . $i . '</span>';
                    } else {
                        echo '<a href="?page=' . $i . '">' . $i . '</a>';
                    }
                }

                // Link "Sau"
                if ($current_page < $total_pages) {
                    echo '<a href="?page=' . ($current_page + 1) . '">Sau</a>';
                } else {
                    echo '<span class="disabled">Sau</span>';
                }

                // Link "Trang cuối"
                if ($current_page < $total_pages) {
                    echo '<a href="?page=' . $total_pages . '">Trang cuối</a>';
                } else {
                    echo '<span class="disabled">Trang cuối</span>';
                }
                ?>
            </div>
            <?php
        }
        ?>

    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Link to the main JavaScript file that handles add-to-cart -->
    <!-- Ensure this script is included AFTER the product items are loaded -->
    <script src="../assets/javascript/index.js"></script>
</body>
</html>