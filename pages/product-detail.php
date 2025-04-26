<?php
// Bắt đầu session (có thể cần cho giỏ hàng hoặc thông báo)
session_start();

// Nhúng tệp kết nối cơ sở dữ liệu
include '../includes/db_connect.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$product = null; // Biến để lưu thông tin sản phẩm

// Kiểm tra xem ID sản phẩm có hợp lệ không
if ($product_id > 0) {
    // Chuẩn bị truy vấn SQL để lấy thông tin sản phẩm
    $sql = "SELECT id, name, price, image, description, category_id FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Gán tham số và thực thi truy vấn
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Lấy dữ liệu sản phẩm
        if ($row = mysqli_fetch_assoc($result)) {
            $product = $row;
        }

        // Đóng statement
        mysqli_stmt_close($stmt);
    } else {
        // Lỗi chuẩn bị statement
        // Trong môi trường production, bạn nên ghi log lỗi này thay vì hiển thị cho người dùng
        echo "Lỗi hệ thống truy vấn sản phẩm.";
    }
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($product['name']) : 'Sản phẩm không tồn tại'; ?> - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Basic styles for product detail page */
        .product-detail-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }
        .product-detail-image {
            flex: 1 1 400px; /* Grow and shrink, base width 400px */
            max-width: 500px;
            text-align: center;
        }
        .product-detail-image img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .product-detail-info {
            flex: 1 1 400px; /* Grow and shrink, base width 400px */
        }
        .product-detail-info h1 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #333;
        }
        .product-detail-info .price {
            font-size: 1.5em;
            color: #ff4500; /* Orange color for price */
            margin-bottom: 20px;
            font-weight: bold;
        }
        .product-detail-info .description {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
        }
        .product-detail-actions {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }
        .product-detail-actions label {
            margin-right: 10px;
            font-weight: bold;
        }
        .product-detail-actions .quantity-input {
            width: 60px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
            margin-right: 15px;
        }
        .product-detail-actions .add-to-cart-btn {
            padding: 10px 20px;
            background-color: #28a745; /* Green color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .product-detail-actions .add-to-cart-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="product-detail-container">
        <?php if ($product): ?>
            <div class="product-detail-image">
                <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-detail-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                <div class="description">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p> <!-- Hiển thị mô tả và giữ định dạng xuống dòng -->
                </div>
                <div class="product-detail-actions">
                    <label for="detail-quantity">Số lượng:</label>
                    <input type="number" id="detail-quantity" value="1" min="1" class="quantity-input">
                    <!-- Add data attributes for product info -->
                    <button class="add-to-cart-btn"
                            data-product-id="<?php echo $product['id']; ?>"
                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                            data-product-price="<?php echo $product['price']; ?>"
                            data-product-image="../<?php echo htmlspecialchars($product['image']); ?>">
                        Thêm vào giỏ hàng
                    </button>
                </div>
            </div>
        <?php else: ?>
            <p style="text-align: center; width: 100%;">Sản phẩm không tồn tại hoặc ID không hợp lệ.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Link to a specific JS file for product detail page -->
    <!-- Ensure this script is included AFTER the product details are loaded -->
    <script src="../assets/javascript/product-detail.js"></script>
</body>
</html>