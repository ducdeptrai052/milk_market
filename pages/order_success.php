<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            background-color: #f4f4f4;
            text-align: center;
        }
        .success-message {
            padding: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            font-size: 1.2em;
        }
    </style>
    <meta http-equiv="refresh" content="3;url=index.php"> 
</head>
<body>
    <div class="success-message">
        <p>Đơn hàng của bạn đã được đặt thành công!</p>
        <p>Đơn hàng sẽ sớm được giao đến quý khách.</p>
        <p>Bạn sẽ được chuyển hướng về trang chủ sau vài giây...</p>
    </div>
    <script>
        // Clear the cart from localStorage
        localStorage.removeItem('cart');

        // Attempt to update the header cart count immediately
        const cartCountElement = document.querySelector('.header-actions .cart .cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = '0';
        }
    </script>
</body>
</html>