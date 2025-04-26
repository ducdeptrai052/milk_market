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
$user = null; // Biến để lưu thông tin người dùng cần sửa
$user_id = null;

// --- Logic lấy thông tin người dùng cần sửa ---
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql_get_user = "SELECT id, username, email, role FROM users WHERE id = ?";
    $stmt_get_user = mysqli_prepare($conn, $sql_get_user);
    mysqli_stmt_bind_param($stmt_get_user, "i", $user_id);
    mysqli_stmt_execute($stmt_get_user);
    $result_get_user = mysqli_stmt_get_result($stmt_get_user);

    if ($row = mysqli_fetch_assoc($result_get_user)) {
        $user = $row;
    } else {
        // Không tìm thấy người dùng với ID này
        $message = '<div style="color: red;">Không tìm thấy người dùng cần sửa.</div>';
        $user_id = null; // Đặt lại ID để không hiển thị form
    }
    mysqli_stmt_close($stmt_get_user);
} else {
    // Không có ID người dùng được cung cấp
    $message = '<div style="color: red;">Không có ID người dùng được cung cấp để sửa.</div>';
}

// --- Logic xử lý form sửa người dùng ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && $user_id !== null) {
    // Lấy dữ liệu từ form
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? ''; // Mật khẩu mới (có thể trống)
    $role = $_POST['role'] ?? 'user';

    // Kiểm tra dữ liệu đầu vào cơ bản
    if (empty($username) || empty($email)) {
        $message = '<div style="color: red;">Vui lòng điền đầy đủ tên đăng nhập và email.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div style="color: red;">Địa chỉ email không hợp lệ.</div>';
    } else {
        // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại với người dùng khác chưa
        $sql_check = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "ssi", $username, $email, $user_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $message = '<div style="color: red;">Tên đăng nhập hoặc email đã tồn tại với người dùng khác.</div>';
        } else {
            // Chuẩn bị truy vấn SQL để cập nhật người dùng
            $sql_update = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
            $params = [$username, $email, $role, $user_id];
            $types = "sssi";

            // Nếu có mật khẩu mới được cung cấp, cập nhật cả mật khẩu
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_update = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?";
                $params = [$username, $email, $hashed_password, $role, $user_id];
                $types = "ssssi";
            }

            $stmt_update = mysqli_prepare($conn, $sql_update);

            if ($stmt_update) {
                // Gán tham số và thực thi truy vấn
                mysqli_stmt_bind_param($stmt_update, $types, ...$params);

                if (mysqli_stmt_execute($stmt_update)) {
                    // Cập nhật thành công
                    $message = '<div style="color: green;">Cập nhật người dùng thành công!</div>';
                    // Cập nhật lại thông tin người dùng sau khi sửa để hiển thị trên form
                    $sql_get_user_updated = "SELECT id, username, email, role FROM users WHERE id = ?";
                    $stmt_get_user_updated = mysqli_prepare($conn, $sql_get_user_updated);
                    mysqli_stmt_bind_param($stmt_get_user_updated, "i", $user_id);
                    mysqli_stmt_execute($stmt_get_user_updated);
                    $result_get_user_updated = mysqli_stmt_get_result($stmt_get_user_updated);
                     if ($row_updated = mysqli_fetch_assoc($result_get_user_updated)) {
                        $user = $row_updated;
                    }
                    mysqli_stmt_close($stmt_get_user_updated);

                } else {
                    // Lỗi khi cập nhật vào database
                    $message = '<div style="color: red;">Lỗi khi cập nhật người dùng vào cơ sở dữ liệu: ' . mysqli_error($conn) . '</div>';
                }
                // Đóng statement
                mysqli_stmt_close($stmt_update);
            } else {
                // Lỗi chuẩn bị statement
                $message = '<div style="color: red;">Lỗi hệ thống chuẩn bị truy vấn cập nhật.</div>';
            }
        }
        // Đóng statement kiểm tra
        mysqli_stmt_close($stmt_check);
    }
}


// Đóng kết nối cơ sở dữ liệu (sau khi xử lý form hoặc lấy dữ liệu)
if (isset($conn) && $conn) {
    mysqli_close($conn);
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Người dùng - Admin - Milk Market</title>
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
            background-color: #ffc107; /* Yellow color for edit */
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: #e0a800;
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
        <h1>Sửa Người dùng</h1>

        <?php echo $message; // Hiển thị thông báo ?>

        <?php if ($user): ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                 <div class="form-group">
                    <label for="password">Mật khẩu mới (để trống nếu không đổi):</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="role">Vai trò:</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>Người dùng</option>
                        <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="submit-button">Cập nhật Người dùng</button>
            </form>
        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Add specific JS for this page if needed -->
    <!-- <script src="../assets/javascript/edit-user.js"></script> -->

</body>
</html>