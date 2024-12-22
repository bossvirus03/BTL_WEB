<?php
session_start();
require_once '../configs/db.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'student') {
            header("Location: ../views/student_dashboard.php");
        } elseif ($user['role'] === 'education_office') {
            header("Location: ../views/education_office_dashboard.php");
        } elseif ($user['role'] === 'admin') {
            header("Location: ../views/admin_dashboard.php");
        }
    } else {
        echo "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}

?>
