<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - SDGD College</title>
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
                <li><a href="administration.php" class="active"><i class="fas fa-users-cog"></i> ADMINISTRATION</a></li>
                <li><a href="principal-desk.php"><i class="fas fa-user-tie"></i> PRINCIPAL DESK</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-users-cog"></i> College Administration</h1>
            <p>Meet the dedicated team managing SDGD College operations and academic excellence</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Principal -->
            <section class="admin-section">
                <h2><i class="fas fa-user-tie"></i> Principal</h2>
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="assets/principal.jpg" alt="Principal" class="admin-image">
                    </div>
                    <div class="admin-info">
                        <h3>Dr. [Principal Name]</h3>
                        <p class="admin-designation">Principal</p>
                        <p class="admin-qualification">M.Phil, Ph.D.</p>
                        <p class="admin-experience">15+ years of experience in higher education</p>
                        <div class="admin-contact">
                            <p><i class="fas fa-envelope"></i> principal@sdgdcnauhatta.ac.in</p>
                            <p><i class="fas fa-phone"></i> +91-XXXX-XXXXXX</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vice Principal -->
            <section class="admin-section">
                <h2><i class="fas fa-user-tie"></i> Vice Principal</h2>
                <div class="admin-card">
                    <div class="admin-photo">
                        <img src="assets/vice-principal.jpg" alt="Vice Principal" class="admin-image">
                    </div>
                    <div class="admin-info">
                        <h3>Dr. [Vice Principal Name]</h3>
                        <p class="admin-designation">Vice Principal</p>
                        <p class="admin-qualification">M.A., M.Phil.</p>
                        <p class="admin-experience">12+ years of academic experience</p>
                        <div class="admin-contact">
                            <p><i class="fas fa-envelope"></i> viceprincipal@sdgdcnauhatta.ac.in</p>
                            <p><i class="fas fa-phone"></i> +91-XXXX-XXXXXX</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Administrative Staff -->
            <section class="admin-section">
                <h2><i class="fas fa-users"></i> Administrative Staff</h2>
                <div class="staff-grid">
                    <div class="staff-card">
                        <div class="staff-photo">
                            <img src="assets/staff/office.jpg" alt="Office Superintendent" class="staff-image">
                        </div>
                        <div class="staff-info">
                            <h3>Office Superintendent</h3>
                            <p class="staff-name">Shri [Name]</p>
                            <p class="staff-qualification">M.Com.</p>
                            <p class="staff-responsibility">Handles administrative operations and student services</p>
                            <p><i class="fas fa-envelope"></i> office@sdgdcnauhatta.ac.in</p>
                        </div>
                    </div>
                    
                    <div class="staff-card">
                        <div class="staff-photo">
                            <img src="assets/staff/accountant.jpg" alt="Accountant" class="staff-image">
                        </div>
                        <div class="staff-info">
                            <h3>Accountant</h3>
                            <p class="staff-name">Shri [Name]</p>
                            <p class="staff-qualification">M.Com.</p>
                            <p class="staff-responsibility">Manages financial operations and accounts</p>
                            <p><i class="fas fa-envelope"></i> accounts@sdgdcnauhatta.ac.in</p>
                        </div>
                    </div>
                    
                    <div class="staff-card">
                        <div class="staff-photo">
                            <img src="assets/staff/library.jpg" alt="Librarian" class="staff-image">
                        </div>
                        <div class="staff-info">
                            <h3>Librarian</h3>
                            <p class="staff-name">Shri [Name]</p>
                            <p class="staff-qualification">M.Lib.Sc.</p>
                            <p class="staff-responsibility">Manages library operations and resources</p>
                            <p><i class="fas fa-envelope"></i> library@sdgdcnauhatta.ac.in</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Department Heads -->
            <section class="admin-section">
                <h2><i class="fas fa-chalkboard-teacher"></i> Department Heads</h2>
                <div class="departments-grid">
                    <div class="dept-card">
                        <div class="dept-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="dept-info">
                            <h3>Head of Department - Hindi</h3>
                            <p class="dept-name">Dr. [Name]</p>
                            <p class="dept-qualification">M.A., Ph.D.</p>
                            <p class="dept-experience">10+ years teaching experience</p>
                            <p><i class="fas fa-envelope"></i> hod.hindi@sdgdcnauhatta.ac.in</p>
                        </div>
                    </div>
                    
                    <div class="dept-card">
                        <div class="dept-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="dept-info">
                            <h3>Head of Department - Science</h3>
                            <p class="dept-name">Dr. [Name]</p>
                            <p class="dept-qualification">M.Sc., Ph.D.</p>
                            <p class="dept-experience">12+ years teaching experience</p>
                            <p><i class="fas fa-envelope"></i> hod.science@sdgdcnauhatta.ac.in</p>
                        </div>
                    </div>
                    
                    <div class="dept-card">
                        <div class="dept-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="dept-info">
                            <h3>Head of Department - Commerce</h3>
                            <p class="dept-name">Shri [Name]</p>
                            <p class="dept-qualification">M.Com., M.Phil.</p>
                            <p class="dept-experience">8+ years teaching experience</p>
                            <p><i class="fas fa-envelope"></i> hod.commerce@sdgdcnauhatta.ac.in</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Supporting Staff -->
            <section class="admin-section">
                <h2><i class="fas fa-hands-helping"></i> Supporting Staff</h2>
                <div class="supporting-grid">
                    <div class="support-item">
                        <i class="fas fa-laptop"></i>
                        <h3>IT Administrator</h3>
                        <p>Manages computer lab and technical infrastructure</p>
                        <p><i class="fas fa-envelope"></i> it@sdgdcnauhatta.ac.in</p>
                    </div>
                    
                    <div class="support-item">
                        <i class="fas fa-futbol"></i>
                        <h3>Sports Officer</h3>
                        <p>Organizes sports activities and physical education</p>
                        <p><i class="fas fa-envelope"></i> sports@sdgdcnauhatta.ac.in</p>
                    </div>
                    
                    <div class="support-item">
                        <i class="fas fa-utensils"></i>
                        <h3>Canteen Manager</h3>
                        <p>Manages canteen operations and food services</p>
                        <p><i class="fas fa-envelope"></i> canteen@sdgdcnauhatta.ac.in</p>
                    </div>
                    
                    <div class="support-item">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Security Officer</h3>
                        <p>Ensures campus security and safety</p>
                        <p><i class="fas fa-envelope"></i> security@sdgdcnauhatta.ac.in</p>
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
        
        .admin-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .admin-section h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .admin-card {
            display: flex;
            gap: 30px;
            align-items: flex-start;
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .admin-photo {
            flex-shrink: 0;
        }
        
        .admin-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            border: 3px solid #3498db;
        }
        
        .admin-info {
            flex: 1;
        }
        
        .admin-info h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        
        .admin-designation {
            color: #3498db;
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .admin-qualification {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .admin-experience {
            color: #27ae60;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .admin-contact p {
            color: #5a6c7d;
            margin: 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-contact i {
            color: #3498db;
            width: 20px;
        }
        
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .staff-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
            text-align: center;
        }
        
        .staff-photo {
            margin-bottom: 20px;
        }
        
        .staff-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #27ae60;
        }
        
        .staff-info h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .staff-name {
            color: #27ae60;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .staff-qualification {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .staff-responsibility {
            color: #5a6c7d;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        
        .departments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .dept-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #f39c12;
            text-align: center;
        }
        
        .dept-icon {
            font-size: 32px;
            color: #f39c12;
            margin-bottom: 15px;
        }
        
        .dept-info h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .dept-name {
            color: #f39c12;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .dept-qualification {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .dept-experience {
            color: #27ae60;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .supporting-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .support-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #9b59b6;
            text-align: center;
        }
        
        .support-item i {
            font-size: 32px;
            color: #9b59b6;
            margin-bottom: 15px;
        }
        
        .support-item h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .support-item p {
            color: #7f8c8d;
            margin: 5px 0;
        }
        
        .support-item p:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .admin-card {
                flex-direction: column;
                text-align: center;
            }
            
            .staff-grid,
            .departments-grid,
            .supporting-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
