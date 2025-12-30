<?php
require_once 'config.php';

echo "<h2>Database Schema Update Script</h2>";
echo "<p>This script will add all missing columns to make the website work properly.</p>";

// Array of columns to add
$columns_to_add = [
    'courses' => [
        'course_type' => "ENUM('undergraduate', 'postgraduate', 'diploma', 'certificate') DEFAULT 'undergraduate'",
        'department' => "VARCHAR(100)",
        'seats_available' => "INT DEFAULT 0",
        'course_image' => "VARCHAR(255)",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'students' => [
        'roll_number' => "VARCHAR(50) UNIQUE NOT NULL",
        'registration_number' => "VARCHAR(50) UNIQUE NOT NULL",
        'phone' => "VARCHAR(20)",
        'course' => "VARCHAR(100)",
        'semester' => "VARCHAR(20)",
        'session' => "VARCHAR(50)",
        'date_of_birth' => "DATE",
        'gender' => "ENUM('male', 'female', 'other')",
        'category' => "VARCHAR(50)",
        'address' => "TEXT",
        'admission_date' => "DATE",
        'status' => "ENUM('active', 'inactive', 'graduated') DEFAULT 'active'",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'teachers' => [
        'email' => "VARCHAR(100) UNIQUE NOT NULL",
        'phone' => "VARCHAR(20)",
        'designation' => "VARCHAR(100)",
        'department' => "VARCHAR(100)",
        'qualification' => "TEXT",
        'specialization' => "TEXT",
        'experience' => "VARCHAR(100)",
        'status' => "ENUM('active', 'inactive') DEFAULT 'active'",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'notices' => [
        'title' => "VARCHAR(255) NOT NULL",
        'description' => "TEXT",
        'notice_type' => "ENUM('general', 'academic', 'examination', 'holiday', 'event') DEFAULT 'general'",
        'target_audience' => "ENUM('all', 'students', 'staff', 'parents') DEFAULT 'all'",
        'priority' => "ENUM('low', 'medium', 'high') DEFAULT 'medium'",
        'publish_date' => "DATE NOT NULL",
        'expiry_date' => "DATE",
        'attachment_path' => "VARCHAR(255)",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'college_magazine' => [
        'title' => "VARCHAR(255) NOT NULL",
        'description' => "TEXT",
        'volume' => "VARCHAR(50)",
        'issue_number' => "VARCHAR(50)",
        'published_date' => "DATE NOT NULL",
        'pages' => "INT DEFAULT 0",
        'cover_image' => "VARCHAR(255)",
        'pdf_path' => "VARCHAR(255)",
        'views' => "INT DEFAULT 0",
        'downloads' => "INT DEFAULT 0",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'news_articles' => [
        'title' => "VARCHAR(255) NOT NULL",
        'content' => "TEXT",
        'category' => "VARCHAR(100)",
        'author' => "VARCHAR(100)",
        'published_date' => "DATE NOT NULL",
        'image_path' => "VARCHAR(255)",
        'views' => "INT DEFAULT 0",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'facilities' => [
        'facility_name' => "VARCHAR(255) NOT NULL",
        'description' => "TEXT",
        'category' => "VARCHAR(100)",
        'capacity' => "VARCHAR(100)",
        'equipment' => "TEXT",
        'availability' => "VARCHAR(255)",
        'image_path' => "VARCHAR(255)",
        'status' => "ENUM('active', 'inactive') DEFAULT 'active'",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'examinations' => [
        'title' => "VARCHAR(255) NOT NULL",
        'description' => "TEXT",
        'exam_type' => "VARCHAR(100)",
        'duration' => "VARCHAR(100)",
        'start_time' => "TIME",
        'end_time' => "TIME",
        'venue' => "VARCHAR(255)",
        'instructions' => "TEXT",
        'exam_date' => "DATE NOT NULL",
        'admit_card' => "VARCHAR(255)",
        'status' => "ENUM('active', 'inactive') DEFAULT 'active'",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'results' => [
        'student_id' => "INT NOT NULL",
        'course_id' => "INT NOT NULL",
        'subject' => "VARCHAR(255)",
        'marks' => "DECIMAL(5,2)",
        'grade' => "VARCHAR(10)",
        'exam_date' => "DATE",
        'semester' => "VARCHAR(20)",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ],
    'master_routine' => [
        'title' => "VARCHAR(255) NOT NULL",
        'description' => "TEXT",
        'category' => "VARCHAR(100)",
        'day' => "VARCHAR(20)",
        'start_time' => "TIME",
        'end_time' => "TIME",
        'venue' => "VARCHAR(255)",
        'status' => "ENUM('active', 'inactive') DEFAULT 'active'",
        'created_by' => "INT",
        'updated_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ]
];

echo "<h3>Adding Missing Columns...</h3>";

foreach ($columns_to_add as $table => $columns) {
    echo "<h4>Table: $table</h4>";
    
    // First check if table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    
    if (mysqli_num_rows($table_check) == 0) {
        echo "<p style='color: orange;'>⚠️ Table '$table' does not exist. Creating table...</p>";
        
        // Create the table first
        $create_table_queries = [
            'courses' => "CREATE TABLE IF NOT EXISTS courses (
                id INT PRIMARY KEY AUTO_INCREMENT,
                course_name VARCHAR(255) NOT NULL,
                course_code VARCHAR(50) UNIQUE NOT NULL,
                course_type ENUM('undergraduate', 'postgraduate', 'diploma', 'certificate') DEFAULT 'undergraduate',
                duration VARCHAR(50),
                eligibility TEXT,
                fees DECIMAL(10,2),
                department VARCHAR(100),
                seats_available INT DEFAULT 0,
                course_image VARCHAR(255),
                description TEXT,
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'students' => "CREATE TABLE IF NOT EXISTS students (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                roll_number VARCHAR(50) UNIQUE NOT NULL,
                registration_number VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                phone VARCHAR(20),
                course VARCHAR(100),
                semester VARCHAR(20),
                session VARCHAR(50),
                date_of_birth DATE,
                gender ENUM('male', 'female', 'other'),
                category VARCHAR(50),
                address TEXT,
                admission_date DATE,
                status ENUM('active', 'inactive', 'graduated') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'teachers' => "CREATE TABLE IF NOT EXISTS teachers (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                phone VARCHAR(20),
                designation VARCHAR(100),
                department VARCHAR(100),
                qualification TEXT,
                specialization TEXT,
                experience VARCHAR(100),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'notices' => "CREATE TABLE IF NOT EXISTS notices (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                notice_type ENUM('general', 'academic', 'examination', 'holiday', 'event') DEFAULT 'general',
                target_audience ENUM('all', 'students', 'staff', 'parents') DEFAULT 'all',
                priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
                publish_date DATE NOT NULL,
                expiry_date DATE,
                attachment_path VARCHAR(255),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'college_magazine' => "CREATE TABLE IF NOT EXISTS college_magazine (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                volume VARCHAR(50),
                issue_number VARCHAR(50),
                published_date DATE NOT NULL,
                pages INT DEFAULT 0,
                cover_image VARCHAR(255),
                pdf_path VARCHAR(255),
                views INT DEFAULT 0,
                downloads INT DEFAULT 0,
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'news_articles' => "CREATE TABLE IF NOT EXISTS news_articles (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                content TEXT,
                category VARCHAR(100),
                author VARCHAR(100),
                published_date DATE NOT NULL,
                image_path VARCHAR(255),
                views INT DEFAULT 0,
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'facilities' => "CREATE TABLE IF NOT EXISTS facilities (
                id INT PRIMARY KEY AUTO_INCREMENT,
                facility_name VARCHAR(255) NOT NULL,
                description TEXT,
                category VARCHAR(100),
                capacity VARCHAR(100),
                equipment TEXT,
                availability VARCHAR(255),
                image_path VARCHAR(255),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'examinations' => "CREATE TABLE IF NOT EXISTS examinations (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                exam_type VARCHAR(100),
                duration VARCHAR(100),
                start_time TIME,
                end_time TIME,
                venue VARCHAR(255),
                instructions TEXT,
                exam_date DATE NOT NULL,
                admit_card VARCHAR(255),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'results' => "CREATE TABLE IF NOT EXISTS results (
                id INT PRIMARY KEY AUTO_INCREMENT,
                student_id INT NOT NULL,
                course_id INT NOT NULL,
                subject VARCHAR(255),
                marks DECIMAL(5,2),
                grade VARCHAR(10),
                exam_date DATE,
                semester VARCHAR(20),
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'master_routine' => "CREATE TABLE IF NOT EXISTS master_routine (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                category VARCHAR(100),
                day VARCHAR(20),
                start_time TIME,
                end_time TIME,
                venue VARCHAR(255),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )"
        ];
        
        if (isset($create_table_queries[$table])) {
            if (mysqli_query($conn, $create_table_queries[$table])) {
                echo "<p style='color: green;'>✅ Table '$table' created successfully</p>";
            } else {
                echo "<p style='color: red;'>❌ Error creating table '$table': " . mysqli_error($conn) . "</p>";
            }
        }
    }
    
    echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<tr><th>Column</th><th>Type</th><th>Status</th></tr>";
    
    foreach ($columns as $column_name => $column_definition) {
        // Check if column already exists (only if table exists)
        if (mysqli_num_rows($table_check) > 0) {
            $check_query = "SHOW COLUMNS FROM `$table` LIKE '$column_name'";
            $result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<tr><td>$column_name</td><td>$column_definition</td><td><span style='color: green;'>✅ Already exists</span></td></tr>";
            } else {
                // Add the column
                $alter_query = "ALTER TABLE `$table` ADD COLUMN `$column_name` $column_definition";
                
                if (mysqli_query($conn, $alter_query)) {
                    echo "<tr><td>$column_name</td><td>$column_definition</td><td><span style='color: green;'>✅ Added successfully</span></td></tr>";
                } else {
                    echo "<tr><td>$column_name</td><td>$column_definition</td><td><span style='color: red;'>❌ Error: " . mysqli_error($conn) . "</span></td></tr>";
                }
            }
        } else {
            echo "<tr><td>$column_name</td><td>$column_definition</td><td><span style='color: orange;'>⏳️ Will be added when table is created</span></td></tr>";
        }
    }
    
    echo "</table>";
}

// Add foreign key constraints
echo "<h3>Adding Foreign Key Constraints...</h3>";

$constraints = [
    'courses' => [
        'fk_courses_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'students' => [
        'fk_students_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'teachers' => [
        'fk_teachers_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'notices' => [
        'fk_notices_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'college_magazine' => [
        'fk_magazine_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'news_articles' => [
        'fk_articles_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'facilities' => [
        'fk_facilities_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'examinations' => [
        'fk_examinations_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'results' => [
        'fk_results_student_id' => "FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE",
        'fk_results_course_id' => "FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE",
        'fk_results_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ],
    'master_routine' => [
        'fk_routine_created_by' => "FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL"
    ]
];

foreach ($constraints as $table => $table_constraints) {
    echo "<h4>Table: $table</h4>";
    echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<tr><th>Constraint</th><th>Status</th></tr>";
    
    foreach ($table_constraints as $constraint_name => $constraint_definition) {
        // Check if constraint already exists
        $check_query = "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table' AND CONSTRAINT_NAME = '$constraint_name'";
        $result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<tr><td>$constraint_name</td><td>$constraint_definition</td><td><span style='color: green;'>✅ Already exists</span></td></tr>";
        } else {
            // Add the constraint
            $alter_query = "ALTER TABLE `$table` ADD CONSTRAINT $constraint_name $constraint_definition";
            
            if (mysqli_query($conn, $alter_query)) {
                echo "<tr><td>$constraint_name</td><td>$constraint_definition</td><td><span style='color: green;'>✅ Added successfully</span></td></tr>";
            } else {
                echo "<tr><td>$constraint_name</td><td>$constraint_definition</td><td><span style='color: red;'>❌ Error: " . mysqli_error($conn) . "</span></td></tr>";
            }
        }
    }
    
    echo "</table>";
}

// Insert sample data if tables are empty
echo "<h3>Inserting Sample Data...</h3>";

// Sample data for courses
$courses_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
$courses_count = mysqli_fetch_assoc($courses_check)['count'];

if ($courses_count == 0) {
    $sample_courses = [
        ['Bachelor of Arts', 'BA001', 'undergraduate', '3 Years', '10+2 with minimum 45%', 15000.00, 'Arts', 60, 'Bachelor of Arts program with various specializations in humanities and social sciences.'],
        ['Bachelor of Science', 'BSC001', 'undergraduate', '3 Years', '10+2 with Science stream', 18000.00, 'Science', 80, 'Bachelor of Science program with Physics, Chemistry, Mathematics, and Biology options.'],
        ['Bachelor of Commerce', 'BCOM001', 'undergraduate', '3 Years', '10+2 with Commerce', 12000.00, 'Commerce', 100, 'Bachelor of Commerce program focusing on accounting, finance, and business studies.'],
        ['Master of Arts', 'MA001', 'postgraduate', '2 Years', 'Graduation in relevant subject', 25000.00, 'Arts', 30, 'Master of Arts program for advanced studies in humanities and social sciences.'],
        ['Master of Science', 'MSC001', 'postgraduate', '2 Years', 'B.Sc. in relevant subject', 30000.00, 'Science', 25, 'Master of Science program for advanced studies in physical and biological sciences.']
    ];
    
    foreach ($sample_courses as $course) {
        $query = "INSERT INTO courses (course_name, course_code, course_type, duration, eligibility, fees, department, seats_available, description, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', 1)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssdsssss', $course[0], $course[1], $course[2], $course[3], $course[4], $course[5], $course[6], $course[7], $course[8], 'active', 1);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    echo "<p style='color: green;'>✅ Sample courses inserted successfully</p>";
} else {
    echo "<p style='color: blue;'>ℹ️ Courses already exist</p>";
}

// Check admin users
$admin_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM admin_users");
$admin_count = mysqli_fetch_assoc($admin_check)['count'];

if ($admin_count == 0) {
    $query = "INSERT INTO admin_users (username, password, email, full_name, role, status) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@sdgdcnauhatta.ac.in', 'System Administrator', 'super_admin', 'active')";
    mysqli_query($conn, $query);
    echo "<p style='color: green;'>✅ Admin user created (username: admin, password: password)</p>";
} else {
    echo "<p style='color: blue;'>ℹ️ Admin user already exists</p>";
}

echo "<h3>Database Update Complete!</h3>";
echo "<p>Your database now has all the required columns and sample data to make the website work properly.</p>";
echo "<p>You can now:</p>";
echo "<ul>";
echo "<li>Add courses with images</li>";
echo "<li>View course details with proper data</li>";
echo "<li>Use all the new features of the website</li>";
echo "</ul>";

echo "<p><a href='index.php' class='btn btn-primary'>Go to Homepage</a></p>";

mysqli_close($conn);
?>
