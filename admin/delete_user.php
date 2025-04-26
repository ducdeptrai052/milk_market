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

// Kiểm tra xem có ID người dùng được truyền qua URL không
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];

    // Ngăn không cho admin tự xóa tài khoản của mình
    if ($user_id == $_SESSION['user_id']) {
        $message = '<div style="color: red;">Bạn không thể xóa tài khoản của chính mình.</div>';
    } else {
        // Chuẩn bị truy vấn SQL để xóa người dùng
        $sql_delete = "DELETE FROM users WHERE id = ?";
        $stmt_delete = mysqli_prepare($conn, $sql_delete);

        if ($stmt_delete) {
            // Gán tham số và thực thi truy vấn
            mysqli_stmt_bind_param($stmt_delete, "i", $user_id);

            if (mysqli_stmt_execute($stmt_delete)) {
                // Xóa người dùng thành công
                $message = '<div style="color: green;">Xóa người dùng thành công!</div>';
            } else {
                // Lỗi khi xóa người dùng
                $message = '<div style="color: red;">Lỗi khi xóa người dùng: ' . mysqli_error($conn) . '</div>';
            }
            // Đóng statement
            mysqli_stmt_close($stmt_delete);
        } else {
            // Lỗi chuẩn bị statement
            $message = '<div style="color: red;">Lỗi hệ thống chuẩn bị truy vấn xóa người dùng.</div>';
        }
    }
} else {
    // Không có ID người dùng được cung cấp
    $message = '<div style="color: red;">Không tìm thấy ID người dùng để xóa.</div>';
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);

// Chuyển hướng về trang quản lý người dùng sau khi xử lý
header("Location: manage_users.php?message=" . urlencode($message));
exit();
?>