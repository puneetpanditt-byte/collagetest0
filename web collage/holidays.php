<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holidays - SDGD College</title>
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
                <li><a href="instructions.php"><i class="fas fa-list"></i> INSTRUCTIONS</a></li>
                <li><a href="rules.php"><i class="fas fa-gavel"></i> RULES</a></li>
                <li><a href="holidays.php" class="active"><i class="fas fa-calendar-alt"></i> HOLIDAYS</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-calendar-alt"></i> College Holidays</h1>
            <p>Academic calendar and holiday schedule for SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Current Year Holidays -->
            <section class="holiday-section">
                <h2><i class="fas fa-calendar"></i> <?php echo date('Y'); ?> Academic Year Holidays</h2>
                <div class="holiday-content">
                    <div class="holiday-item">
                        <h3><i class="fas fa-sun"></i> Summer Vacation</h3>
                        <div class="holiday-dates">
                            <div class="date-item">
                                <span class="date-label">From:</span>
                                <span class="date-value">15th May 2025</span>
                            </div>
                            <div class="date-item">
                                <span class="date-label">To:</span>
                                <span class="date-value">30th June 2025</span>
                            </div>
                        </div>
                        <p class="holiday-description">Summer vacation for all students and staff. College reopens on 1st July 2025.</p>
                    </div>
                    
                    <div class="holiday-item">
                        <h3><i class="fas fa-umbrella-beach"></i> Puja Vacation</h3>
                        <div class="holiday-dates">
                            <div class="date-item">
                                <span class="date-label">From:</span>
                                <span class="date-value">28th September 2025</span>
                            </div>
                            <div class="date-item">
                                <span class="date-label">To:</span>
                                <span class="date-value">15th October 2025</span>
                            </div>
                        </div>
                        <p class="holiday-description">Durga Puja vacation for all students and staff. College reopens on 16th October 2025.</p>
                    </div>
                    
                    <div class="holiday-item">
                        <h3><i class="fas fa-snowflake"></i> Winter Vacation</h3>
                        <div class="holiday-dates">
                            <div class="date-item">
                                <span class="date-label">From:</span>
                                <span class="date-value">25th December 2025</span>
                            </div>
                            <div class="date-item">
                                <span class="date-label">To:</span>
                                <span class="date-value">15th January 2026</span>
                            </div>
                        </div>
                        <p class="holiday-description">Winter vacation for all students and staff. College reopens on 16th January 2026.</p>
                    </div>
                </div>
            </section>

            <!-- Festival Holidays -->
            <section class="holiday-section">
                <h2><i class="fas fa-star"></i> Festival Holidays <?php echo date('Y'); ?></h2>
                <div class="holiday-content">
                    <div class="holiday-grid">
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-om"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Independence Day</h3>
                                <p class="holiday-date">15th August 2025</p>
                                <p class="holiday-description">National holiday celebrating India's independence</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-dharmachakra"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Janmashtami</h3>
                                <p class="holiday-date">19th August 2025</p>
                                <p class="holiday-description">Birthday of Lord Krishna</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-gandhi"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Gandhi Jayanti</h3>
                                <p class="holiday-date">2nd October 2025</p>
                                <p class="holiday-description">Birth anniversary of Mahatma Gandhi</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-diwali"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Diwali</h3>
                                <p class="holiday-date">21st October 2025</p>
                                <p class="holiday-description">Festival of lights</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-mosque"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Eid-ul-Fitr</h3>
                                <p class="holiday-date">22nd March 2025</p>
                                <p class="holiday-description">Festival marking end of Ramadan</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-mosque"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Eid-ul-Adha</h3>
                                <p class="holiday-date">21st July 2025</p>
                                <p class="holiday-description">Festival of sacrifice</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-om"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Chhath Puja</h3>
                                <p class="holiday-date">6th November 2025</p>
                                <p class="holiday-description">Sun God worship festival</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-om"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Guru Nanak Jayanti</h3>
                                <p class="holiday-date">25th December 2025</p>
                                <p class="holiday-description">Birth anniversary of Guru Nanak</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Republic Day</h3>
                                <p class="holiday-date">26th January 2025</p>
                                <p class="holiday-description">India's Republic Day</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Mahashivratri Jayanti</h3>
                                <p class="holiday-date">19th February 2025</p>
                                <p class="holiday-description">Birth anniversary of Dr. B.R. Ambedkar</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Holi</h3>
                                <p class="holiday-date">14th March 2025</p>
                                <p class="holiday-description">Festival of colors</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>Good Friday</h3>
                                <p class="holiday-date">18th April 2025</p>
                                <p class="holiday-description">Friday before Easter Sunday</p>
                            </div>
                        </div>
                        
                        <div class="holiday-card">
                            <div class="holiday-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="holiday-info">
                                <h3>May Day</h3>
                                <p class="holiday-date">1st May 2025</p>
                                <p class="holiday-description">International Workers' Day</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Weekend Schedule -->
            <section class="holiday-section">
                <h2><i class="fas fa-clock"></i> Weekend Schedule</h2>
                <div class="holiday-content">
                    <div class="schedule-item">
                        <h3><i class="fas fa-calendar-day"></i> Regular Classes</h3>
                        <div class="schedule-info">
                            <p><strong>Monday to Friday:</strong> Regular classes as per timetable</p>
                            <p><strong>Saturday:</strong> Special classes and extra-curricular activities</p>
                            <p><strong>Sunday:</strong> College closed (except during examination periods)</p>
                        </div>
                    </div>
                    
                    <div class="schedule-item">
                        <h3><i class="fas fa-book-open"></i> Library Hours</h3>
                        <div class="schedule-info">
                            <p><strong>Monday to Friday:</strong> 9:00 AM to 5:00 PM</p>
                            <p><strong>Saturday:</strong> 9:00 AM to 4:00 PM</p>
                            <p><strong>Sunday:</strong> Closed</p>
                            <p><strong>Holiday Periods:</strong> Library remains closed during college vacations</p>
                        </div>
                    </div>
                    
                    <div class="schedule-item">
                        <h3><i class="fas fa-utensils"></i> Mess Timings</h3>
                        <div class="schedule-info">
                            <p><strong>Breakfast:</strong> 7:30 AM to 8:30 AM</p>
                            <p><strong>Lunch:</strong> 12:00 PM to 2:00 PM</p>
                            <p><strong>Dinner:</strong> 7:00 PM to 8:00 PM</p>
                            <p><strong>Sunday:</strong> 8:00 AM to 9:00 PM only</p>
                            <p><strong>Special Notes:</strong> Mess remains closed during college vacations</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Important Notes -->
            <section class="holiday-section">
                <h2><i class="fas fa-info-circle"></i> Important Notes</h2>
                <div class="holiday-content">
                    <div class="note-item">
                        <h3><i class="fas fa-exclamation-triangle"></i> Holiday Notifications</h3>
                        <ul>
                            <li>All holidays are subject to change as per university guidelines</li>
                            <li>Students will be informed of any additional holidays through college notice board</li>
                            <li>Examination schedules may affect regular holidays</li>
                            <li>Emergency situations may require college to remain open on scheduled holidays</li>
                        </ul>
                    </div>
                    
                    <div class="note-item">
                        <h3><i class="fas fa-phone"></i> Contact Information</h3>
                        <ul>
                            <li>For holiday-related queries, contact college office during working hours</li>
                            <li>Emergency contact numbers are available for urgent matters</li>
                            <li>Class teachers will inform students about any changes in holiday schedule</li>
                            <li>Parents should regularly check college website and notice board for updates</li>
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
        
        .holiday-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .holiday-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .holiday-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .holiday-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .holiday-item h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .holiday-item h3 i {
            font-size: 20px;
            color: #3498db;
        }
        
        .holiday-dates {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .date-item {
            text-align: center;
        }
        
        .date-label {
            display: block;
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .date-value {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .holiday-description {
            color: #5a6c7d;
            line-height: 1.6;
        }
        
        .holiday-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .holiday-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #f39c12;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .holiday-card:hover {
            transform: translateY(-5px);
        }
        
        .holiday-icon {
            font-size: 32px;
            color: #f39c12;
            margin-bottom: 15px;
        }
        
        .holiday-info h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .holiday-date {
            color: #3498db;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .schedule-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
        }
        
        .schedule-item h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .schedule-item h3 i {
            font-size: 20px;
            color: #27ae60;
        }
        
        .schedule-info p {
            color: #5a6c7d;
            margin: 5px 0;
            line-height: 1.5;
        }
        
        .schedule-info strong {
            color: #2c3e50;
        }
        
        .note-item {
            background: #fff3cd;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #e74c3c;
        }
        
        .note-item h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .note-item h3 i {
            font-size: 20px;
            color: #e74c3c;
        }
        
        .note-item ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .note-item li {
            color: #5a6c7d;
            margin-bottom: 8px;
            line-height: 1.6;
            position: relative;
            padding-left: 25px;
        }
        
        .note-item li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #e74c3c;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .holiday-content,
            .holiday-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
