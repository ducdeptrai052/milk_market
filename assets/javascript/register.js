document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', async (event) => { // Use async function
            // Ngăn form gửi đi theo cách truyền thống
            event.preventDefault();

            // Lấy dữ liệu từ form
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();

            // Basic Client-side Validation
            if (username === '' || email === '' || password === '' || confirmPassword === '') {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }

            if (password !== confirmPassword) {
                alert('Mật khẩu xác nhận không khớp.');
                return;
            }

            // Lấy dữ liệu form bao gồm cả trường hidden 'action'
            const formData = new FormData(registerForm);

            try {
                // Gửi dữ liệu đến process_auth.php bằng Fetch API
                const response = await fetch('/milk_market/process/process_auth.php', {
                    method: 'POST',
                    body: formData
                });

                // Kiểm tra nếu phản hồi không thành công
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Phân tích phản hồi JSON
                const result = await response.json();

                // Xử lý kết quả từ server
                alert(result.message); // Hiển thị thông báo từ server

                if (result.status === 'success') {
                    // Tùy chọn: Chuyển hướng đến trang đăng nhập sau khi đăng ký thành công
                    // window.location.href = '/milk_market/pages/login.php'; // Uncomment and use this line if you want to redirect
                    registerForm.reset(); // Reset form sau khi đăng ký thành công
                }

            } catch (error) {
                // Xử lý lỗi khi gửi yêu cầu hoặc nhận phản hồi
                console.error('Lỗi khi đăng ký:', error);
                alert('Đã xảy ra lỗi khi cố gắng đăng ký. Vui lòng thử lại.');
            }
        });
    }
});