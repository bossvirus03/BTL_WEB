<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Lấy thông tin sinh viên
$query = "SELECT s.name, s.gpa, s.classification 
          FROM students s 
          JOIN users u ON s.user_id = u.id 
          WHERE u.username = :username";

$stmt = $conn->prepare($query);
$stmt->bindParam(':username', $_SESSION['user']);
$stmt->execute();

// Kiểm tra kết quả
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Không tìm thấy thông tin sinh viên.";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bảng điểm</title>
</head>
<body>
    <h1>Chào mừng, <?= htmlspecialchars($_SESSION['user']) ?></h1>
    <h2>Thông tin học tập</h2>
    <p>Tên: <?= htmlspecialchars($student['name']) ?></p>
    <p>GPA: <?= htmlspecialchars($student['gpa']) ?></p>
    <p>Xếp loại: <?= htmlspecialchars($student['classification']) ?></p>
    <a href="../controllers/logout.php">Đăng xuất</a>
</body>
</html>
