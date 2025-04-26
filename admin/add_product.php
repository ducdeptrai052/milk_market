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

// Define the base upload directory relative to the web root
$base_upload_dir = 'assets/image/products/';
// Define the full upload directory path relative to this file
$upload_dir = '../' . $base_upload_dir;

$message = ''; // Biến để lưu thông báo

// --- Logic xử lý form thêm sản phẩm ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    // Kiểm tra dữ liệu đầu vào cơ bản
    if (empty($name) || empty($price) || empty($description) || empty($category_id) || !isset($_FILES['image'])) {
        $message = '<div style="color: red;">Vui lòng điền đầy đủ thông tin và chọn hình ảnh.</div>';
    } elseif (!is_numeric($price) || $price < 0) {
         $message = '<div style="color: red;">Giá sản phẩm không hợp lệ.</div>';
    } else {
        // Xử lý tải lên tệp hình ảnh
        $image = $_FILES['image'];
        $image_name = basename($image['name']);
        $image_tmp_name = $image['tmp_name'];
        $image_error = $image['error'];
        $image_size = $image['size'];

        // Kiểm tra lỗi tải lên
        if ($image_error !== UPLOAD_ERR_OK) {
            $message = '<div style="color: red;">Lỗi khi tải lên hình ảnh. Mã lỗi: ' . $image_error . '</div>';
        } else {
            // Kiểm tra loại tệp và kích thước (tùy chọn)
            $allowed_types = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            $file_type = mime_content_type($image_tmp_name);
            $file_extension = array_search($file_type, $allowed_types, true);

            if ($file_extension === false) {
                $message = '<div style="color: red;">Chỉ cho phép tệp hình ảnh (JPG, PNG, GIF).</div>';
            } elseif ($image_size > $max_size) {
                $message = '<div style="color: red;">Kích thước hình ảnh quá lớn (tối đa 5MB).</div>';
            } else {
                // Tạo tên tệp duy nhất để tránh trùng lặp
                $new_image_name = uniqid('', true) . "." . $file_extension;
                $upload_path_full = $upload_dir . $new_image_name; // Full path for saving file
                $image_path_db = $base_upload_dir . $new_image_name; // Path to save in DB

                 // Tạo thư mục upload nếu chưa tồn tại
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Di chuyển tệp tải lên vào thư mục đích
                if (move_uploaded_file($image_tmp_name, $upload_path_full)) {
                    // Tải lên thành công, chèn dữ liệu vào database

                    // Chuẩn bị truy vấn SQL để chèn sản phẩm mới
                    $sql_insert = "INSERT INTO products (name, price, image, description, category_id) VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert = mysqli_prepare($conn, $sql_insert);

                    if ($stmt_insert) {
                        // Gán tham số và thực thi truy vấn
                        // Sử dụng 'd' cho price (decimal/float), 's' cho các chuỗi, 'i' cho category_id (integer)
                        mysqli_stmt_bind_param($stmt_insert, "sdssi", $name, $price, $image_path_db, $description, $category_id);

                        if (mysqli_stmt_execute($stmt_insert)) {
                            // Chèn thành công
                            $message = '<div style="color: green;">Thêm sản phẩm thành công!</div>';
                            // Tùy chọn: Chuyển hướng về trang quản lý sản phẩm sau vài giây
                            // header("Location: manage_products.php");
                            // exit();
                        } else {
                            // Lỗi khi chèn vào database
                            $message = '<div style="color: red;">Lỗi khi thêm sản phẩm vào cơ sở dữ liệu: ' . mysqli_error($conn) . '</div>';
                            // Xóa tệp hình ảnh đã tải lên nếu chèn database thất bại
                            if (file_exists($upload_path_full)) {
                                unlink($upload_path_full);
                            }
                        }
                        // Đóng statement
                        mysqli_stmt_close($stmt_insert);
                    } else {
                        // Lỗi chuẩn bị statement
                        $message = '<div style="color: red;">Lỗi hệ thống chuẩn bị truy vấn.</div>';
                         // Xóa tệp hình ảnh đã tải lên nếu có lỗi
                         if (file_exists($upload_path_full)) {
                             unlink($upload_path_full);
                         }
                     }
                 } else {
                     // Lỗi di chuyển tệp
                     $message = '<div style="color: red;">Lỗi khi lưu tệp hình ảnh.</div>';
                 }
             }
         }
     }
      // Đóng kết nối cơ sở dữ liệu sau khi xử lý form
      mysqli_close($conn);
      // Mở lại kết nối để lấy danh mục cho form hiển thị lại
      include '../includes/db_connect.php';
 }


 // --- Logic lấy danh mục cho dropdown (nếu có bảng categories) ---
 $categories = [];
 // Kiểm tra xem kết nối có tồn tại không trước khi truy vấn
 if (isset($conn) && $conn) {
     $sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
     $result_categories = mysqli_query($conn, $sql_categories);

     if ($result_categories && mysqli_num_rows($result_categories) > 0) {
         while($row_category = mysqli_fetch_assoc($result_categories)) {
             $categories[] = $row_category;
         }
         mysqli_free_result($result_categories); // Giải phóng bộ nhớ
     }
 }


 // Đóng kết nối cơ sở dữ liệu (sau khi lấy danh mục)
 if (isset($conn) && $conn) {
     mysqli_close($conn);
 }

 ?>
 <!DOCTYPE html>
 <html lang="vi">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Thêm Sản phẩm Mới - Admin - Milk Market</title>
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
         .form-group input[type="number"],
         .form-group textarea,
         .form-group select {
             width: 100%;
             padding: 10px;
             border: 1px solid #ccc;
             border-radius: 4px;
             box-sizing: border-box;
         }
         .form-group textarea {
             height: 100px;
             resize: vertical; /* Allow vertical resizing */
         }
         .submit-button {
             display: inline-block;
             padding: 10px 20px;
             background-color: #28a745;
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
         <h1>Thêm Sản phẩm Mới</h1>

         <?php echo $message; // Hiển thị thông báo ?>

         <form action="" method="POST" enctype="multipart/form-data"> <!-- Add enctype for file uploads -->
             <div class="form-group">
                 <label for="name">Tên sản phẩm:</label>
                 <input type="text" id="name" name="name" required>
             </div>
             <div class="form-group">
                 <label for="price">Giá:</label>
                 <input type="number" id="price" name="price" step="0.01" required> <!-- Use step for decimal prices -->
             </div>
             <div class="form-group">
                 <label for="image">Hình ảnh:</label>
                 <input type="file" id="image" name="image" accept="image/*" required> <!-- Accept image files -->
             </div>
             <div class="form-group">
                 <label for="description">Mô tả:</label>
                 <textarea id="description" name="description" required></textarea>
             </div>
             <div class="form-group">
                 <label for="category_id">Danh mục:</label>
                 <select id="category_id" name="category_id" required>
                     <option value="">-- Chọn danh mục --</option>
                     <?php foreach ($categories as $category): ?>
                         <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                     <?php endforeach; ?>
                 </select>
             </div>
             <button type="submit" class="submit-button">Thêm Sản phẩm</button>
         </form>

     </div>

     <?php include '../includes/footer.php'; ?>

     <!-- Add specific JS for this page if needed -->
     <!-- <script src="../assets/javascript/add-product.js"></script> -->

 </body>
 </html>