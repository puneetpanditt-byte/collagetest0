<?php
// Database Setup Script
echo "<h1>Database Setup for SDGD College</h1>";

// Database connection without selecting database
$conn = mysqli_connect('localhost', 'root', '');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$db_name = 'sdgd_college';
$create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
if (mysqli_query($conn, $create_db)) {
    echo "<p style='color: green;'>✅ Database '$db_name' created or already exists</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating database: " . mysqli_error($conn) . "</p>";
}

// Select the database
mysqli_select_db($conn, $db_name);

// Create essential tables
$tables = [
    "admin_users" => "
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            role ENUM('admin', 'super_admin') DEFAULT 'admin',
            status ENUM('active', 'inactive') DEFAULT 'active',
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
    
    "students" => "
        CREATE TABLE IF NOT EXISTS students (
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
    
    "teachers" => "
        CREATE TABLE IF NOT EXISTS teachers (
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
    
    "courses" => "
        CREATE TABLE IF NOT EXISTS courses (
            id INT PRIMARY KEY AUTO_INCREMENT,
            course_name VARCHAR(255) NOT NULL,
            course_code VARCHAR(50) UNIQUE NOT NULL,
            course_type ENUM('undergraduate', 'postgraduate', 'diploma', 'certificate') DEFAULT 'undergraduate',
            duration VARCHAR(50),
            eligibility TEXT,
            fees DECIMAL(10,2),
            description TEXT,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
        )",
    
    "notices" => "
        CREATE TABLE IF NOT EXISTS notices (
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
        )",
    
    "college_magazine" => "
        CREATE TABLE IF NOT EXISTS college_magazine (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            volume VARCHAR(50),
            issue_number VARCHAR(50),
            published_date DATE NOT NULL,
            pages INT DEFAULT 0,
            cover_image VARCHAR(255),
            pdf_path VARCHAR(255),
            status ENUM('active', 'inactive') DEFAULT 'active',
            views INT DEFAULT 0,
            downloads INT DEFAULT 0,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
        )"
];

foreach ($tables as $table_name => $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✅ Table '$table_name' created or already exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating table '$table_name': " . mysqli_error($conn) . "</p>";
    }
}

// Insert sample data
$sample_data = [
    "admin_users" => "INSERT IGNORE INTO admin_users (username, password, email, full_name, role) VALUES 
        ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@sdgdcnauhatta.ac.in', 'System Administrator', 'super_admin')",
    
    "college_magazine" => "INSERT IGNORE INTO college_magazine (title, description, volume, issue_number, published_date, pages, cover_image, pdf_path, status, created_by) VALUES 
        ('Annual Magazine 2024', 'Comprehensive annual magazine featuring student achievements, events, and college highlights from the academic year 2024.', 'Volume 1', 'Issue 1', '2024-12-15', 48, 'assets/magazine/annual-2024-cover.jpg', 'assets/magazine/annual-2024.pdf', 'active', 1),
        ('College Newsletter', 'Monthly newsletter with updates on college activities and announcements.', 'Volume 1', 'Issue 2', '2024-11-15', 24, 'assets/magazine/newsletter-2024-11.jpg', 'assets/magazine/newsletter-2024-11.pdf', 'active', 1)"
];

foreach ($sample_data as $table_name => $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✅ Sample data inserted into '$table_name'</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Sample data for '$table_name' may already exist</p>";
    }
}

echo "<h2>Setup Complete!</h2>";
echo "<p style='color: green;'>✅ Database setup completed successfully</p>";
echo "<p>You can now access your website at: <a href='index.php'>http://localhost/web%20collage/</a></p>";
echo "<p>Admin login: Username: admin, Password: password</p>";
echo "<p><a href='test.php'>Run Test Check</a> to verify everything is working</p>";

mysqli_close($conn);
?>
