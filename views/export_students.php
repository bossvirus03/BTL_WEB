<?php
session_start();

if ($_SESSION['role'] !== 'education_office') {
    header("Location: ../views/login.php");
    exit();
}

require_once './../configs/db.php'; // Kết nối DB
require_once './../configs/fpdf186/fpdf.php'; // Nạp thư viện FPDF

// Kết nối cơ sở dữ liệu
$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách sinh viên
$query = "SELECT id, username, name, email, created_at FROM users WHERE role = 'student'";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Khởi tạo PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Tiêu đề
$pdf->Cell(0, 10, 'Danh sach sinh vien', 0, 1, 'C');
$pdf->Ln(10); // Dòng trống

// Tạo tiêu đề bảng
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(30, 10, 'Username', 1);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(80, 10, 'Email', 1);
$pdf->Cell(60, 10, 'Ngay tao', 1);
$pdf->Ln();

// Thêm dữ liệu sinh viên vào bảng
$pdf->SetFont('Arial', '', 12);

foreach ($students as $student) {
    $pdf->Cell(10, 10, $student['id'], 1);
    $pdf->Cell(30, 10, $student['username'], 1);
    $pdf->Cell(40, 10, $student['name'], 1);
    $pdf->Cell(80, 10, $student['email'], 1);
    $pdf->Cell(60, 10, $student['created_at'], 1);
    $pdf->Ln();
}

// Xuất file PDF
$pdf->Output('D', 'danh_sach_sinh_vien.pdf'); // Tải về trực tiếp
// $pdf->Output(); // Hiển thị trên trình duyệt
?>