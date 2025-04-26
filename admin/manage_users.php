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

// --- Logic lấy danh sách người dùng ---
$sql = "SELECT id, username, email, role, created_at FROM users ORDER BY id DESC"; // Truy vấn lấy tất cả người dùng
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
    <title>Quản lý Người dùng - Admin - Milk Market</title>
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
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        .user-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
         .action-links a:hover {
            text-decoration: underline;
         }
         /* Add styles for add new user button if needed */
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
        <h1>Quản lý Người dùng</h1>

        <a href="add_user.php" class="add-new-button">Thêm Người dùng Mới</a> <!-- Link to add new user processing script -->

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td class="action-links">
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>">Sửa</a> <!-- Link to edit user processing script -->
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?');">Xóa</a> <!-- Link to delete user processing script -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Không có người dùng nào trong cơ sở dữ liệu.</p>
        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Add specific JS for this page if needed -->
    <!-- <script src="../assets/javascript/manage-users.js"></script> -->

<?php
// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
?>
</body>
</html>