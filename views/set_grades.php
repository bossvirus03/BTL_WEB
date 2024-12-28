<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

include_once '../configs/db.php';

// Create an instance of the Database class
$database = new Database();
$db = $database->getConnection();

// Fetch students from the user table
$stmt = $db->prepare("SELECT id, username FROM users WHERE role = 'student'");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subjects from the subjects table
$stmt = $db->prepare("SELECT id, name FROM subjects");
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id']; // Updated to use subject_id
    $grade_a = $_POST['grade_a']; // Grade A
    $grade_b = $_POST['grade_b']; // Grade B
    $grade_c = $_POST['grade_c']; // Grade C
    $semester = $_POST['semester'];

    // Kiểm tra dữ liệu đầu vào
    if ($student_id && $subject_id && $grade_a && $grade_b && $grade_c && $semester) {
        try {
            // Thêm hoặc cập nhật điểm vào bảng 'grades'
            $sql = "INSERT INTO grades (student_id, subject_id, grade_a, grade_b, grade_c, semester) 
                    VALUES (:student_id, :subject_id, :grade_a, :grade_b, :grade_c, :semester)
                    ON DUPLICATE KEY UPDATE grade_a = :grade_a, grade_b = :grade_b, grade_c = :grade_c";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':subject_id', $subject_id); // Use subject_id
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
        $message = "Vui lòng điền đầy đủ thông tin.";
    }
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
    background: linear-gradient(to bottom, #4e54c8, #8f94fb);
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
    max-width: 600px;
    box-sizing: border-box;
}

h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
    text-align: center;
    font-weight: bold;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #555;
}

input, select {
    width: 100%;
    padding: 12px 15px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    box-sizing: border-box;
}

input[type="number"] {
    -webkit-appearance: none;
    -moz-appearance: textfield;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    margin-top: 20px;
    background: #6a11cb;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: #4e54c8;
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
    color: #ffd700;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* Responsive design for smaller screens */
@media (max-width: 600px) {
    .container {
        padding: 15px;
        max-width: 100%;
    }

    h1 {
        font-size: 20px;
    }

    input, select, button {
        font-size: 14px;
        padding: 10px;
    }
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

            <label for="subject_id">Môn học:</label>
            <select name="subject_id" required>
                <option value="">Chọn môn học</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="grade_a">Điểm A:</label>
            <input type="number" step="0.1" name="grade_a" required>

            <label for="grade_b">Điểm B:</label>
            <input type="number" step="0.1" name="grade_b" required>

            <label for="grade_c">Điểm C:</label>
            <input type="number" step="0.1" name="grade_c" required>

            <label for="semester">Kỳ học:</label>
            <input type="text" name="semester" required>

            <button type="submit">Cập nhật điểm</button>
        </form>

        <a href="education_office_dashboard.php">Quay lại</a>
    </div>
</body>
</html>
