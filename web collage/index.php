<?php
require_once 'config.php';

// Dynamic greeting based on time of day
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good Morning! Start your journey today.";
    $greeting_icon = "sun";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon! Discover your potential.";
    $greeting_icon = "cloud-sun";
} else {
    $greeting = "Good Evening! Plan your future tonight.";
    $greeting_icon = "moon";
}

// Get featured courses
$courses_query = "SELECT * FROM courses WHERE status = 'active' ORDER BY created_at DESC LIMIT 4";
$courses_result = mysqli_query($conn, $courses_query);
$featured_courses = $courses_result ? mysqli_fetch_all($courses_result, MYSQLI_ASSOC) : [];

// Get latest notices
$notices_query = "SELECT * FROM notices WHERE status = 'active' AND target_audience IN ('all', 'students') ORDER BY created_at DESC LIMIT 3";
$notices_result = mysqli_query($conn, $notices_query);
$latest_notices = $notices_result ? mysqli_fetch_all($notices_result, MYSQLI_ASSOC) : [];

// Get statistics (sample data for now)
$stats = [
    'students' => 1500,
    'courses' => 25,
    'faculty' => 50,
    'placement' => 95
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDGD COLLEGE : OFFICIAL WEBSITE</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="top-bar">
            <div class="container">
                <div class="user-pathways">
                    <a href="#" class="pathway-link"><i class="fas fa-user-graduate"></i> Prospective Students</a>
                    <a href="student-portal.php" class="pathway-link"><i class="fas fa-user"></i> Current Students</a>
                    <a href="#" class="pathway-link"><i class="fas fa-users"></i> Alumni</a>
                </div>
                <div class="top-links">
                    <div class="search-bar">
                        <input type="text" placeholder="Search..." class="search-input">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <a href="admin/login.php" class="admin-link"><i class="fas fa-user-shield"></i> ADMIN</a>
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
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> HOME</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">ABOUT US <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="about.php">About College</a></li>
                        <li><a href="administration.php">Administration</a></li>
                        <li><a href="committee.php">Committee</a></li>
                        <li><a href="instructions.php">General Instruction</a></li>
                        <li><a href="rules.php">Rules and Discipline</a></li>
                        <li><a href="holidays.php">Holidays List</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">PRINCIPAL <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="principal-desk.php">Principal Desk</a></li>
                        <li><a href="principal-message.php">Message to Students/Staffs/Parent</a></li>
                        <li><a href="principal-profile.php">Principal's Profile</a></li>
                        <li><a href="photo-gallery.php">Photo Gallery</a></li>
                        <li><a href="video-gallery.php">Video Gallery</a></li>
                        <li><a href="media.php">In Media</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">OUR STAFFS <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="teaching-staff.php">Teaching Staffs</a></li>
                        <li><a href="non-teaching-staff.php">Non-Teaching Staffs</a></li>
                        <li><a href="contractual-staff.php">Contractual Staffs</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">ACADEMIC <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="courses.php">Course & Syllabus</a></li>
                        <li><a href="routine.php">Class Routine</a></li>
                        <li><a href="master-routine.php">Master Routine</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">STUDENT <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="student-portal.php">Student Portal</a></li>
                        <li><a href="examination.php">Examination</a></li>
                        <li><a href="results.php">Results</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">MEDIA <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="photo-gallery.php">Photo Gallery</a></li>
                        <li><a href="newspaper.php">News Paper</a></li>
                    </ul>
                </li>
                <li><a href="facilities.php">FACILITIES</a></li>
                <li><a href="tender.php">TENDER</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background">
            <img src="assets/college1.jpg" alt="College Building" loading="lazy">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-text">
                    <div class="greeting">
                        <i class="fas fa-<?php echo $greeting_icon; ?>"></i>
                        <span><?php echo $greeting; ?></span>
                    </div>
                    <h1>Empowering Your Future Through Innovation</h1>
                    <p>Discover excellence in education at SDGD College, where tradition meets modern learning to shape tomorrow's leaders.</p>
                    <div class="hero-cta">
                        <a href="courses.php" class="cta-primary">
                            <i class="fas fa-graduation-cap"></i> Explore Courses
                        </a>
                        <a href="contact.php" class="cta-secondary">
                            <i class="fas fa-phone"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['students']); ?>+</h3>
                        <p>Students Enrolled</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['courses']; ?>+</h3>
                        <p>Courses Offered</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['faculty']; ?>+</h3>
                        <p>Expert Faculty</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['placement']; ?>%</h3>
                        <p>Placement Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="featured-courses">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-graduation-cap"></i> Featured Courses</h2>
                <p>Discover our most popular programs designed to shape your future</p>
                <a href="courses.php" class="view-all">View All Courses <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="courses-grid">
                <?php if (count($featured_courses) > 0): ?>
                    <?php foreach ($featured_courses as $course): ?>
                        <div class="course-card">
                            <div class="course-image">
                                <img src="assets/course-placeholder.jpg" alt="<?php echo htmlspecialchars($course['course_name']); ?>" loading="lazy">
                                <div class="course-badge"><?php echo ucfirst(htmlspecialchars($course['course_type'] ?? 'Undergraduate')); ?></div>
                            </div>
                            <div class="course-content">
                                <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration'] ?? 'N/A'); ?></span>
                                    <span><i class="fas fa-rupee-sign"></i> <?php echo number_format($course['fees'] ?? 0); ?></span>
                                </div>
                                <p><?php echo substr(htmlspecialchars($course['description'] ?? 'No description available'), 0, 100); ?>...</p>
                                <a href="courses.php" class="course-link">Learn More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-courses">
                        <i class="fas fa-book"></i>
                        <h3>Courses Coming Soon</h3>
                        <p>We're preparing exciting new courses for you. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Notices Section -->
                <section class="notices-section">
                    <h3><i class="fas fa-bullhorn"></i> Latest Notices</h3>
                    <div class="notices-list">
                        <?php
                        $query = "SELECT * FROM notices ORDER BY created_at DESC LIMIT 5";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="notice-item">';
                                echo '<span class="notice-date">' . format_date($row['publish_date']) . '</span>';
                                echo '<a href="' . ($row['file_path'] ?: '#') . '" target="_blank" class="notice-title">' . $row['title'] . '</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No notices available.</p>';
                        }
                        ?>
                    </div>
                    <a href="notices.php" class="view-more">View More...</a>
                </section>

                <!-- Tender Notices Section -->
                <section class="tender-section">
                    <h3><i class="fas fa-file-contract"></i> Tender Notices</h3>
                    <div class="tender-list">
                        <?php
                        $query = "SELECT * FROM tenders ORDER BY created_at DESC LIMIT 3";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="tender-item">';
                                echo '<span class="tender-date">' . format_date($row['created_at']) . '</span>';
                                echo '<a href="' . ($row['file_path'] ?: '#') . '" target="_blank" class="tender-title">' . $row['title'] . '</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No tender notices available.</p>';
                        }
                        ?>
                    </div>
                    <a href="tender.php" class="view-more">View More...</a>
                </section>

                <!-- Principal Message Section -->
                <section class="principal-message">
                    <h3><i class="fas fa-user-tie"></i> Principal's Message</h3>
                    <div class="message-content">
                        <?php
                        $query = "SELECT * FROM principal_messages ORDER BY created_at DESC LIMIT 1";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<p>' . substr($row['message'], 0, 200) . '...</p>';
                            echo '<a href="principal-desk.php" class="view-more">View More...</a>';
                        } else {
                            echo '<p>Message from the Principal will be available soon.</p>';
                        }
                        ?>
                    </div>
                </section>
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
                        <li><a href="about.php">About College</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
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

    <script src="script.js"></script>
    
    <style>
        /* Enhanced Header Styles */
        .user-pathways {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .pathway-link {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            transition: background-color 0.3s;
        }
        
        .pathway-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 5px 15px;
            margin-right: 15px;
        }
        
        .search-input {
            background: none;
            border: none;
            color: #fff;
            padding: 5px;
            width: 150px;
            outline: none;
        }
        
        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        
        .search-btn {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 5px;
        }
        
        /* Enhanced Hero Section */
        .hero {
            position: relative;
            height: 70vh;
            min-height: 500px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .hero-background img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(44,62,80,0.8) 0%, rgba(52,73,94,0.6) 100%);
            z-index: 2;
        }
        
        .hero-content {
            position: relative;
            z-index: 3;
            width: 100%;
        }
        
        .hero-text {
            text-align: center;
            color: #fff;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .greeting {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 18px;
            opacity: 0.9;
        }
        
        .greeting i {
            font-size: 24px;
            color: #f39c12;
        }
        
        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .hero-cta {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .cta-primary, .cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .cta-primary {
            background: #f39c12;
            color: #fff;
        }
        
        .cta-primary:hover {
            background: #e67e22;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(243,156,18,0.3);
        }
        
        .cta-secondary {
            background: transparent;
            color: #fff;
            border-color: #fff;
        }
        
        .cta-secondary:hover {
            background: #fff;
            color: #2c3e50;
            transform: translateY(-2px);
        }
        
        /* Statistics Section */
        .stats-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .stat-card {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 48px;
            color: #3498db;
            margin-bottom: 20px;
        }
        
        .stat-info h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stat-info p {
            color: #7f8c8d;
            font-size: 1rem;
        }
        
        /* Featured Courses Section */
        .featured-courses {
            padding: 80px 0;
            background: #fff;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-header h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .section-header h2 i {
            color: #3498db;
        }
        
        .section-header p {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        
        .view-all {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
        }
        
        .view-all:hover {
            color: #2980b9;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .course-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .course-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .course-card:hover .course-image img {
            transform: scale(1.05);
        }
        
        .course-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #3498db;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .course-content {
            padding: 25px;
        }
        
        .course-content h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .course-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .course-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .course-content p {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .course-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
        }
        
        .course-link:hover {
            color: #2980b9;
        }
        
        .no-courses {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .no-courses i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #bdc3c7;
        }
        
        .no-courses h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .user-pathways {
                display: none;
            }
            
            .search-bar {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .hero-text h1 {
                font-size: 2rem;
            }
            
            .hero-text p {
                font-size: 1rem;
            }
            
            .hero-cta {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-primary, .cta-secondary {
                width: 200px;
                justify-content: center;
            }
            
            .stats-grid,
            .courses-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
