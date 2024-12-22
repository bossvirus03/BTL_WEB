<?php
class StudentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllStudents() {
        $query = "SELECT * FROM students";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStudent($student_code, $name, $dob, $gender, $gpa) {
        $query = "INSERT INTO students (student_code, name, dob, gender, gpa) VALUES (:student_code, :name, :dob, :gender, :gpa)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_code', $student_code);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':gpa', $gpa);
        return $stmt->execute();
    }
}
?>
