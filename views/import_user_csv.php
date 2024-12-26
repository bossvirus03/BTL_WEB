<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

include_once '../configs/db.php';

// Create an instance of the Database class
$database = new Database();
$db = $database->getConnection();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem tệp CSV đã được tải lên hay chưa
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $csvFile = $_FILES['csv_file']['tmp_name'];

        // Mở tệp CSV
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Đọc dòng đầu tiên (header) và bỏ qua nếu cần
            $header = fgetcsv($handle);

            // Chuẩn bị truy vấn SQL để chèn dữ liệu
            $sql = "INSERT INTO users (name, username, email, password, role) 
                    VALUES (:name, :username, :email, :password, :role)";
            $stmt = $db->prepare($sql);

            // Duyệt qua từng dòng trong tệp CSV
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $name = $data[0];
                $username = $data[1];
                $email = $data[2];
                $password = $data[3]; // Giả định mật khẩu trong CSV là plaintext
                $role = $data[4];

                // Hash mật khẩu
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Gán dữ liệu cho câu lệnh chuẩn bị
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':role', $role);

                // Thực thi câu lệnh
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    $message = "Lỗi khi chèn dữ liệu: " . $e->getMessage();
                    break;
                }
            }
            fclose($handle);
            if (!$message) {
                $message = "Dữ liệu người dùng đã được nhập thành công!";
            }
        } else {
            $message = "Không thể mở tệp CSV.";
        }
    } else {
        $message = "Vui lòng tải lên một tệp CSV hợp lệ.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Người Dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
        }
        input[type="file"] {
            margin: 10px 0;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            color: green;
            text-align: center;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nhập Người Dùng</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="csv_file" accept=".csv" required>
            <button type="submit">Nhập CSV</button>
        </form>
        <?php if ($message): ?>
            <p class="<?= strpos($message, 'Lỗi') !== false ? 'error' : 'message' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>