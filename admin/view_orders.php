<?php
// Bắt đầu session để truy cập thông tin người dùng
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa và có phải là admin không
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Nếu chưa đăng nhập hoặc không phải admin, chuyển hướng về trang đăng nhập
    header("Location: ../pages/login.php");
    exit();
}

// Nhúng tệp kết nối cơ sở dữ liệu
include '../includes/db_connect.php';

// --- Logic lấy danh sách đơn hàng ---
// Truy vấn lấy tất cả đơn hàng, join với bảng users để lấy tên người dùng
$sql = "SELECT o.order_id, o.user_id, u.username, o.total_amount, o.order_date
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.order_date DESC"; // Sắp xếp theo ngày đặt hàng mới nhất

$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn cơ sở dữ liệu: " . mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem Đơn hàng - Admin - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <!-- Add specific CSS for this page if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .admin-content-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-content-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        .order-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
         .order-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
         }
        .order-table td a {
            text-decoration: none;
            color: #007bff;
        }
         .order-table td a:hover {
            text-decoration: underline;
         }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="admin-content-container">
        <h1>Xem Đơn hàng</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID Đơn hàng</th>
                        <th>Người đặt hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt hàng</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo number_format($row['total_amount'], 0, ',', '.'); ?> đ</td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><a href="order_detail.php?id=<?php echo $row['order_id']; ?>">Xem chi tiết</a></td> <!-- Link to order detail page -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Không có đơn hàng nào trong cơ sở dữ liệu.</p>
        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Add specific JS for this page if needed -->
    <!-- <script src="../assets/javascript/view-orders.js"></script> -->
</body>
</html>