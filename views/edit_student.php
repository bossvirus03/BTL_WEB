<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Fetch student information
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT username, email, name FROM users WHERE id = :id AND role = 'student'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "Sinh viên không tồn tại!";
        exit();
    }

    // Fetch grades and subjects
    $gradesQuery = "SELECT g.id AS grade_id, s.name AS subject_name, g.grade_a, g.grade_b, g.grade_c, g.semester 
                    FROM grades g
                    JOIN subjects s ON g.subject_id = s.id
                    WHERE g.student_id = :id";
    $gradesStmt = $conn->prepare($gradesQuery);
    $gradesStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $gradesStmt->execute();
    $grades = $gradesStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle student information and grades update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update student information
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $updateQuery = "UPDATE users SET username = :username, email = :email, name = :name WHERE id = :id AND role = 'student'";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Update grades and subjects
        foreach ($_POST['grades'] as $gradeId => $gradeData) {
            $subjectName = $gradeData['subject_name'];
            $gradeA = $gradeData['grade_a'];
            $gradeB = $gradeData['grade_b'];
            $gradeC = $gradeData['grade_c'];

            if (
                $gradeA >= 0 && $gradeA <= 10 &&
                $gradeB >= 0 && $gradeB <= 10 &&
                $gradeC >= 0 && $gradeC <= 10
            ) {
                // Update the subject name
                $updateSubjectQuery = "UPDATE subjects s 
                                       JOIN grades g ON s.id = g.subject_id 
                                       SET s.name = :subject_name 
                                       WHERE g.id = :grade_id";
                $subjectStmt = $conn->prepare($updateSubjectQuery);
                $subjectStmt->bindParam(':subject_name', $subjectName);
                $subjectStmt->bindParam(':grade_id', $gradeId, PDO::PARAM_INT);
                $subjectStmt->execute();

                // Update the grades
                $updateGradeQuery = "UPDATE grades 
                                     SET grade_a = :grade_a, grade_b = :grade_b, grade_c = :grade_c 
                                     WHERE id = :grade_id";
                $gradeStmt = $conn->prepare($updateGradeQuery);
                $gradeStmt->bindParam(':grade_a', $gradeA);
                $gradeStmt->bindParam(':grade_b', $gradeB);
                $gradeStmt->bindParam(':grade_c', $gradeC);
                $gradeStmt->bindParam(':grade_id', $gradeId, PDO::PARAM_INT);
                $gradeStmt->execute();
            }
        }
        header("Location: student_list.php");
        exit();
    } else {
        echo "Cập nhật thông tin thất bại!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin sinh viên</title>
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <form method="POST">
        <h2>Sửa thông tin sinh viên</h2>
        <label for="username">Tên đăng nhập</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($student['username']) ?>" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($student['name']) ?>" required>

        <h3>Sửa điểm</h3>
        <?php if (!empty($grades)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Môn học</th>
                        <th>Điểm A</th>
                        <th>Điểm B</th>
                        <th>Điểm C</th>
                        <th>Học kỳ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade) : ?>
                        <tr>
                            <td>
                                <input type="text" name="grades[<?= $grade['grade_id'] ?>][subject_name]" value="<?= htmlspecialchars($grade['subject_name']) ?>" required>
                            </td>
                            <td>
                                <input 
                                    type="number" 
                                    name="grades[<?= $grade['grade_id'] ?>][grade_a]" 
                                    value="<?= htmlspecialchars($grade['grade_a']) ?>" 
                                    min="0" 
                                    max="10" 
                                    step="0.1" 
                                    required>
                            </td>
                            <td>
                                <input 
                                    type="number" 
                                    name="grades[<?= $grade['grade_id'] ?>][grade_b]" 
                                    value="<?= htmlspecialchars($grade['grade_b']) ?>" 
                                    min="0" 
                                    max="10" 
                                    step="0.1" 
                                    required>
                            </td>
                            <td>
                                <input 
                                    type="number" 
                                    name="grades[<?= $grade['grade_id'] ?>][grade_c]" 
                                    value="<?= htmlspecialchars($grade['grade_c']) ?>" 
                                    min="0" 
                                    max="10" 
                                    step="0.1" 
                                    required>
                            </td>
                            <td><?= htmlspecialchars($grade['semester']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Không có điểm để hiển thị.</p>
        <?php endif; ?>

        <button type="submit">Cập nhật</button>
        <div class="back-link">
            <a href="student_list.php">Quay lại danh sách</a>
        </div>
    </form>
</body>
</html>
