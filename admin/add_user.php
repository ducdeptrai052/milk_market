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

$message = ''; // Biến để lưu thông báo

// --- Logic xử lý form thêm người dùng ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user'; // Mặc định là 'user' nếu không được chọn

    // Kiểm tra dữ liệu đầu vào cơ bản
    if (empty($username) || empty($email) || empty($password)) {
        $message = '<div style="color: red;">Vui lòng điền đầy đủ tên đăng nhập, email và mật khẩu.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div style="color: red;">Địa chỉ email không hợp lệ.</div>';
    } else {
        // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
        $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $message = '<div style="color: red;">Tên đăng nhập hoặc email đã tồn tại.</div>';
        } else {
            // Hash mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Chuẩn bị truy vấn SQL để chèn người dùng mới
            $sql_insert = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $sql_insert);

            if ($stmt_insert) {
                // Gán tham số và thực thi truy vấn
                mysqli_stmt_bind_param($stmt_insert, "ssss", $username, $email, $hashed_password, $role);

                if (mysqli_stmt_execute($stmt_insert)) {
                    // Chèn thành công
                    $message = '<div style="color: green;">Thêm người dùng thành công!</div>';
                    // Tùy chọn: Chuyển hướng về trang quản lý người dùng sau vài giây
                    // header("Location: manage_users.php");
                    // exit();
                } else {
                    // Lỗi khi chèn vào database
                    $message = '<div style="color: red;">Lỗi khi thêm người dùng vào cơ sở dữ liệu: ' . mysqli_error($conn) . '</div>';
                }
                // Đóng statement
                mysqli_stmt_close($stmt_insert);
            } else {
                // Lỗi chuẩn bị statement
                $message = '<div style="color: red;">Lỗi hệ thống chuẩn bị truy vấn.</div>';
            }
        }
        // Đóng statement kiểm tra
        mysqli_stmt_close($stmt_check);
    }
     // Đóng kết nối cơ sở dữ liệu sau khi xử lý form
     mysqli_close($conn);
     // Mở lại kết nối để hiển thị form lại (nếu cần)
     include '../includes/db_connect.php';
}

// Đóng kết nối cơ sở dữ liệu (sau khi xử lý form)
if (isset($conn) && $conn) {
    mysqli_close($conn);
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người dùng Mới - Admin - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <!-- Add specific CSS for this page if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .admin-content-container {
            max-width: 800px;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .submit-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745; /* Green color for add */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: #218838;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="admin-content-container">
        <h1>Thêm Người dùng Mới</h1>

        <?php echo $message; // Hiển thị thông báo ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" required>
                    <option value="user">Người dùng</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="submit-button">Thêm Người dùng</button>
        </form>

    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Add specific JS for this page if needed -->
    <!-- <script src="../assets/javascript/add-user.js"></script> -->

</body>
</html>