## B1: Khởi tạo cơ sở dữ liệu

# khởi tạo bảng users

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- Đổi SERIAL thành INT UNSIGNED
    username VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'education_office') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

# khởi tạo bảng grades

```sql
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Grade ID
    student_id INT UNSIGNED NOT NULL, -- Reference to users table
    subject_id INT UNSIGNED NOT NULL, -- Reference to subjects table
    grade_a FLOAT NOT NULL CHECK (grade_a BETWEEN 0 AND 10), -- Grade A
    grade_b FLOAT NOT NULL CHECK (grade_b BETWEEN 0 AND 10), -- Grade B
    grade_c FLOAT NOT NULL CHECK (grade_c BETWEEN 0 AND 10), -- Grade C
    semester VARCHAR(50) NOT NULL, -- Semester
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Creation timestamp
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Update timestamp
    CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE, -- Link to users
    CONSTRAINT fk_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE -- Link to subjects
);
```

# khởi tạo bảng subjects

```sql
CREATE TABLE subjects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- Subject ID
    name VARCHAR(255) NOT NULL UNIQUE, -- Subject name
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Creation timestamp
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Update timestamp
);
```

## Tạo người dùng đầu tiền với quyền là phòng đào tạo

```sql
INSERT INTO users (username, name, email, password, role) VALUES ('pdt', 'phòng đào tạo', 'daotao@humg.edu.vn', '123', 'education_office')
```
