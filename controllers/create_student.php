<?php
session_start();
if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $db = new Database();
    $conn = $db->getConnection();

    $conn->beginTransaction();

    try {
        $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, 'student')";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
        $user_id = $conn->lastInsertId();
        $query = "INSERT INTO students (user_id, name) VALUES (:user_id, :name)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $conn->commit();
        echo "Tài khoản sinh viên đã được tạo thành công!";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Lỗi: " . $e->getMessage();
    }
}
?>
