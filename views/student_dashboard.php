<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['id'];
$user_role = $_SESSION['role'];

// Kết nối đến cơ sở dữ liệu
require_once '../configs/db.php';

try {
    $pdo = new PDO("mysql:host=localhost;port=3307;dbname=btl_web", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, subject FROM grades WHERE student_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi kết nối hoặc truy vấn: " . $e->getMessage();
    $results = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Sinh Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #4e54c8, #8f94fb);
            color: #333;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        header {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 20px;
        }
        header a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            background: #007bff;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        header a:hover {
            background: #0056b3;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tr:hover {
            background: #f1f1f1;
        }
        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bảng Điều Khiển Sinh Viên</h1>
        <a href="../controllers/logout.php">Đăng Xuất</a>
    </header>
    <div class="container">
        <h1>Chào mừng, sinh viên!</h1>
        <p>Thông tin người dùng:</p>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($user_id); ?></p>
        <p><strong>Vai trò:</strong> <?php echo htmlspecialchars($user_role); ?></p>

        <h2>Danh sách môn học</h2>
        <div>
            <?php if (!empty($results)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Môn học</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có dữ liệu cho người dùng này.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>