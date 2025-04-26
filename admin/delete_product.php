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

// Kiểm tra xem có ID sản phẩm được truyền qua URL không
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = $_GET['id'];

    // Lấy đường dẫn hình ảnh của sản phẩm trước khi xóa
    $sql_get_image = "SELECT image FROM products WHERE id = ?";
    $stmt_get_image = mysqli_prepare($conn, $sql_get_image);
    mysqli_stmt_bind_param($stmt_get_image, "i", $product_id);
    mysqli_stmt_execute($stmt_get_image);
    $result_get_image = mysqli_stmt_get_result($stmt_get_image);
    $row_image = mysqli_fetch_assoc($result_get_image);
    $image_path = $row_image['image'] ?? null;
    mysqli_stmt_close($stmt_get_image);

    // Chuẩn bị truy vấn SQL để xóa sản phẩm
    $sql_delete = "DELETE FROM products WHERE id = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);

    if ($stmt_delete) {
        // Gán tham số và thực thi truy vấn
        mysqli_stmt_bind_param($stmt_delete, "i", $product_id);

        if (mysqli_stmt_execute($stmt_delete)) {
            // Xóa sản phẩm thành công
            $message = '<div style="color: green;">Xóa sản phẩm thành công!</div>';

            // Xóa tệp hình ảnh nếu tồn tại (thêm tiền tố ../)
            if ($image_path && file_exists('../' . $image_path)) {
                unlink('../' . $image_path);
            }

        } else {
            // Lỗi khi xóa sản phẩm
            $message = '<div style="color: red;">Lỗi khi xóa sản phẩm: ' . mysqli_error($conn) . '</div>';
        }
        // Đóng statement
        mysqli_stmt_close($stmt_delete);
    } else {
        // Lỗi chuẩn bị statement
        $message = '<div style="color: red;">Lỗi hệ thống chuẩn bị truy vấn xóa.</div>';
    }
} else {
    // Không có ID sản phẩm được cung cấp
    $message = '<div style="color: red;">Không tìm thấy ID sản phẩm để xóa.</div>';
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);

// Chuyển hướng về trang quản lý sản phẩm sau khi xử lý
header("Location: manage_products.php?message=" . urlencode($message));
exit();
?>