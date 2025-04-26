<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="cart-container">
        <h1>Giỏ hàng của bạn</h1>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng cộng</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hàng mẫu 1 -->
                <tr>
                    <td class="product-info">
                        <img src="../assets/image/product1.png" alt="Sản phẩm 1">
                        <span>Tên sản phẩm 1</span>
                    </td>
                    <td class="product-price">150,000₫</td>
                    <td><input type="number" value="1" min="1"></td>
                    <td class="item-total">150,000₫</td>
                    <td><span class="remove-item">Xóa</span></td>
                </tr>
                <!-- Hàng mẫu 2 -->
                <tr>
                    <td class="product-info">
                        <img src="../assets/image/product2.png" alt="Sản phẩm 2">
                        <span>Tên sản phẩm 2 dài hơn một chút</span>
                    </td>
                    <td class="product-price">200,000₫</td>
                    <td><input type="number" value="2" min="1"></td>
                    <td class="item-total">400,000₫</td>
                    <td><span class="remove-item">Xóa</span></td>
                </tr>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="summary-box">
                <h2>Tóm tắt đơn hàng</h2>
                <p><span>Tạm tính:</span> <span class="subtotal-value">550,000₫</span></p>
                <p><span>Phí vận chuyển:</span> <span>Miễn phí</span></p>
                <hr>
                <p class="total"><span>Tổng cộng:</span> <span class="total-value">550,000₫</span></p>
                <form action="../process/process_order.php" method="POST">
                   <button type="submit" class="checkout-button">Đặt hàng</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/javascript/cart.js"></script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>