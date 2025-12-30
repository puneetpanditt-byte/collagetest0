<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Committees - SDGD College</title>
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
                    <a href="magazine.php" class="magazine-link"><i class="fas fa-book"></i> College Magazine</a></a>
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
                <li><a href="committee.php" class="active"><i class="fas fa-users"></i> COMMITTEES</a></li>
                <li><a href="principal-desk.php"><i class="fas fa-user-tie"></i> PRINCIPAL DESK</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-users"></i> College Committees</h1>
            <p>Various committees working together for the development and governance of SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Academic Council -->
            <section class="committee-section">
                <h2><i class="fas fa-graduation-cap"></i> Academic Council</h2>
                <div class="committee-content">
                    <p>The Academic Council is the supreme academic body responsible for making decisions regarding academic policies, curriculum development, and educational standards.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-crown"></i>
                                    <h4>Chairman</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Dr. [Principal Name]</p>
                                    <p class="member-designation">Principal</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-tie"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Dr. [Vice Principal Name]</p>
                                    <p class="member-designation">Vice Principal</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">All Heads of Departments</p>
                                    <p class="member-designation">Department Heads</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Examination Committee -->
            <section class="committee-section">
                <h2><i class="fas fa-clipboard-check"></i> Examination Committee</h2>
                <div class="committee-content">
                    <p>Responsible for conducting fair and transparent examinations, maintaining academic integrity, and ensuring proper evaluation processes.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-shield"></i>
                                    <h4>Controller of Examinations</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Dr. [Name]</p>
                                    <p class="member-designation">Controller of Examinations</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-tie"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Senior Faculty</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Senior Faculty</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Discipline Committee -->
            <section class="committee-section">
                <h2><i class="fas fa-gavel"></i> Discipline Committee</h2>
                <div class="committee-content">
                    <p>Ensures maintenance of discipline and decorum among students, handles disciplinary matters, and promotes a conducive learning environment.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-shield"></i>
                                    <h4>Chairman</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Dr. [Principal Name]</p>
                                    <p class="member-designation">Principal</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-tie"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Senior Faculty</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-tie"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Senior Faculty</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cultural Committee -->
            <section class="committee-section">
                <h2><i class="fas fa-music"></i> Cultural Committee</h2>
                <div class="committee-content">
                    <p>Organizes cultural events, festivals, and extracurricular activities to promote holistic development of students.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-star"></i>
                                    <h4>Coordinator</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Cultural Coordinator</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-users"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Faculty Member</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-users"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Faculty Member</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sports Committee -->
            <section class="committee-section">
                <h2><i class="fas fa-futbol"></i> Sports Committee</h2>
                <div class="committee-content">
                    <p>Promotes sports activities, organizes tournaments, and encourages physical fitness and sportsmanship among students.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-trophy"></i>
                                    <h4>Sports Officer</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Sports Officer</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-users"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Physical Education Teacher</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-users"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Faculty Member</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Library Committee -->
            <section class="committee-section">
                <h2><i class="fas fa-book"></i> Library Committee</h2>
                <div class="committee-content">
                    <p>Oversees library development, book procurement, and promotes reading culture among students and faculty.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-book-reader"></i>
                                    <h4>Convener</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Librarian</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-users"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Library Staff</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-users"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Faculty Representative</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Anti-Ragging Committee -->
            <section class="committee-section">
                <h2><i class="fas fa-shield-alt"></i> Anti-Ragging Committee</h2>
                <div class="committee-content">
                    <p>Ensures ragging-free campus environment, handles complaints, and takes preventive measures to maintain student safety.</p>
                    
                    <div class="committee-members">
                        <h3>Members</h3>
                        <div class="members-grid">
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-shield"></i>
                                    <h4>Chairman</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Dr. [Principal Name]</p>
                                    <p class="member-designation">Principal</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-tie"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Senior Faculty</p>
                                </div>
                            </div>
                            
                            <div class="member-item">
                                <div class="member-role">
                                    <i class="fas fa-user-tie"></i>
                                    <h4>Member</h4>
                                </div>
                                <div class="member-details">
                                    <p class="member-name">Shri [Name]</p>
                                    <p class="member-designation">Faculty Member</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="anti-ragging-info">
                        <h4><i class="fas fa-phone"></i> Contact for Anti-Ragging Complaints</h4>
                        <p><strong>Helpline:</strong> +91-XXXX-XXXXXX</p>
                        <p><strong>Email:</strong> antiragging@sdgdcnauhatta.ac.in</p>
                        <p><strong>Address:</strong> Anti-Ragging Committee, SDGD College, Nauhatta</p>
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
        
        .committee-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .committee-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .committee-content {
            line-height: 1.8;
            color: #5a6c7d;
            margin-bottom: 30px;
        }
        
        .committee-members h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .member-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
            text-align: center;
        }
        
        .member-role {
            margin-bottom: 15px;
        }
        
        .member-role i {
            font-size: 24px;
            color: #3498db;
            margin-bottom: 10px;
        }
        
        .member-role h4 {
            color: #2c3e50;
            margin: 0;
            font-size: 16px;
        }
        
        .member-details {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .member-name {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .member-designation {
            color: #7f8c8d;
            margin: 0;
        }
        
        .anti-ragging-info {
            background: #e74c3c;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .anti-ragging-info h4 {
            color: white;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .anti-ragging-info p {
            margin: 5px 0;
        }
        
        .anti-ragging-info strong {
            color: #f39c12;
        }
        
        @media (max-width: 768px) {
            .members-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
