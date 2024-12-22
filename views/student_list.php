<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once '../configs/db.php';
require_once '../repositories/studentRepositories.php';

$db = new Database();
$conn = $db->getConnection();
$studentModel = new StudentModel($conn);
$students = $studentModel->getAllStudents();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sinh viên</title>
</head>
<body>
    <h1>Danh sách sinh viên</h1>
    <table border="1">
        <tr>
            <th>Mã SV</th>
            <th>Họ tên</th>
            <th>Ngày sinh</th>
            <th>Giới tính</th>
            <th>GPA</th>
        </tr>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['student_code'] ?></td>
                <td><?= $student['name'] ?></td>
                <td><?= $student['dob'] ?></td>
                <td><?= $student['gender'] ?></td>
                <td><?= $student['gpa'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
