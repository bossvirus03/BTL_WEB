<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "Tài khoản đã được tạo thành công!";
    } else {
        echo "Lỗi: Không thể tạo tài khoản.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tạo tài khoản</title>
</head>
<body>
    <h1>Tạo tài khoản mới</h1>
    <form method="POST">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" required><br><br>

        <label for="role">Vai trò:</label>
        <select name="role" required>
            <option value="student">Sinh viên</option>
            <option value="education_office">Phòng giáo dục</option>
        </select><br><br>

        <button type="submit">Tạo tài khoản</button>
    </form>
    <a href="../controllers/logout.php">Đăng xuất</a>
</body>
</html>
