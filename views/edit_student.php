<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Get student details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT username, email, name FROM users WHERE id = :id AND role = 'student'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "Sinh viên không tồn tại!";
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $updateQuery = "UPDATE users SET username = :username, email = :email, name = :name WHERE id = :id AND role = 'student'";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':email', $name);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: student_list.php");
        exit();
    } else {
        echo "Cập nhật thất bại!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin sinh viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Sửa thông tin sinh viên</h2>
        <label for="username">Tên đăng nhập</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($student['username']) ?>" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        <button type="submit">Cập nhật</button>
        <div class="back-link">
            <a href="student_list.php">Quay lại danh sách</a>
        </div>
    </form>
</body>
</html>
