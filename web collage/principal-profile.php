<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal Profile - SDGD College</title>
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
                <li><a href="principal-desk.php"><i class="fas fa-user-tie"></i> PRINCIPAL DESK</a></li>
                <li><a href="principal-message.php"><i class="fas fa-envelope"></i> PRINCIPAL MESSAGES</a></li>
                <li><a href="principal-profile.php" class="active"><i class="fas fa-id-card"></i> PRINCIPAL PROFILE</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-id-card"></i> Principal Profile</h1>
            <p>Professional profile and credentials of the Principal</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-photo">
                        <img src="assets/principal.jpg" alt="Principal" class="principal-image">
                        <div class="photo-overlay">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2>Dr. [Principal Name]</h2>
                        <p class="designation">Principal, Sub Divisional Government Degree College, Nauhatta</p>
                        <p class="qualifications">
                            <strong>Qualifications:</strong><br>
                            M.Phil (Education)<br>
                            Ph.D. (Higher Education)<br>
                            Post Doctoral Research in Educational Administration
                        </p>
                        </div>
                    </div>
                </div>
                
                <div class="profile-content">
                    <div class="profile-section">
                        <h3><i class="fas fa-user-graduate"></i> Academic Background</h3>
                        <div class="section-content">
                            <div class="info-item">
                                <h4>Education</h4>
                                <p>Completed Doctoral studies in Educational Administration with specialization in Higher Education Policy and Management.</p>
                                <p><strong>Research Focus:</strong> Quality improvement in higher education, curriculum development, and educational leadership.</p>
                            </div>
                            
                            <div class="info-item">
                                <h4>Experience</h4>
                                <p>Over 20 years of experience in educational administration and teaching at various levels.</p>
                                <p><strong>Previous Positions:</strong></p>
                                <ul>
                                    <li>Principal, Government College (2015-Present)</li>
                                    <li>Head of Department, Government College (2010-2015)</li>
                                    <li>Assistant Professor, University College (2005-2010)</li>
                                    <li>Lecturer, Various Colleges (2000-2005)</li>
                                </ul>
                            </div>
                            
                            <div class="info-item">
                                <h4>Publications</h4>
                                <p>Published numerous research papers in national and international journals on higher education and educational leadership.</p>
                                <p><strong>Books Authored:</strong> 5 books on educational administration and quality assurance in higher education.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h3><i class="fas fa-award"></i> Achievements & Awards</h3>
                        <div class="section-content">
                            <div class="info-item">
                                <h4>Academic Excellence</h4>
                                <ul>
                                    <li>Best Principal Award by State Government (2023)</li>
                                    <li>Educational Leadership Award by University Grants Commission (2022)</li>
                                    <li>Quality Initiative Award by Ministry of Education (2021)</li>
                                </ul>
                            </div>
                            
                            <div class="info-item">
                                <h4>Research Recognition</h4>
                                <p>Research papers cited in educational policy documents and implemented in various college improvement programs.</p>
                            </div>
                            
                            <div class="info-item">
                                <h4>Professional Memberships</h4>
                                <ul>
                                    <li>Life Member, Indian Association of Higher Education</li>
                                    <li>Member, Academic Staff College of India</li>
                                    <li>Member, National Assessment and Accreditation Council</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h3><i class="fas fa-bullhorn"></i> Vision & Mission</h3>
                        <div class="section-content">
                            <div class="info-item">
                                <h4>Vision</h4>
                                <p>To establish SDGD College as a center of academic excellence, fostering holistic development of students and contributing to nation-building through quality education and character building.</p>
                            </div>
                            
                            <div class="info-item">
                                <h4>Mission</h4>
                                <p>To provide accessible and quality higher education to students from rural areas, empowering them with knowledge, skills, and values to become responsible citizens and leaders in society.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h3><i class="fas fa-handshake"></i> Professional Associations</h3>
                        <div class="section-content">
                            <div class="info-item">
                                <h4>Current Roles</h4>
                                <ul>
                                    <li>Member, Board of Governors, VKSU, Ara</li>
                                    <li>Member, Academic Council, VKSU</li>
                                    <li>Member, State Level Committee for Quality Enhancement in Higher Education</li>
                                </ul>
                            </div>
                            
                            <div class="info-item">
                                <h4>Previous Roles</h4>
                                <ul>
                                    <li>Chairman, Bihar State University Service Commission</li>
                                    <li>Member, NAAC Peer Team</li>
                                    <li>Expert Member, National Institutional Ranking Framework</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h3><i class="fas fa-quote-left"></i> Message to Students</h3>
                        <div class="message-box">
                            <div class="message-content">
                                <p>Dear Students,</p>
                                <p>As your Principal, I am committed to providing you with the best possible education and learning environment. Our college is not just about academic excellence but also about building character, values, and life skills that will help you succeed in all aspects of life.</p>
                                <p>Remember that education is the key to unlocking your potential and achieving your dreams. I encourage you to make the most of your time here at SDGD College.</p>
                                <p>Together, we can build a bright future for yourselves and our nation.</p>
                            </div>
                            <div class="message-signature">
                                <p><strong>Dr. [Principal Name]</strong></p>
                                <p>Principal, SDGD College, Nauhatta</p>
                            </div>
                        </div>
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
                        <li><a href="principal-desk.php">Principal Desk</a></li>
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
        
        .profile-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .profile-header {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .profile-photo {
            position: relative;
            text-align: center;
        }
        
        .principal-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #3498db;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .photo-overlay {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(52, 152, 219, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .profile-info {
            flex: 1;
        }
        
        .profile-info h2 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 24px;
        }
        
        .designation {
            color: #3498db;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        .qualifications {
            color: #5a6c7d;
            line-height: 1.6;
        }
        
        .qualifications strong {
            color: #2c3e50;
        }
        
        .profile-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .profile-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .profile-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .profile-section h3 i {
            font-size: 20px;
            color: #3498db;
        }
        
        .section-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .info-item h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .info-item p {
            color: #5a6c7d;
            line-height: 1.6;
            margin: 0;
        }
        
        .info-item strong {
            color: #2c3e50;
        }
        
        .message-box {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }
        
        .message-content {
            font-size: 16px;
            line-height: 1.8;
        }
        
        .message-content p {
            margin-bottom: 15px;
        }
        
        .message-signature {
            text-align: right;
            margin-top: 20px;
        }
        
        .message-signature p {
            margin: 5px 0;
        }
        
        .message-signature strong {
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .profile-container,
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-section h3 {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</body>
</html>
