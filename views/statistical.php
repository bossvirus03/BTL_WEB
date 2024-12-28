<?php
// Kết nối DB
require_once '../configs/db.php';
$db = new Database();
$conn = $db->getConnection();

// Thống kê xếp loại với điểm mới (a * 0.6 + b * 0.3 + c * 0.1)
$query = "
    SELECT
        SUM(CASE WHEN (grade_a * 0.6 + grade_b * 0.3 + grade_c * 0.1) >= 8 THEN 1 ELSE 0 END) AS gioi,
        SUM(CASE WHEN (grade_a * 0.6 + grade_b * 0.3 + grade_c * 0.1) >= 6.5 AND (grade_a * 0.6 + grade_b * 0.3 + grade_c * 0.1) < 8 THEN 1 ELSE 0 END) AS kha,
        SUM(CASE WHEN (grade_a * 0.6 + grade_b * 0.3 + grade_c * 0.1) >= 5 AND (grade_a * 0.6 + grade_b * 0.3 + grade_c * 0.1) < 6.5 THEN 1 ELSE 0 END) AS trung_binh,
        SUM(CASE WHEN (grade_a * 0.6 + grade_b * 0.3 + grade_c * 0.1) < 5 THEN 1 ELSE 0 END) AS yeu
    FROM grades
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Tính tổng số sinh viên
$total_students = array_sum($result);

// Tính phần trăm
$percentages = array_map(function ($count) use ($total_students) {
    return $total_students > 0 ? round(($count / $total_students) * 100, 2) : 0;
}, $result);

// Xác định nhãn cho biểu đồ
$labels = ["Giỏi", "Khá", "Trung bình", "Yếu"];
$values = [
    $percentages['gioi'],
    $percentages['kha'],
    $percentages['trung_binh'],
    $percentages['yeu']
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biểu đồ xếp loại sinh viên</title>
    <style>
        .container{
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: center; flex-direction: column; align-items: center;">
        <h1>Biểu đồ xếp loại sinh viên</h1>
    
        <div class="container"><canvas id="pieChart" width="400" height="400"></canvas></div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($labels); ?>, // Nhãn của các phần trong biểu đồ
                datasets: [{
                    data: <?php echo json_encode($values); ?>, // Dữ liệu phần trăm
                    backgroundColor: ['#28a745', '#007bff', '#ff7f0e', '#dc3545'], // Màu sắc các phần
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
