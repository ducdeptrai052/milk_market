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

// Nếu là admin, hiển thị nội dung trang quản lý sản phẩm

// --- Logic lấy danh sách sản phẩm ---
$sql = "SELECT id, name, price, image, description, category_id FROM products ORDER BY id DESC"; // Truy vấn lấy tất cả sản phẩm
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
    <title>Quản lý Sản phẩm - Admin - Milk Market</title>
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
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        .product-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .product-table img {
            max-width: 50px;
            height: auto;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
         .action-links a:hover {
            text-decoration: underline;
         }
         .add-new-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
         }
         .add-new-button:hover {
            background-color: #218838;
         }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="admin-content-container">
        <h1>Quản lý Sản phẩm</h1>

        <a href="add_product.php" class="add-new-button">Thêm Sản phẩm Mới</a> <!-- Link to add new product processing script -->

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Mô tả</th>
                        <th>Danh mục ID</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</td>
                            <td><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></td> <!-- Hiển thị 100 ký tự đầu -->
                            <td><?php echo $row['category_id']; ?></td>
                            <td class="action-links">
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>">Sửa</a> <!-- Link to edit product processing script -->
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">Xóa</a> <!-- Link to delete product processing script -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Không có sản phẩm nào trong cơ sở dữ liệu.</p>
        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Add specific JS for this page if needed -->
    <script src="../assets/javascript/manage-products.js"></script>

<?php
mysqli_close($conn);
?>
</body>
</html>