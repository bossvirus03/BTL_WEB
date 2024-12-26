<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Handle delete request
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $deleteQuery = "DELETE FROM users WHERE id = :id AND role = 'student'";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);//PDO::PARAM_INT đảm bảo kiểu dữ liệu truyền vào là int
    $stmt->execute();
    header("Location: student_list.php");
    exit();
}

// Lấy danh sách sinh viên
$query = "SELECT id, username, email, created_at FROM users WHERE role = 'student'";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC làm cho kết quả truy vấn trả về dưới dạng mảng kết hợp (associative array)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #343a40;
            margin-top: 20px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a {
            padding: 5px 10px;
            border: 1px solid #007bff;
            border-radius: 4px;
            text-align: center;
        }
        .actions a.delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .actions a.edit {
            background-color: #ffc107;
            color: black;
        }
        .container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Danh sách sinh viên</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                    <td><?= htmlspecialchars($student['username']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['created_at']) ?></td>
                    <td>
                        <div class="actions">
                            <a href="edit_student.php?id=<?= htmlspecialchars($student['id']) ?>" class="edit">Sửa</a>
                            <a href="student_list.php?delete_id=<?= htmlspecialchars($student['id']) ?>" class="delete" onclick="return confirm('Bạn có chắc chắn muốn xoá sinh viên này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="container">
        <a href="education_office_dashboard.php">Quay lại dashboard</a>
    </div>
</body>
</html>
