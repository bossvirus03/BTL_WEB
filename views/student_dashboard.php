<?php
session_start();

// Include the Database class
include_once '../configs/db.php';

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header("Location: ./login.php");
    exit;
}

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['id'];
$user_role = $_SESSION['role'];

// Tạo kết nối database
$database = new Database();
$db = $database->getConnection();

// Lấy danh sách điểm từ bảng 'grades' kết hợp với bảng 'subjects'
$results = [];
try {
    $stmt = $db->prepare("
        SELECT 
            grades.id AS grade_id, 
            subjects.name AS subject_name,
            grades.grade_a, 
            grades.grade_b, 
            grades.grade_c,
            grades.semester 
        FROM grades 
        JOIN subjects ON grades.subject_id = subjects.id 
        WHERE grades.student_id = :student_id
    ");
    $stmt->bindParam(':student_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi khi lấy dữ liệu: " . $e->getMessage();
    exit;
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $grade_a = $_POST['grade_a'];
    $grade_b = $_POST['grade_b'];
    $grade_c = $_POST['grade_c'];
    $semester = $_POST['semester'];

    // Kiểm tra dữ liệu đầu vào
    if ($student_id && $subject_id && $grade_a && $grade_b && $grade_c && $semester) {
        try {
            $sql = "INSERT INTO grades (student_id, subject_id, grade_a, grade_b, grade_c, semester) 
                    VALUES (:student_id, :subject_id, :grade_a, :grade_b, :grade_c, :semester)
                    ON DUPLICATE KEY UPDATE grade_a = :grade_a, grade_b = :grade_b, grade_c = :grade_c";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $stmt->bindParam(':grade_a', $grade_a);
            $stmt->bindParam(':grade_b', $grade_b);
            $stmt->bindParam(':grade_c', $grade_c);
            $stmt->bindParam(':semester', $semester);

            $stmt->execute();
            $message = "Điểm đã được cập nhật thành công!";
        } catch (PDOException $e) {
            $message = "Lỗi khi cập nhật điểm: " . $e->getMessage();
        }
    } else {
        $message = "Vui lòng nhập đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Sinh Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #4e54c8, #8f94fb);
            color: #333;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        header {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 20px;
        }
        header a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            background: #007bff;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        header a:hover {
            background: #0056b3;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tr:hover {
            background: #f1f1f1;
        }
        p {
            font-size: 16px;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid green;
            background: #d4edda;
            color: #155724;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bảng Điều Khiển Sinh Viên</h1>
        <a href="./logout.php">Đăng Xuất</a>
    </header>
    <div class="container">
        <h1>Chào mừng, sinh viên!</h1>
        <p>Thông tin người dùng:</p>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($user_id); ?></p>
        <p><strong>Vai trò:</strong> <?php echo htmlspecialchars($user_role); ?></p>

        <h2>Danh sách môn học và điểm</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <div>
            <?php if (!empty($results)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Môn học</th>
                            <th>Điểm A</th>
                            <th>Điểm B</th>
                            <th>Điểm C</th>
                            <th>Kỳ học</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['grade_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['grade_a']); ?></td>
                                <td><?php echo htmlspecialchars($row['grade_b']); ?></td>
                                <td><?php echo htmlspecialchars($row['grade_c']); ?></td>
                                <td><?php echo htmlspecialchars($row['semester']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có dữ liệu điểm cho sinh viên này.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
