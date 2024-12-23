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
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Kiểm tra nếu tên đăng nhập đã tồn tại
    $query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $userCount = $stmt->fetchColumn();

    if ($userCount > 0) {
        $error_message = "Lỗi: Tên đăng nhập đã tồn tại.";
    } else {
        // Mã hóa mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Thêm tài khoản mới vào cơ sở dữ liệu
        $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $success_message = "Tài khoản đã được tạo thành công!";
        } else {
            $error_message = "Lỗi: Không thể tạo tài khoản.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #4e54c8, #8f94fb);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 32px;
            color: #f7f7f7;
        }
        label, select, input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        input, select {
            background: #f1f1f1;
        }
        button {
            background: #6a11cb;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #4e54c8;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tạo tài khoản mới</h1>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

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
    </div>
</body>
</html>
