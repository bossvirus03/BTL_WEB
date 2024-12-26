<?php
session_start();

// Kiểm tra quyền truy cập
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../configs/db.php'; // Kết nối DB
$db = new Database();
$conn = $db->getConnection();

$searchResults = [];
$searchQuery = '';

// Kiểm tra nếu người dùng đã nhập từ khóa tìm kiếm
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    
    // Truy vấn tìm kiếm sinh viên theo tên
    $query = "SELECT id, username, name, email, created_at FROM users WHERE role = 'student' AND (username LIKE :search OR name LIKE :search)";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', "%$searchQuery%", PDO::PARAM_STR);
    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm sinh viên</title>
    <style>
         body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 2rem;
            color: #4A90E2;
        }

        /* Form tìm kiếm */
        form {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        form input[type="text"] {
            padding: 10px;
            font-size: 1rem;
            width: 60%;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus {
            border-color: #4A90E2;
            outline: none;
        }

        form button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #357ABD;
        }

        /* Bảng kết quả */
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: #4A90E2;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        /* Thông báo không tìm thấy */
        .no-results {
            text-align: center;
            color: #dc3545;
            font-size: 1rem;
        }

        /* Thêm Padding và Canh giữa cho trang */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
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
    </style>
</head>
<body>
    <h1>Tìm kiếm sinh viên</h1>
    
    <!-- Form tìm kiếm -->
    <form method="GET" action="">
        <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Nhập tên hoặc username sinh viên" required>
        <button type="submit">Tìm kiếm</button>
    </form>

    <?php if (!empty($searchResults)): ?>
        <h2>Kết quả tìm kiếm</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Tên sinh viên</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['id']) ?></td>
                        <td><?= htmlspecialchars($student['username']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
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
    <?php elseif (isset($_GET['search'])): ?>
        <p>Không tìm thấy sinh viên với tên hoặc username "<?= htmlspecialchars($searchQuery) ?>"</p>
    <?php endif; ?>

</body>
</html>
