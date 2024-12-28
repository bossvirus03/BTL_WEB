<?php
// Kết nối DB
require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách các ngành học
$query = "SELECT * FROM majors";
$stmt = $conn->prepare($query);
$stmt->execute();
$majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Ngành Học</title>
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
        <h1>Danh Sách Ngành Học</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Ngành Học</th>
                    <th>Mô Tả</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($majors as $major): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($major['id']); ?></td>
                        <td><?php echo htmlspecialchars($major['name']); ?></td>
                        <td><?php echo htmlspecialchars($major['description']); ?></td>
                        <td><?php echo htmlspecialchars($major['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($major['updated_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
