<?php
require_once 'config.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to main page if not logged in (student-login.php doesn't exist)
    header('Location: index.php');
    exit();
}

// Get student information
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM students WHERE id = $student_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Get student's courses and results
$courses_query = "SELECT c.*, r.marks, r.grade FROM courses c 
                LEFT JOIN results r ON c.id = r.course_id AND r.student_id = $student_id
                WHERE c.status = 'active'
                ORDER BY c.course_name";
$courses_result = mysqli_query($conn, $courses_query);
$courses = mysqli_fetch_all($courses_result, MYSQLI_ASSOC);

// Get notices for students
$notices_query = "SELECT * FROM notices WHERE target_audience IN ('all', 'students') AND status = 'active' ORDER BY created_at DESC LIMIT 5";
$notices_result = mysqli_query($conn, $notices_query);
$notices = mysqli_fetch_all($notices_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - SDGD College</title>
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
                    <a href="magazine.php" class="magazine-link"><i class="fas fa-book"></i> College Magazine</a>
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
                <li><a href="student-portal.php" class="active"><i class="fas fa-user-graduate"></i> STUDENT PORTAL</a></li>
                <li><a href="student-portal.php?page=examination"><i class="fas fa-clipboard-check"></i> EXAMINATION</a></li>
                <li><a href="student-portal.php?page=results"><i class="fas fa-chart-line"></i> RESULTS</a></li>
                <li><a href="student-portal.php?page=profile"><i class="fas fa-user"></i> PROFILE</a></li>
                <li><a href="student-portal.php?page=logout"><i class="fas fa-sign-out-alt"></i> LOGOUT</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-user-graduate"></i> Student Portal</h1>
            <p>Welcome to your personal academic dashboard</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?php
            $page = $_GET['page'] ?? 'dashboard';
            
            switch ($page) {
                case 'dashboard':
                    include 'student-portal/dashboard.php';
                    break;
                case 'examination':
                    include 'student-portal/examination.php';
                    break;
                case 'results':
                    include 'student-portal/results.php';
                    break;
                case 'profile':
                    include 'student-portal/profile.php';
                    break;
                case 'logout':
                    session_destroy();
                    header('Location: index.php');
                    exit();
                    break;
                default:
                    include 'student-portal/dashboard.php';
                    break;
            }
            ?>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="notices.php">Notices</a></li>
                        <li><a href="tender.php">Tenders</a></li>
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
        
        .student-welcome {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .student-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .info-card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-card h3 i {
            font-size: 20px;
            color: #3498db;
        }
        
        .info-card p {
            color: #5a6c7d;
            margin: 5px 0;
            line-height: 1.6;
        }
        
        .info-card strong {
            color: #2c3e50;
        }
        
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .quick-link {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #2c3e50;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .quick-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .quick-link i {
            font-size: 24px;
            color: #3498db;
        }
        
        .quick-link h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .recent-notices {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .recent-notices h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .notice-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 3px solid #3498db;
        }
        
        .notice-item h4 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .notice-item p {
            color: #5a6c7d;
            margin: 0;
            line-height: 1.5;
        }
        
        .notice-item .date {
            color: #7f8c8d;
            font-size: 12px;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .student-info,
            .quick-links {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
