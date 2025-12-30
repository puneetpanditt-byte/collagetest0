<?php
require_once 'config.php';

// Get examinations from database
$query = "SELECT * FROM examinations WHERE status = 'active' ORDER BY exam_date DESC";
$result = mysqli_query($conn, $query);
$examinations = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get unique exam types for filter
$exam_types = [];
$type_result = mysqli_query($conn, "SELECT DISTINCT exam_type FROM examinations WHERE exam_type != '' ORDER BY exam_type");
while ($row = mysqli_fetch_assoc($type_result)) {
    $exam_types[] = $row['exam_type'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination - SDGD College</title>
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
                <li><a href="examination.php" class="active"><i class="fas fa-clipboard-check"></i> EXAMINATION</a></li>
                <li><a href="results.php"><i class="fas fa-chart-line"></i> RESULTS</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-clipboard-check"></i> Examination Information</h1>
            <p>Examination schedules, timetables, and important instructions</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search by title or description..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        
                        <select name="exam_type">
                            <option value="">All Types</option>
                            <?php foreach ($exam_types as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo ($_GET['exam_type'] ?? '') === $type ? 'selected' : ''; ?>>
                                    <?php echo $type; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="examination.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Examination Schedule -->
            <div class="exam-schedule">
                <h2><i class="fas fa-calendar-alt"></i> Upcoming Examinations</h2>
                <div class="exam-grid">
                    <?php if (count($examinations) > 0): ?>
                        <?php foreach ($examinations as $exam): ?>
                            <div class="exam-card">
                                <div class="exam-header">
                                    <h4><?php echo $exam['title']; ?></h4>
                                    <span class="exam-type"><?php echo ucfirst($exam['exam_type']); ?></span>
                                    <span class="exam-date"><?php echo format_date($exam['exam_date']); ?></span>
                                </div>
                                <div class="exam-details">
                                    <p><strong>Duration:</strong> <?php echo $exam['duration']; ?></p>
                                    <p><strong>Time:</strong> <?php echo $exam['start_time']; ?> - <?php echo $exam['end_time']; ?></p>
                                    <p><strong>Venue:</strong> <?php echo $exam['venue']; ?></p>
                                    <p><strong>Instructions:</strong> <?php echo substr($exam['instructions'], 0, 200); ?>...</p>
                                </div>
                                <div class="exam-actions">
                                    <a href="examination.php?action=view&id=<?php echo $exam['id']; ?>" class="view-btn">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a href="<?php echo $exam['admit_card']; ?>" class="download-btn" target="_blank">
                                        <i class="fas fa-download"></i> Admit Card
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-exams">
                            <i class="fas fa-clipboard-check"></i>
                            <h3>No Examinations Scheduled</h3>
                            <p>No examinations are currently scheduled.</p>
                            <p>Please check back later for updates.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Exam Instructions -->
            <div class="exam-instructions">
                <h2><i class="fas fa-info-circle"></i> Important Instructions</h2>
                <div class="instructions-grid">
                    <div class="instruction-item">
                        <h3><i class="fas fa-clock"></i> Timing</h3>
                        <ul>
                            <li>Students must reach the examination hall 30 minutes before the scheduled time</li>
                            <li>Late comers will not be allowed to enter the examination hall</li>
                            <li>No extra time will be provided under any circumstances</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-id-card"></i> Required Documents</h3>
                        <ul>
                            <li>Valid college identity card is mandatory</li>
                            <li>Admit card must be printed and brought to the examination hall</li>
                            <li>Students without proper documents will not be allowed to appear</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-mobile-alt"></i> Prohibited Items</h3>
                        <ul>
                            <li>Mobile phones, smart watches, and any electronic devices are strictly prohibited</li>
                            <li>Books, notes, or any written material are not allowed</li>
                            <li>Calculator (unless specified) and mathematical instruments are prohibited</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-user-check"></i> Conduct</h3>
                        <ul>
                            <li>Maintain silence and discipline in the examination hall</li>
                            <li>Follow all instructions given by the invigilators</li>
                            <li>Any form of cheating will result in immediate disqualification</li>
                            <li>Students found with unfair means will be reported to the university</li>
                        </ul>
                    </div>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="results.php">Results</a></li>
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
        
        .exam-schedule {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .exam-schedule h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .exam-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .exam-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .exam-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .exam-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        
        .exam-header h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .exam-type {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .exam-date {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .exam-details {
            padding: 20px;
        }
        
        .exam-details p {
            color: #5a6c7d;
            margin: 5px 0;
            line-height: 1.6;
        }
        
        .exam-details strong {
            color: #2c3e50;
        }
        
        .exam-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .view-btn,
        .download-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .view-btn:hover,
        .download-btn:hover {
            background: #2980b9;
        }
        
        .exam-instructions {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .exam-instructions h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }
        
        .instructions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .instruction-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #e74c3c;
        }
        
        .instruction-item h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .instruction-item h3 i {
            font-size: 20px;
            color: #e74c3c;
        }
        
        .instruction-item ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .instruction-item li {
            color: #5a6c7d;
            margin-bottom: 8px;
            line-height: 1.6;
            position: relative;
            padding-left: 25px;
        }
        
        .instruction-item li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #e74c3c;
            font-weight: bold;
        }
        
        .no-exams {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-exams h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .no-exams p {
            color: #7f8c8d;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .exam-grid,
            .instructions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
