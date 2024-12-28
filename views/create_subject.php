<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectName = trim($_POST['subject_name']);
    $description = trim($_POST['description']);

    if (!empty($subjectName)) {
        // Insert subject into the database
        $insertQuery = "INSERT INTO subjects (name, description, created_at, updated_at) 
                        VALUES (:name, :description, NOW(), NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':name', $subjectName, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $message = "Môn học đã được tạo thành công!";
        } else {
            $message = "Lỗi: Không thể tạo môn học!";
        }
    } else {
        $message = "Tên môn học không được để trống.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Môn Học</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        form {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333333;
        }

        label {
            font-size: 14px;
            color: #555555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #007bff;
            background-color: #ffffff;
            outline: none;
        }

        button {
            padding: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: green;
        }

        .message.error {
            color: red;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Tạo Môn Học</h2>
        <?php if (!empty($message)) : ?>
            <p class="message <?= strpos($message, 'Lỗi') !== false ? 'error' : '' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <label for="subject_name">Tên môn học</label>
        <input type="text" name="subject_name" id="subject_name" placeholder="Nhập tên môn học" required>

        <label for="description">Mô tả</label>
        <textarea name="description" id="description" rows="5" placeholder="Nhập mô tả (tùy chọn)"></textarea>

        <button type="submit">Tạo Môn Học</button>
        <div class="back-link">
            <a href="education_office_dashboard.php">Quay lại Bảng điều khiển</a>
        </div>
    </form>
</body>
</html>
