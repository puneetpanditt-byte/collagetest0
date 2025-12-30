<?php
require_once 'config.php';

// Get results from database
$query = "SELECT r.*, c.course_name, s.name as student_name, s.roll_number 
          FROM results r 
          JOIN courses c ON r.course_id = c.id 
          JOIN students s ON r.student_id = s.id 
          ORDER BY r.exam_date DESC, r.created_at DESC";
$result = mysqli_query($conn, $query);
$results = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get unique courses for filter
$courses = [];
$course_result = mysqli_query($conn, "SELECT DISTINCT c.course_name FROM courses c JOIN results r ON c.id = r.course_id ORDER BY c.course_name");
while ($row = mysqli_fetch_assoc($course_result)) {
    $courses[] = $row['course_name'];
}

// Get unique semesters for filter
$semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results - SDGD College</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="top-bar">
            <div class="container">
                <div class="top-links">
                    <a href="admin/login.php" class="admin-link"><i class="fas fa-user-shield"></i> ADMIN</a>
                    <a href="teacher/login.php" class="teacher-link"><i class="fas fa-chalkboard-teacher"></i> TEACHER LOGIN</a>
                    <a href="student-portal.php" class="student-link"><i class="fas fa-user-graduate"></i> STUDENT PORTAL</a>
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <div class="logo-section">
                    <img src="assets/logo.png" alt="College Logo" class="logo">
                    <div class="college-info">
                        <h1>SUB DIVISIONAL GOVERNMENT DEGREE COLLEGE</h1>
                        <h2>NAUHATTA, ROHTAS</h2>
                        <p>Affiliated to Veer Kunwar Singh University, Ara</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php"><i class="fas fa-home"></i> HOME</a></li>
                <li><a href="examination.php"><i class="fas fa-clipboard-check"></i> EXAMINATION</a></li>
                <li><a href="results.php" class="active"><i class="fas fa-chart-line"></i> RESULTS</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-chart-line"></i> Examination Results</h1>
            <p>View your academic performance and examination results</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Student Login Section -->
            <div class="result-login">
                <h2><i class="fas fa-user-graduate"></i> Student Login</h2>
                <form method="POST" action="student-portal.php" class="login-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="roll_number">Roll Number</label>
                            <input type="text" id="roll_number" name="roll_number" required 
                                   placeholder="Enter your roll number">
                        </div>
                        <div class="form-group">
                            <label for="registration_number">Registration Number</label>
                            <input type="text" id="registration_number" name="registration_number" required 
                                   placeholder="Enter your registration number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i> View Results
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search by roll number or name..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        
                        <select name="course">
                            <option value="">All Courses</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course; ?>" <?php echo ($_GET['course'] ?? '') === $course ? 'selected' : ''; ?>>
                                    <?php echo $course; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select name="semester">
                            <option value="">All Semesters</option>
                            <?php foreach ($semesters as $semester): ?>
                                <option value="<?php echo $semester; ?>" <?php echo ($_GET['semester'] ?? '') === $semester ? 'selected' : ''; ?>>
                                    <?php echo $semester; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="results.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Display -->
            <div class="results-display">
                <h2><i class="fas fa-graduation-cap"></i> Your Results</h2>
                <div class="results-grid">
                    <?php if (count($results) > 0): ?>
                        <?php foreach ($results as $result): ?>
                            <div class="result-card">
                                <div class="result-header">
                                    <h4><?php echo $result['course_name']; ?></h4>
                                    <span class="result-grade <?php echo $result['grade']; ?>">
                                        Grade: <?php echo $result['grade']; ?>
                                    </span>
                                    <span class="result-marks">
                                        Marks: <?php echo $result['marks']; ?>/100
                                    </span>
                                </div>
                                <div class="result-details">
                                    <p><strong>Student:</strong> <?php echo $result['student_name']; ?></p>
                                    <p><strong>Roll Number:</strong> <?php echo $result['roll_number']; ?></p>
                                    <p><strong>Exam Date:</strong> <?php echo format_date($result['exam_date']); ?></p>
                                    <p><strong>Subject:</strong> <?php echo $result['subject']; ?></p>
                                    <p><strong>Semester:</strong> <?php echo $result['semester']; ?></p>
                                </div>
                                <div class="result-actions">
                                    <a href="results.php?action=download&id=<?php echo $result['id']; ?>" class="download-btn">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <a href="results.php?action=print&id=<?php echo $result['id']; ?>" class="print-btn" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-results">
                            <i class="fas fa-chart-line"></i>
                            <h3>No Results Available</h3>
                            <p>No results are currently available.</p>
                            <p>Please check back later for updates.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Sub Divisional Government Degree College, Nauhatta, Rohtas, Bihar</p>
                    <p><i class="fas fa-phone"></i> +91-XXXX-XXXXXX</p>
                    <p><i class="fas fa-envelope"></i> principal@sdgdcnauhatta.ac.in</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="examination.php">Examination</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="admin/login.php">Admin Login</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> SDGD College, Nauhatta. All rights reserved. | Developed by <a href="#">Web Team</a></p>
            </div>
        </div>
    </footer>

    <style>
        .page-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 300;
        }
        
        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        .result-login {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .result-login h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            text-align: center;
        }
        
        .login-form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-actions {
            text-align: center;
        }
        
        .submit-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background: #2980b9;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .filter-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            flex: 1;
        }
        
        .filter-row input,
        .filter-row select {
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-btn,
        .clear-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .filter-btn:hover,
        .clear-btn:hover {
            background: #2980b9;
        }
        
        .results-display {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .results-display h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 10px;
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .result-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .result-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .result-header {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        
        .result-header h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .result-grade {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .result-grade.A { background: #27ae60; color: white; }
        .result-grade.B { background: #3498db; color: white; }
        .result-grade.C { background: #f39c12; color: white; }
        .result-grade.D { background: #e74c3c; color: white; }
        
        .result-marks {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .result-details {
            padding: 20px;
        }
        
        .result-details p {
            color: #5a6c7d;
            margin: 5px 0;
            line-height: 1.6;
        }
        
        .result-details strong {
            color: #2c3e50;
        }
        
        .result-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .download-btn,
        .print-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .download-btn:hover,
        .print-btn:hover {
            background: #2980b9;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-results h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .no-results p {
            color: #7f8c8d;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .results-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
