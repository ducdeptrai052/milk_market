<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Shop</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
</head>
<body>
    <?php include '../includes/header.php'; ?>
 
     <section class="banner">
        <div class="container">
            <div class="banner-slider">
                <div class="banner-items">
                    <img src="../assets/image/banner.png" alt="Banner" class="slide">
                    <img src="../assets/image/banner2.png" alt="Banner" class="slide">
                    <img src="../assets/image/banner.png" alt="Banner" class="slide">
                </div>
                <div class="slider-dots"></div>
            </div>
        </div>
    </section>
 
    <section class="featured-categories">
        <div class="container">
            <h2>KHUYẾN MẠI NỔI BẬT</h2>
            <div class="product-list">
                <div class="product-item" data-product-id="P001" data-product-name="Product Title 1" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P001">
                        <img src="../assets/image/product1.png" alt="Product 1">
                        <h3>Product Title 1</h3>
                       </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P002" data-product-name="Product Title 2" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P002">
                        <img src="../assets/image/product1.png" alt="Product 2">
                        <h3>Product Title 2</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P003" data-product-name="Product Title 3" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P003">
                       <img src="../assets/image/product1.png" alt="Product 3">
                       <h3>Product Title 3</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
            </div>
 
              <h2>SẢN PHẨM HOT</h2>
               <div class="product-list">
                  <div class="product-item" data-product-id="P004" data-product-name="Product Title 4" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P004">
                       <img src="../assets/image/product1.png" alt="Product 4">
                       <h3>Product Title 4</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P005" data-product-name="Product Title 5" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P005">
                        <img src="../assets/image/product1.png" alt="Product 5">
                        <h3>Product Title 5</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P006" data-product-name="Product Title 6" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P006">
                       <img src="../assets/image/product1.png" alt="Product 6">
                       <h3>Product Title 6</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
               </div>
 
            <h2>SẢN PHẨM MỚI</h2>
               <div class="product-list">
                  <div class="product-item" data-product-id="P007" data-product-name="Product Title 7" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P007">
                       <img src="../assets/image/product1.png" alt="Product 7">
                       <h3>Product Title 7</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P008" data-product-name="Product Title 8" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P008">
                        <img src="../assets/image/product1.png" alt="Product 8">
                        <h3>Product Title 8</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P009" data-product-name="Product Title 9" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P009">
                       <img src="../assets/image/product1.png" alt="Product 9">
                       <h3>Product Title 9</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
               </div>
        </div>
    </section>
 
    <section class="product-categories">
        <div class="container">
            <h2>SẢN PHẨM DÀNH CHO BẠN</h2>
            <div class="product-list">
                 <div class="product-item" data-product-id="P010" data-product-name="Product Title 10" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P010">
                       <img src="../assets/image/product1.png" alt="Product 10">
                       <h3>Product Title 10</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P011" data-product-name="Product Title 11" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P011">
                        <img src="../assets/image/product1.png" alt="Product 11">
                        <h3>Product Title 11</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P012" data-product-name="Product Title 12" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P012">
                       <img src="../assets/image/product1.png" alt="Product 12">
                       <h3>Product Title 12</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 </div>
             <h2>SỮA TƯƠI</h2>
            <div class="product-list">
                 <div class="product-item" data-product-id="P013" data-product-name="Product Title 13" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P013">
                       <img src="../assets/image/product1.png" alt="Product 13">
                       <h3>Product Title 13</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P014" data-product-name="Product Title 14" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P014">
                        <img src="../assets/image/product1.png" alt="Product 14">
                        <h3>Product Title 14</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P015" data-product-name="Product Title 15" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P015">
                       <img src="../assets/image/product1.png" alt="Product 15">
                       <h3>Product Title 15</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 </div>
             <h2>SỮA CHUA TỰ NHIÊN</h2>
            <div class="product-list">
                 <div class="product-item" data-product-id="P016" data-product-name="Product Title 16" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P016">
                       <img src="../assets/image/product1.png" alt="Product 16">
                       <h3>Product Title 16</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P017" data-product-name="Product Title 17" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P017">
                        <img src="../assets/image/product1.png" alt="Product 17">
                        <h3>Product Title 17</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P018" data-product-name="Product Title 18" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P018">
                       <img src="../assets/image/product1.png" alt="Product 18">
                       <h3>Product Title 18</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 </div>
             <h2>NƯỚC TRÁI CÂY TỰ NHIÊN</h2>
            <div class="product-list">
                 <div class="product-item" data-product-id="P019" data-product-name="Product Title 19" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P019">
                       <img src="../assets/image/product1.png" alt="Product 19">
                       <h3>Product Title 19</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P020" data-product-name="Product Title 20" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P020">
                        <img src="../assets/image/product1.png" alt="Product 20">
                        <h3>Product Title 20</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P021" data-product-name="Product Title 21" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P021">
                       <img src="../assets/image/product1.png" alt="Product 21">
                       <h3>Product Title 21</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 </div>
             <h2>NƯỚC UỐNG SỮA TRÁI CÂY</h2>
            <div class="product-list">
                 <div class="product-item" data-product-id="P022" data-product-name="Product Title 22" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P022">
                       <img src="../assets/image/product1.png" alt="Product 22">
                       <h3>Product Title 22</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P023" data-product-name="Product Title 23" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P023">
                        <img src="../assets/image/product1.png" alt="Product 23">
                        <h3>Product Title 23</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P024" data-product-name="Product Title 24" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P024">
                       <img src="../assets/image/product1.png" alt="Product 24">
                       <h3>Product Title 24</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 </div>
             <h2>KEM TỪ SỮA TƯƠI NGUYÊN CHẤT</h2>
            <div class="product-list">
                 <div class="product-item" data-product-id="P025" data-product-name="Product Title 25" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P025">
                       <img src="../assets/image/product1.png" alt="Product 25">
                       <h3>Product Title 25</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                <div class="product-item" data-product-id="P026" data-product-name="Product Title 26" data-product-price="25000" data-product-image="../assets/image/product1.png">
                    <a href="product-detail.php?id=P026">
                        <img src="../assets/image/product1.png" alt="Product 26">
                        <h3>Product Title 26</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 <div class="product-item" data-product-id="P027" data-product-name="Product Title 27" data-product-price="25000" data-product-image="../assets/image/product1.png">
                   <a href="product-detail.php?id=P027">
                       <img src="../assets/image/product1.png" alt="Product 27">
                       <h3>Product Title 27</h3>
                    </a>
                    <p class="price">25.000 đ</p>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" class="quantity-input">
                        <button class="add-to-cart-btn">Chọn mua</button>
                    </div>
                </div>
                 </div>
        </div>
    </section>
 
    <?php include '../includes/footer.php'; ?>
 
    <script src="../assets/javascript/index.js"></script>
</body>
</html>