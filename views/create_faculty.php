<?php
// Kết nối DB
require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Xử lý form tạo mới khoa
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Kiểm tra dữ liệu đầu vào
    if ($name) {
        try {
            $sql = "INSERT INTO faculties (name, description) VALUES (:name, :description)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                $message = "Khoa đã được tạo thành công!";
            } else {
                $message = "Lỗi khi tạo khoa.";
            }
        } catch (PDOException $e) {
            $message = "Lỗi khi kết nối cơ sở dữ liệu: " . $e->getMessage();
        }
    } else {
        $message = "Vui lòng nhập tên khoa.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Khoa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thêm Khoa</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="create_faculty.php" method="POST">
            <label for="name">Tên Khoa</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Mô Tả Khoa</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <button type="submit">Thêm Khoa</button>
        </form>
    </div>
</body>
</html>
