<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: student_list.php"); // Chuyển hướng nếu đã đăng nhập
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng Nhập</title>
</head>
<body>
    <h1>Đăng Nhập</h1>
    <form method="POST" action="../controllers/login.php">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Đăng Nhập</button>
    </form>
    <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</body>
</html>