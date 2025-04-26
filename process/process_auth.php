<?php
// Bắt đầu session để quản lý trạng thái đăng nhập
session_start();

// Nhúng tệp kết nối cơ sở dữ liệu
include '../includes/db_connect.php';

// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// Mảng để lưu kết quả trả về
$response = array('status' => 'error', 'message' => 'Yêu cầu không hợp lệ.');

// Kiểm tra xem yêu cầu có phải là POST không
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Kiểm tra xem hành động (login/register) có được gửi không
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'login') {
            // --- Xử lý Đăng nhập ---
            $username_email = $_POST['username_email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Kiểm tra dữ liệu đầu vào
            if (empty($username_email) || empty($password)) {
                $response['message'] = "Vui lòng nhập tên đăng nhập/email và mật khẩu.";
            } else {
                // Chuẩn bị truy vấn SQL để tìm người dùng theo username hoặc email
                // Sử dụng prepared statement để ngăn SQL Injection
                $sql = "SELECT id, username, password, role FROM users WHERE username = ? OR email = ?";
                $stmt = mysqli_prepare($conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        // Tìm thấy người dùng, kiểm tra mật khẩu
                        if (password_verify($password, $row['password'])) {
                            // Mật khẩu đúng, tạo session đăng nhập
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['user_role'] = $row['role']; // Lưu vai trò người dùng vào session

                            // Đăng nhập thành công, trả về JSON
                            $response['status'] = 'success';
                            $response['message'] = 'Đăng nhập thành công!';
                            $response['role'] = $row['role']; // Trả về vai trò để JS xử lý chuyển hướng

                        } else {
                            // Mật khẩu sai
                            $response['message'] = "Sai mật khẩu.";
                        }
                    } else {
                        // Không tìm thấy người dùng
                        $response['message'] = "Tên đăng nhập hoặc email không tồn tại.";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    // Lỗi chuẩn bị statement
                    $response['message'] = "Lỗi hệ thống. Vui lòng thử lại sau.";
                }
            }

        } elseif ($action == 'register') {
            // --- Xử lý Đăng ký ---
            // Logic xử lý đăng ký vẫn giữ nguyên, trả về JSON
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Kiểm tra dữ liệu đầu vào
            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                $response['message'] = "Vui lòng điền đầy đủ thông tin.";
            } elseif ($password !== $confirm_password) {
                $response['message'] = "Mật khẩu xác nhận không khớp.";
            } else {
                 // Kiểm tra xem username hoặc email đã tồn tại chưa
                 $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
                 $stmt_check = mysqli_prepare($conn, $sql_check);

                 if ($stmt_check) {
                     mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
                     mysqli_stmt_execute($stmt_check);
                     mysqli_stmt_store_result($stmt_check);

                     if (mysqli_stmt_num_rows($stmt_check) > 0) {
                         $response['message'] = "Tên đăng nhập hoặc email đã tồn tại.";
                     } else {
                         // Mã hóa mật khẩu
                         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                         // Chèn người dùng mới
                         $sql_insert = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
                         $stmt_insert = mysqli_prepare($conn, $sql_insert);

                         if ($stmt_insert) {
                             mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $hashed_password);

                             if (mysqli_stmt_execute($stmt_insert)) {
                                 $response['status'] = 'success';
                                 $response['message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                             } else {
                                 $response['message'] = "Lỗi khi đăng ký tài khoản. Vui lòng thử lại sau.";
                             }
                             mysqli_stmt_close($stmt_insert);
                         } else {
                             $response['message'] = "Lỗi hệ thống đăng ký. Vui lòng thử lại sau.";
                         }
                     }
                     mysqli_stmt_close($stmt_check);
                 } else {
                     $response['message'] = "Lỗi hệ thống kiểm tra trùng lặp. Vui lòng thử lại sau.";
                 }
             }

         } else {
             $response['message'] = "Hành động không xác định.";
         }
     } else {
         $response['message'] = "Không có hành động nào được chỉ định.";
     }
 }

 // Trả về kết quả dưới dạng JSON
 echo json_encode($response);

 // Đóng kết nối cơ sở dữ liệu
 mysqli_close($conn);
 ?>