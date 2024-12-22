<!DOCTYPE html>
<html>
<head>
    <title>Đăng Ký</title>
</head>
<body>
    <h1>Đăng Ký</h1>
    <form method="POST" action="../controllers/register.php">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="confirm_password">Xác nhận mật khẩu:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        
        <button type="submit">Đăng Ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</body>
</html>
