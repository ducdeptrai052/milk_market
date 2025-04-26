<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <link rel="stylesheet" href="../assets/css/register.css"> <!-- Link CSS cho trang register -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="register-container">
        <h2>Đăng ký tài khoản mới</h2>
        <form id="registerForm" action="../process/process_auth.php" method="POST"> <!-- Action points to the processing script -->
            <input type="hidden" name="action" value="register"> <!-- Hidden field to identify form -->
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
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="register-button">Đăng ký</button>
            <div class="form-footer">
                <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p> <!-- Link back to login page -->
            </div>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/javascript/register.js"></script> <!-- Link JavaScript cho trang register -->
</body>
</html>