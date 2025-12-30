-- SDGD College Database Schema
-- Create Database
CREATE DATABASE IF NOT EXISTS sdgd_college;
USE sdgd_college;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Teachers Table
CREATE TABLE IF NOT EXISTS teachers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id VARCHAR(20) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    designation VARCHAR(100),
    qualification TEXT,
    experience VARCHAR(50),
    phone VARCHAR(15),
    address TEXT,
    profile_image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    roll_number VARCHAR(20) UNIQUE NOT NULL,
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    course VARCHAR(100),
    semester VARCHAR(20),
    session VARCHAR(20),
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    category VARCHAR(20),
    address TEXT,
    admission_date DATE,
    status ENUM('active', 'inactive', 'passed', 'left') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Notices Table
CREATE TABLE IF NOT EXISTS notices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255),
    file_name VARCHAR(255),
    notice_type ENUM('general', 'examination', 'admission', 'holiday', 'important') DEFAULT 'general',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    target_audience ENUM('all', 'students', 'teachers', 'staff') DEFAULT 'all',
    publish_date DATE NOT NULL,
    expiry_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Tenders Table
CREATE TABLE IF NOT EXISTS tenders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255),
    file_name VARCHAR(255),
    tender_type VARCHAR(100),
    last_date DATE,
    status ENUM('active', 'closed', 'cancelled') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Courses Table
CREATE TABLE IF NOT EXISTS courses (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Insert Sample Courses
INSERT INTO courses (course_name, course_code, course_type, duration, eligibility, fees, department, seats_available, description, status, created_by) VALUES 
('Bachelor of Arts', 'BA001', 'undergraduate', '3 Years', '10+2 with minimum 45%', 15000.00, 'Arts', 60, 'Bachelor of Arts program with various specializations in humanities and social sciences.', 'active', 1),
('Bachelor of Science', 'BSC001', 'undergraduate', '3 Years', '10+2 with Science stream', 18000.00, 'Science', 80, 'Bachelor of Science program with Physics, Chemistry, Mathematics, and Biology options.', 'active', 1),
('Bachelor of Commerce', 'BCOM001', 'undergraduate', '3 Years', '10+2 with Commerce', 12000.00, 'Commerce', 100, 'Bachelor of Commerce program focusing on accounting, finance, and business studies.', 'active', 1),
('Master of Arts', 'MA001', 'postgraduate', '2 Years', 'Graduation in relevant subject', 25000.00, 'Arts', 30, 'Master of Arts program for advanced studies in humanities and social sciences.', 'active', 1),
('Master of Science', 'MSC001', 'postgraduate', '2 Years', 'B.Sc. in relevant subject', 30000.00, 'Science', 25, 'Master of Science program for advanced studies in physical and biological sciences.', 'active', 1);

-- Syllabus Table
CREATE TABLE IF NOT EXISTS syllabus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    file_path VARCHAR(255),
    file_name VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Photo Gallery Table
CREATE TABLE IF NOT EXISTS photo_gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    album_name VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Video Gallery Table
CREATE TABLE IF NOT EXISTS video_gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_url VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255),
    category VARCHAR(100),
    duration VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Principal Messages Table
CREATE TABLE IF NOT EXISTS principal_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('general', 'students', 'staff', 'parents') DEFAULT 'general',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- College Magazine Table
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
);

-- Insert Sample Magazine Issues
INSERT INTO college_magazine (title, description, volume, issue_number, published_date, pages, cover_image, pdf_path, status, created_by) VALUES 
('Annual Magazine 2024', 'Comprehensive annual magazine featuring student achievements, events, and college highlights from the academic year 2024.', 'Volume 1', 'Issue 1', '2024-12-15', 48, 'assets/magazine/annual-2024-cover.jpg', 'assets/magazine/annual-2024.pdf', 'active', 1),
('College Newsletter', 'Monthly newsletter with updates on college activities and announcements.', 'Volume 1', 'Issue 2', '2024-11-15', 24, 'assets/magazine/newsletter-2024-11.jpg', 'assets/magazine/newsletter-2024-11.pdf', 'active', 1),
('Research Journal', 'Peer-reviewed research journal featuring articles from faculty and students.', 'Volume 1', 'Issue 3', '2024-10-15', 36, 'assets/magazine/research-2024.jpg', 'assets/magazine/research-2024.pdf', 'active', 1);

-- News Articles Table
CREATE TABLE IF NOT EXISTS news_articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    category VARCHAR(100),
    author VARCHAR(100),
    published_date DATE NOT NULL,
    image_path VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    views INT DEFAULT 0,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Facilities Table
CREATE TABLE IF NOT EXISTS facilities (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Examinations Table
CREATE TABLE IF NOT EXISTS examinations (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Results Table
CREATE TABLE IF NOT EXISTS results (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Sent Messages Table
CREATE TABLE IF NOT EXISTS sent_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipient_email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    sent_by INT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- College Costs Table
CREATE TABLE IF NOT EXISTS college_costs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cost_type ENUM('tuition_fee', 'exam_fee', 'library_fee', 'lab_fee', 'hostel_fee', 'other') NOT NULL,
    cost_name VARCHAR(200) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    academic_year VARCHAR(10) NOT NULL,
    semester VARCHAR(10),
    course VARCHAR(100),
    payment_method ENUM('cash', 'cheque', 'dd', 'online') DEFAULT 'cash',
    due_date DATE,
    status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Activity Logs Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    user_type ENUM('admin', 'teacher') NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'number', 'boolean', 'file') DEFAULT 'text',
    description TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Insert Default Admin User
INSERT INTO admin_users (username, email, password, full_name, role) VALUES 
('admin', 'admin@sdgdcnauhatta.ac.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin');

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES 
('site_name', 'SDGD College, Nauhatta', 'text', 'Website Name'),
('site_description', 'Sub Divisional Government Degree College, Nauhatta - Excellence in Education', 'textarea', 'Website Description'),
('contact_email', 'admin@sdgdcnauhatta.ac.in', 'text', 'Contact Email'),
('contact_phone', '+91-XXXX-XXXXXX', 'text', 'Contact Phone'),
('address', 'Sub Divisional Government Degree College, Nauhatta, Rohtas, Bihar', 'textarea', 'College Address'),
('facebook_url', '#', 'text', 'Facebook Page URL'),
('twitter_url', '#', 'text', 'Twitter Profile URL'),
('linkedin_url', '#', 'text', 'LinkedIn Profile URL'),
('youtube_url', '#', 'text', 'YouTube Channel URL');

-- Insert Sample Courses
INSERT INTO courses (course_name, course_code, duration, eligibility, fees, description) VALUES 
('Bachelor of Arts (BA)', 'BA001', '3 Years', '10+2 in any stream', 5000.00, 'Bachelor of Arts program with various specializations'),
('Bachelor of Science (BSc)', 'BSC001', '3 Years', '10+2 with Science', 6000.00, 'Bachelor of Science program with Physics, Chemistry, Mathematics'),
('Bachelor of Commerce (BCom)', 'BCOM001', '3 Years', '10+2 in any stream', 5500.00, 'Bachelor of Commerce program with accounting and business studies');

-- Insert Sample Notices
INSERT INTO notices (title, description, notice_type, priority, publish_date, created_by) VALUES 
('College Reopening Notice', 'College will reopen from 1st January 2025 after winter break.', 'general', 'high', '2024-12-15', 1),
('Examination Schedule', 'Annual examination will start from 15th February 2025.', 'examination', 'high', '2024-12-10', 1),
('Admission Open 2025', 'Admissions are now open for the academic session 2025-28.', 'admission', 'medium', '2024-12-01', 1);

-- Insert Sample Principal Message
INSERT INTO principal_messages (title, message, message_type, created_by) VALUES 
('Welcome Message', 'Dear Students, Staff, and Parents, I welcome you all to SDGD College, Nauhatta. Our institution is committed to providing quality education and holistic development of students. We strive to create an environment that fosters academic excellence, character building, and social responsibility. Together, we can build a bright future for our students and society.', 'general', 1);

-- Create Indexes for Better Performance
CREATE INDEX idx_notices_date ON notices(publish_date);
CREATE INDEX idx_notices_type ON notices(notice_type);
CREATE INDEX idx_tenders_date ON tenders(last_date);
CREATE INDEX idx_students_roll ON students(roll_number);
CREATE INDEX idx_teachers_employee ON teachers(employee_id);
CREATE INDEX idx_activity_logs_user ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_date ON activity_logs(created_at);
