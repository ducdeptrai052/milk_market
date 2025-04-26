<?php
// Bắt đầu session để truy cập thông tin người dùng
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa và có phải là admin không
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Admin - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .admin-dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-dashboard-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .admin-menu {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .admin-menu li a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.1em;
            transition: color 0.3s ease;
        }
        .admin-menu li a:hover {
            color: #0056b3;
        }
        /* .admin-content {
        } */
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="admin-dashboard-container">
        <h1>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p style="text-align: center;">Đây là bảng điều khiển quản trị.</p>

        <ul class="admin-menu">
            <li><a href="manage_products.php">Quản lý Sản phẩm</a></li>
            <li><a href="manage_users.php">Quản lý Người dùng</a></li>
            <li><a href="view_orders.php">Xem Đơn hàng</a></li>
            <!-- Add more admin links as needed -->
        </ul>

        <div class="admin-content">
            <!-- Content for managing products, users, orders, etc. will go here -->
            <p style="text-align: center;">Chọn một mục từ menu để bắt đầu quản lý.</p>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Add specific JS for admin dashboard if needed -->
    <!-- <script src="../assets/javascript/admin.js"></script> -->
</body>
</html>