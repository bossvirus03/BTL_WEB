<?php
// Kết nối DB
require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách các khoa
$query = "SELECT * FROM faculties";
$stmt = $conn->prepare($query);
$stmt->execute();
$faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Khoa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh Sách Khoa</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Khoa</th>
                    <th>Mô Tả</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faculties as $faculty): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($faculty['id']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['name']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['description']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['updated_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
