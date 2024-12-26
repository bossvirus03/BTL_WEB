<?php
session_start();
require_once '../configs/db.php';

$database = new Database();
$db = $database->getConnection();
try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Kiểm tra mật khẩu nhập lại
        if ($password !== $confirm_password) {
            $error_message = "Mật khẩu không khớp.";
        } else {
            // Kiểm tra username đã tồn tại chưa
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $usernameExists = $stmt->fetchColumn();

            if ($usernameExists > 0) {
                $error_message = "Tên đăng nhập đã tồn tại.";
            } else {
                // Mã hóa mật khẩu
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Thêm người dùng mới
                $stmt = $db->prepare("INSERT INTO users (username, name, email, password, role) VALUES (:username, :name, :email, :password, 'student')");//Chuẩn bị một câu lệnh SQL để thực thi.
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

                /*
                $stmt->bindParam(':placeholder', $variable, PDO::PARAM_TYPE)
                Mục đích: Gán giá trị cho các placeholder trong câu lệnh SQL.
                Chi tiết:
                    :placeholder: Tên của placeholder được sử dụng trong câu lệnh SQL.
                    $variable: Biến chứa giá trị thực tế để gán vào placeholder.
                        PDO::PARAM_TYPE: Loại dữ liệu của giá trị, ví dụ:
                        PDO::PARAM_STR: Chuỗi (string).
                        PDO::PARAM_INT: Số nguyên (integer).
                        PDO::PARAM_BOOL: Boolean.
                */ 

                if ($stmt->execute()) {
                    $success_message = "Đăng ký thành công!";
                } else {
                    $error_message = "Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.";
                }
            }
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
    <title>Đăng Ký</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px 50px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            margin-bottom: 25px;
            font-size: 32px;
            color: #fff;
            font-weight: bold;
        }

        label {
            display: block;
            text-align: left;
            margin: 15px 0 5px;
            font-size: 14px;
            color: #ddd;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            background: #f9f9f9;
            color: #333;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input:focus {
            outline: none;
            border: 2px solid #667eea;
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.7);
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            background: #6a11cb;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        button:hover {
            background: #764ba2;
            transform: scale(1.05);
        }

        p {
            margin-top: 15px;
            font-size: 14px;
            color: #ddd;
        }

        a {
            color: #ffd700;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #28a745;
            color: #fff;
        }

        .error {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đăng Ký</h1>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
            <a href='../views/login.php'>Đăng nhập ngay</a>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>

            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Nhập email" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>

            <label for="confirm_password">Xác nhận mật khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>

            <button type="submit">Đăng Ký</button>
        </form>
        <p>Đã có tài khoản? <a href="../views/login.php">Đăng nhập</a></p>
    </div>
</body>
</html>
