<?php
require_once '../configs/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Mật khẩu không khớp!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $db = new Database();
    $conn = $db->getConnection();

    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        header("Location: ../views/login.php");
        exit();
    } else {
        echo "Lỗi khi đăng ký tài khoản!";
    }
}
?>
