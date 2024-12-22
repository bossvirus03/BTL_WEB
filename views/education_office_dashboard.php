<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Phòng giáo dục</title>
</head>
<body>
    <h1>Chào mừng, <?= htmlspecialchars($_SESSION['user']) ?></h1>
    <h2>Chức năng:</h2>
    <ul>
        <li><a href="admin_create_account.php">Tạo tài khoản sinh viên</a></li>
        <li><a href="student_reports.php">Xem báo cáo sinh viên</a></li>
    </ul>
    <a href="../controllers/logout.php">Đăng xuất</a>
</body>
</html>
