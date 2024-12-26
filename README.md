## B1: Khởi tạo cơ sở dữ liệu

# khởi tạo bảng users

```sql
CREATE TABLE users (
id SERIAL PRIMARY KEY,
username VARCHAR(255) NOT NULL UNIQUE,
name VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
role ENUM('student', 'education_office') NOT NULL DEFAULT 'student', -- Thêm giá trị 'education_office'
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

# khởi tạo bảng grades

```sql
CREATE TABLE `grades` (
`id` int(11) NOT NULL,
`student_id` int(11) NOT NULL,
`subject` varchar(100) NOT NULL,
`grade` float NOT NULL,
`semester` varchar(50) NOT NULL
);
```

## Tạo người dùng đầu tiền với quyền là phòng đào tạo

```sql
INSERT INTO users (username, name, email, password, role) VALUES ('pdt', 'phòng đào tạo', 'daotao@humg.edu.vn', '123', 'education_office')
```
