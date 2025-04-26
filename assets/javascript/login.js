document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => { // Use async function to use await
            // Ngăn form gửi đi theo cách truyền thống
            event.preventDefault();

            // Lấy dữ liệu từ form
            const usernameEmail = document.getElementById('username_email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Basic Client-side Validation
            if (usernameEmail === '' || password === '') {
                alert('Vui lòng nhập tên đăng nhập/email và mật khẩu.');
                return;
            }

            // Lấy dữ liệu form bao gồm cả trường hidden 'action'
            const formData = new FormData(loginForm);

            try {
                // Gửi dữ liệu đến process_auth.php bằng Fetch API
                const response = await fetch('/milk_market/process/process_auth.php', {
                    method: 'POST',
                    body: formData
                });

                // Kiểm tra nếu phản hồi không thành công (ví dụ: lỗi server 500)
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Phân tích phản hồi JSON
                const result = await response.json();

                // Xử lý kết quả từ server
                if (result.status === 'success') {
                    alert(result.message); // Hiển thị thông báo thành công

                    // Chuyển hướng dựa trên vai trò
                    if (result.role === 'admin') {
                        window.location.href = '/milk_market/admin/admin_dashboard.php'; // Chuyển hướng đến trang quản trị
                    } else {
                        window.location.href = '/milk_market/pages/index.php'; // Chuyển hướng đến trang chủ cho người dùng thường
                    }

                } else {
                    // Đăng nhập thất bại, hiển thị thông báo lỗi từ server
                    alert(result.message);
                }

            } catch (error) {
                // Xử lý lỗi khi gửi yêu cầu hoặc nhận phản hồi
                console.error('Lỗi khi đăng nhập:', error);
                alert('Đã xảy ra lỗi khi cố gắng đăng nhập. Vui lòng thử lại.');
            }

            // Không reset form ở đây để người dùng có thể sửa thông tin nếu sai
        });
    }
});