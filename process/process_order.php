<?php
session_start();
include '../includes/db_connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit();
}

// Kiểm tra xem dữ liệu giỏ hàng đã được gửi qua POST chưa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cart_data'])) {
    $cart_data_json = $_POST['cart_data'];
    $cart = json_decode($cart_data_json, true);

    // Kiểm tra xem dữ liệu giỏ hàng có hợp lệ không
    if (empty($cart) || !is_array($cart)) {
        // Giỏ hàng trống hoặc dữ liệu không hợp lệ, chuyển hướng về trang giỏ hàng với thông báo lỗi
        header("Location: cart.php?message=" . urlencode("Giỏ hàng trống hoặc dữ liệu không hợp lệ."));
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $total_amount = 0;

    // Tính tổng tiền đơn hàng và kiểm tra dữ liệu sản phẩm
    foreach ($cart as $item) {
        // Kiểm tra các trường cần thiết của mỗi sản phẩm trong giỏ hàng
        if (!isset($item['id'], $item['price'], $item['quantity']) ||
            !is_numeric($item['price']) || $item['price'] < 0 ||
            !is_numeric($item['quantity']) || $item['quantity'] < 1) {
            // Dữ liệu sản phẩm không hợp lệ, chuyển hướng về trang giỏ hàng với thông báo lỗi
            header("Location: cart.php?message=" . urlencode("Dữ liệu sản phẩm trong giỏ hàng không hợp lệ."));
            exit();
        }
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Bắt đầu transaction
    mysqli_begin_transaction($conn);

    try {
        // Chèn dữ liệu vào bảng orders
        $sql_order = "INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())";
        $stmt_order = mysqli_prepare($conn, $sql_order);
        mysqli_stmt_bind_param($stmt_order, "id", $user_id, $total_amount);
        mysqli_stmt_execute($stmt_order);

        // Lấy ID của đơn hàng vừa được chèn
        $order_id = mysqli_insert_id($conn);

        // Chèn dữ liệu vào bảng order_items
        $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_order_item = mysqli_prepare($conn, $sql_order_item);

        foreach ($cart as $item) {
            // Gán tham số và thực thi truy vấn cho từng sản phẩm
            mysqli_stmt_bind_param($stmt_order_item, "iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            mysqli_stmt_execute($stmt_order_item);
        }

        // Commit transaction
        mysqli_commit($conn);

        // Đơn hàng đã được lưu thành công, xóa giỏ hàng trong localStorage (sẽ được xử lý bởi cart.js khi tải lại trang)
        // và chuyển hướng đến trang xác nhận đơn hàng hoặc trang chủ
        header("Location: ../pages/order_success.php"); // Chuyển hướng đến trang thông báo đặt hàng thành công
        exit();

    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction nếu có lỗi
        mysqli_rollback($conn);
        // Ghi log lỗi (trong môi trường production)
        echo "Lỗi: " . $exception->getMessage(); // Chỉ hiển thị lỗi trong môi trường dev
        error_log("Process Order Error: " . $exception->getMessage()); // Log the error
        // $message = "Đã xảy ra lỗi khi xử lý đơn hàng. Vui lòng thử lại.";
        // header("Location: cart.php?message=" . urlencode("Đã xảy ra lỗi khi xử lý đơn hàng. Vui lòng kiểm tra log hệ thống để biết chi tiết.")); // Provide a generic message for the user
        // exit(); // Comment out exit to ensure echo is processed
    } finally {
        // Đóng statement
        if (isset($stmt_order)) {
            mysqli_stmt_close($stmt_order);
        }
        if (isset($stmt_order_item)) {
            mysqli_stmt_close($stmt_order_item);
        }
        // Đóng kết nối cơ sở dữ liệu
        mysqli_close($conn);
    }

} else {
    // Truy cập trực tiếp hoặc không có dữ liệu giỏ hàng, chuyển hướng về trang giỏ hàng
    header("Location: cart.php");
    exit();
}
?>