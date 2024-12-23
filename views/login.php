<?php
session_start(); // Bắt đầu phiên làm việc
require_once '../configs/db.php'; // Kết nối với cơ sở dữ liệu

try {
    // Kết nối cơ sở dữ liệu
    $pdo = new PDO("mysql:host=localhost;port=3307;dbname=btl_web", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Kiểm tra username có tồn tại không
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Lấy dữ liệu người dùng
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin người dùng vào session
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Điều hướng theo vai trò
                if ($user['role'] === 'student') {
                    header("Location: ../views/student_dashboard.php");
                } elseif ($user['role'] === 'education_office') {
                    header("Location: ../views/education_office_dashboard.php");
                }
                exit();
            } else {
                $error_message = "Mật khẩu không đúng.";
            }
        } else {
            $error_message = "Tên đăng nhập không tồn tại.";
        }
    }
} catch (PDOException $e) {
    $error_message = "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
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
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #f7f7f7;
        }
        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-size: 16px;
            color: #ddd;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="text"], input[type="password"] {
            background: #f1f1f1;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            background: #6a11cb;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #4e54c8;
        }
        p {
            margin-top: 10px;
            font-size: 14px;
            color: #f1f1f1;
        }
        a {
            color: #ffd700;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đăng Nhập</h1>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Đăng Nhập</button>
        </form>
        <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
</body>
</html>
