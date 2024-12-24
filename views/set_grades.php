<?php
// Include the Database class
include_once '../configs/db.php';

// Create an instance of the Database class
$database = new Database();
$db = $database->getConnection();

// Fetch students from the user table
$stmt = $db->prepare("SELECT id, username FROM users WHERE role = 'student'"); // Assuming students have the role 'student'
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission (if any)
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $grade = $_POST['grade'];
    $semester = $_POST['semester'];
    $message = "Điểm đã được cập nhật thành công!";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set điểm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #4facfe, #00f2fe);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            background-color: #4facfe;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #00f2fe;
        }
        p {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #4facfe;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Set điểm cho sinh viên</h1>

        <!-- Hiển thị thông báo -->
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="student_id">Sinh viên:</label>
            <select name="student_id" required>
                <option value="">Chọn sinh viên</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['username']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="subject">Môn học:</label>
            <input type="text" name="subject" required>

            <label for="grade">Điểm:</label>
            <input type="number" step="0.1" name="grade" required>

            <label for="semester">Kỳ học:</label>
            <input type="text" name="semester" required>

            <button type="submit">Cập nhật điểm</button>
        </form>

        <a href="education_office_dashboard.php">Quay lại</a>
    </div>
</body>
</html>
