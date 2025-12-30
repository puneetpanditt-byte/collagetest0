<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructions - SDGD College</title>
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
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="instructions.php" class="active"><i class="fas fa-list"></i> INSTRUCTIONS</a></li>
                <li><a href="rules.php"><i class="fas fa-gavel"></i> RULES</a></li>
                <li><a href="holidays.php"><i class="fas fa-calendar-alt"></i> HOLIDAYS</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-list"></i> Important Instructions</h1>
            <p>Guidelines and instructions for students, parents, and visitors</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- General Instructions -->
            <section class="instruction-section">
                <h2><i class="fas fa-info-circle"></i> General Instructions</h2>
                <div class="instruction-content">
                    <div class="instruction-item">
                        <h3><i class="fas fa-user-graduate"></i> For Students</h3>
                        <ul>
                            <li>Students must carry their identity cards at all times while on campus</li>
                            <li>Attendance in all classes is mandatory. Minimum 75% attendance is required to appear in examinations</li>
                            <li>Students should wear proper college uniform as per the dress code</li>
                            <li>Use of mobile phones is strictly prohibited in classrooms and examination halls</li>
                            <li>Students must maintain discipline and follow the code of conduct</li>
                            <li>All students must register their attendance in the college portal within the first week of each month</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-users"></i> For Parents/Guardians</h3>
                        <ul>
                            <li>Ensure regular attendance of your ward in college</li>
                            <li>Monitor academic progress and communicate with teachers regularly</li>
                            <li>Pay all college fees on time to avoid late fees and penalties</li>
                            <li>Attend parent-teacher meetings organized by the college</li>
                            <li>Update contact information with the college office in case of any changes</li>
                            <li>Check college notice board regularly for important announcements</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-chalkboard-teacher"></i> For Visitors</h3>
                        <ul>
                            <li>All visitors must register at the college gate and obtain visitor pass</li>
                            <li>Visitors are not allowed to enter classrooms during teaching hours without prior permission</li>
                            <li>Maintain silence and discipline in academic areas</li>
                            <li>Photography is strictly prohibited in college premises without permission</li>
                            <li>Visitors must follow the dress code and maintain decorum</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Academic Instructions -->
            <section class="instruction-section">
                <h2><i class="fas fa-graduation-cap"></i> Academic Instructions</h2>
                <div class="instruction-content">
                    <div class="instruction-item">
                        <h3><i class="fas fa-book"></i> Library Rules</h3>
                        <ul>
                            <li>Students must obtain library card before borrowing books</li>
                            <li>Books can be borrowed for 14 days and renewed for another 14 days</li>
                            <li>Reference books are for reading in library only and cannot be issued</li>
                            <li>Late return of books will incur fine as per library rules</li>
                            <li>Maintain silence and discipline in the library premises</li>
                            <li>Use of mobile phones and laptops is not allowed in the library</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-flask"></i> Laboratory Rules</h3>
                        <ul>
                            <li>Students must wear lab coats and safety equipment as required</li>
                            <li>Follow instructions of lab instructors carefully</li>
                            <li>Handle equipment with care and report any damage immediately</li>
                            <li>No food or drinks allowed inside laboratories</li>
                            <li>Students must maintain lab records and submit assignments on time</li>
                            <li>Emergency exits and safety equipment locations must be known</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-edit"></i> Examination Guidelines</h3>
                        <ul>
                            <li>Students must reach examination hall 30 minutes before exam time</li>
                            <li>Carry required stationery and identity card</li>
                            <li>Mobile phones, smart watches, and electronic devices are strictly prohibited</li>
                            <li>Students found with unfair means will be debarred from examinations</li>
                            <li>Follow seating arrangement and invigilator instructions</li>
                            <li>No talking or communication with other students during examination</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Safety Instructions -->
            <section class="instruction-section">
                <h2><i class="fas fa-shield-alt"></i> Safety & Security</h2>
                <div class="instruction-content">
                    <div class="instruction-item">
                        <h3><i class="fas fa-fire-extinguisher"></i> Emergency Procedures</h3>
                        <ul>
                            <li>In case of fire, follow evacuation routes marked in college buildings</li>
                            <li>Emergency contact numbers are displayed on notice boards and college website</li>
                            <li>First aid boxes are available in each department and laboratory</li>
                            <li>Report any safety concerns to the college administration immediately</li>
                            <li>Participate in all safety drills conducted by the college</li>
                            <li>Emergency assembly points are marked in college campus</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-car"></i> Vehicle Parking</h3>
                        <ul>
                            <li>Students must park vehicles only in designated parking areas</li>
                            <li>Two-wheelers and four-wheelers have separate parking zones</li>
                            <li>Vehicle registration with college security is mandatory</li>
                            <li>Parking stickers must be displayed prominently on vehicles</li>
                            <li>College is not responsible for loss or damage to unregistered vehicles</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Digital Instructions -->
            <section class="instruction-section">
                <h2><i class="fas fa-laptop"></i> Digital Guidelines</h2>
                <div class="instruction-content">
                    <div class="instruction-item">
                        <h3><i class="fas fa-wifi"></i> Internet & Computer Lab Usage</h3>
                        <ul>
                            <li>College Wi-Fi is for academic purposes only</li>
                            <li>Login credentials are personal and must not be shared</li>
                            <li>Access to inappropriate websites is strictly prohibited</li>
                            <li>Download of unauthorized software is not allowed</li>
                            <li>Students must log off properly after using college computers</li>
                            <li>Report any technical issues to the IT department immediately</li>
                        </ul>
                    </div>
                    
                    <div class="instruction-item">
                        <h3><i class="fas fa-mobile-alt"></i> Mobile Phone Policy</h3>
                        <ul>
                            <li>Mobile phones must be switched off during lectures and examinations</li>
                            <li>Use of mobile phones is allowed only in designated areas</li>
                            <li>Phones with camera are not allowed in examination halls</li>
                            <li>College is not responsible for lost or stolen mobile phones</li>
                            <li>Emergency calls can be taken with prior permission from faculty</li>
                        </ul>
                    </div>
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="notices.php">Notices</a></li>
                        <li><a href="tender.php">Tenders</a></li>
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
        
        .instruction-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .instruction-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .instruction-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .instruction-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .instruction-item h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .instruction-item i {
            font-size: 20px;
            color: #3498db;
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
            color: #3498db;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .instruction-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
