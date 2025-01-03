<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Giáo Dục</title>
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 32px;
            color: #f7f7f7;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
        }
        ul li a {
            width: 80%;
            font-size: 18px;
            color: #ffd700;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            background-color: #6a11cb;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        ul li a:hover {
            background-color: #4e54c8;
        }
        .logout {
            margin-top: 20px;
            font-size: 16px;
        }
        .logout a {
            color: #f1f1f1;
            text-decoration: none;
            background-color: #dc3545;
            padding: 10px;
            border-radius: 5px;
        }
        .logout a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chào mừng, <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <h2>Chức năng:</h2>
        <ul style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
            <!-- <li><a href="admin_create_account.php">Tạo tài khoản sinh viên, phòng đào tạo</a></li> -->
            <li><a href="create_subject.php">Tạo Môn học</a></li>
            <li><a href="create_major.php">Tạo Ngành học</a></li>
            <li><a href="list_majors.php">danh sách Ngành học</a></li>
            <li><a href="create_faculty.php">Tạo Khoa</a></li>
            <li><a href="list_faculties.php">danh sách Khoa</a></li>
            <li><a href="set_grades.php">Set điểm cho sinh viên</a></li>
            <li><a href="search.php">Tìm sinh viên</a></li>
            <li><a href="student_list.php">Danh sách sinh viên</a></li>
            <li><a href="import_user_csv.php">Import Sinh viên từ CSV</a></li>
            <li><a href="export_students.php">Export PDF</a></li>
            <li><a href="statistical.php">Thống kê</a></li>
        </ul>

        <div class="logout">
            <a href="./logout.php">Đăng xuất</a>
        </div>
    </div>
</body>
</html>
