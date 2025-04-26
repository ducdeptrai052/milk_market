<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Milk Market</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css"> <!-- Link CSS cho header -->
    <link rel="stylesheet" href="../assets/css/footer.css"> <!-- Link CSS cho footer -->
    <link rel="stylesheet" href="../assets/css/login.css"> <!-- Link CSS cho trang login -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

     <div class="login-container">
        <h2>Đăng nhập</h2>
        <form id="loginForm" action="../process/process_auth.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username_email">Tên đăng nhập hoặc Email:</label>
                <input type="text" id="username_email" name="username_email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Đăng nhập</button>
            <div class="form-footer">
                <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
                <p><a href="forgot-password.php">Quên mật khẩu?</a></p>
            </div>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/javascript/login.js"></script> <!-- Update JS path -->
</body>
</html>