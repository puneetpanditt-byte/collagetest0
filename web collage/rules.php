<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Rules - SDGD College</title>
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
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="instructions.php"><i class="fas fa-list"></i> INSTRUCTIONS</a></li>
                <li><a href="rules.php" class="active"><i class="fas fa-gavel"></i> RULES</a></li>
                <li><a href="holidays.php"><i class="fas fa-calendar-alt"></i> HOLIDAYS</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-gavel"></i> College Rules & Regulations</h1>
            <p>Comprehensive guidelines for maintaining discipline and academic excellence</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Attendance Rules -->
            <section class="rules-section">
                <h2><i class="fas fa-calendar-check"></i> Attendance Rules</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>Minimum Attendance Requirement</h3>
                        <p>Students must maintain a minimum of 75% attendance in each subject to be eligible to appear in university examinations.</p>
                        <p>Attendance below 60% may lead to detention of the student.</p>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Leave Application</h3>
                        <p>Students must apply for leave at least 3 days in advance through proper channels.</p>
                        <p>Medical leave requires a doctor's certificate.</p>
                        <p>Leave applications must be approved by the Head of Department.</p>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Late Attendance</h3>
                        <p>Students arriving late to college will be marked as late.</p>
                        <p>Three consecutive late arrivals may lead to disciplinary action.</p>
                        <p>Parents will be informed of regular late attendance.</p>
                    </div>
                </div>
            </section>

            <!-- Dress Code -->
            <section class="rules-section">
                <h2><i class="fas fa-tshirt"></i> Dress Code</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>Uniform Requirements</h3>
                        <p>All students must wear the prescribed college uniform during college hours.</p>
                        <p>Uniform must be clean, ironed, and in proper condition.</p>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Male Students</h3>
                        <ul>
                            <li>Light blue shirt with college logo</li>
                            <li>Dark blue trousers</li>
                            <li>Black shoes with white socks</li>
                            <li>College tie during formal occasions</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Female Students</h3>
                        <ul>
                            <li>Light blue kameez/salwar with college logo</li>
                            <li>Dark blue dupatta</li>
                            <li>Black shoes with white socks</li>
                            <li>College stole during formal occasions</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Prohibited Items</h3>
                        <ul>
                            <li>Jewelry, expensive watches, and accessories</li>
                            <li>T-shirts with messages or inappropriate graphics</li>
                            <li>Torn or dirty uniforms</li>
                            <li>Sports shoes except on physical education days</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Conduct Rules -->
            <section class="rules-section">
                <h2><i class="fas fa-user-friends"></i> Code of Conduct</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>General Behavior</h3>
                        <ul>
                            <li>Maintain discipline and respect for teachers, staff, and fellow students</li>
                            <li>Use of abusive language is strictly prohibited</li>
                            <li>Any form of ragging or bullying will lead to immediate expulsion</li>
                            <li>Respect college property and maintain cleanliness</li>
                            <li>Follow instructions given by teachers and college authorities</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Academic Integrity</h3>
                        <ul>
                            <li>Any form of cheating in examinations is strictly prohibited</li>
                            <li>Plagiarism in assignments will result in severe penalties</li>
                            <li>Use of unfair means in assessments will lead to debarment</li>
                            <li>Students caught cheating will be reported to university</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Prohibited Activities</h3>
                        <ul>
                            <li>Smoking and consumption of alcohol in college premises</li>
                            <li>Use of drugs or any intoxicating substances</li>
                            <li>Political activities or unauthorized meetings</li>
                            <li>Damage to college property</li>
                            <li>Theft or misappropriation of college belongings</li>
                            <li>Any form of harassment or discrimination</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Library Rules -->
            <section class="rules-section">
                <h2><i class="fas fa-book"></i> Library Rules</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>Membership & Access</h3>
                        <ul>
                            <li>Valid college identity card is mandatory for library access</li>
                            <li>Library cards must be shown to library staff when requested</li>
                            <li>Outside materials are not permitted without prior permission</li>
                            <li>Silence must be maintained in reading areas</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Book Issuance</h3>
                        <ul>
                            <li>Maximum 3 books can be issued at a time</li>
                            <li>Books are issued for 14 days and can be renewed once</li>
                            <li>Reference books are for reading in library only</li>
                            <li>Late return of books will incur fine as per library rules</li>
                            <li>Lost or damaged books must be replaced or paid for</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Library Discipline</h3>
                        <ul>
                            <li>Eating and drinking are not allowed in library premises</li>
                            <li>Mobile phones must be switched off in the library</li>
                            <li>Photography is not permitted without prior permission</li>
                            <li>Maintain silence and decorum at all times</li>
                            <li>Respect library staff and fellow students</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Examination Rules -->
            <section class="rules-section">
                <h2><i class="fas fa-clipboard-check"></i> Examination Rules</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>Examination Hall Protocol</h3>
                        <ul>
                            <li>Students must reach examination hall 30 minutes before exam time</li>
                            <li>Valid identity card and admit card are mandatory</li>
                            <li>No electronic devices, calculators, or unauthorized materials allowed</li>
                            <li>Students must occupy allotted seats only</li>
                            <li>Communication with other students is strictly prohibited</li>
                            <li>Students must follow invigilator instructions</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Malpractice Prevention</h3>
                        <ul>
                            <li>Any form of cheating will result in immediate cancellation of examination</li>
                            <li>Students found with unauthorized materials will be debarred</li>
                            <li>Impersonation or helping others will lead to strict action</li>
                            <li>Mobile phones found in possession will be confiscated</li>
                            <li>Violation of examination rules will be reported to university</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Vehicle Rules -->
            <section class="rules-section">
                <h2><i class="fas fa-motorcycle"></i> Vehicle Rules</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>Registration Requirements</h3>
                        <ul>
                            <li>All vehicles must be registered with college security</li>
                            <li>Valid driving license and registration documents required</li>
                            <li>Vehicle must have valid insurance and pollution certificate</li>
                            <li>Registration sticker must be displayed prominently</li>
                            <li>Annual registration renewal is mandatory</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Parking Regulations</h3>
                        <ul>
                            <li>Vehicles must be parked only in designated parking areas</li>
                            <li>Two-wheelers and four-wheelers have separate parking zones</li>
                            <li>Parking on college roads is strictly prohibited</li>
                            <li>Vehicle owners are responsible for their vehicles</li>
                            <li>College is not responsible for theft or damage</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Traffic Rules</h3>
                        <ul>
                            <li>Follow speed limits within college campus (20 km/h)</li>
                            <li>Wear helmets compulsorily for two-wheelers</li>
                            <li>Observe traffic signs and instructions from security staff</li>
                            <li>Violations may lead to parking privileges withdrawal</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Hostel Rules -->
            <section class="rules-section">
                <h2><i class="fas fa-bed"></i> Hostel Rules</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <h3>Admission & Accommodation</h3>
                        <ul>
                            <li>Hostel admission is subject to availability of seats</li>
                            <li>Students must submit required documents and fees on time</li>
                            <li>Parents/guardians must provide local emergency contact</li>
                            <li>Students must sign hostel agreement at time of admission</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Living Guidelines</h3>
                        <ul>
                            <li>Students must follow mess timings for meals</li>
                            <li>Visitors are allowed only with prior permission from warden</li>
                            <li>Night entry/exit times must be strictly followed</li>
                            <li>Room must be kept clean and tidy</li>
                            <li>No electrical appliances allowed except college-provided ones</li>
                        </ul>
                    </div>
                    
                    <div class="rule-item">
                        <h3>Discipline & Safety</h3>
                        <ul>
                            <li>Ragging in any form is strictly prohibited and punishable</li>
                            <li>Any form of harassment will lead to immediate expulsion</li>
                            <li>Students must maintain silence during study hours</li>
                            <li>Alcohol, smoking, and drugs are strictly prohibited</li>
                            <li>Emergency contact numbers must be updated with warden</li>
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
        
        .rules-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .rules-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .rules-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .rule-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .rule-item h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .rule-item h3 i {
            font-size: 20px;
            color: #3498db;
        }
        
        .rule-item ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .rule-item li {
            color: #5a6c7d;
            margin-bottom: 8px;
            line-height: 1.6;
            position: relative;
            padding-left: 25px;
        }
        
        .rule-item li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #3498db;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .rules-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
