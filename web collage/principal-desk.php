<?php
require_once 'config.php';

// Get principal messages
$query = "SELECT * FROM principal_messages WHERE status = 'active' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal's Desk - SDGD College</title>
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
                <li><a href="principal-desk.php" class="active"><i class="fas fa-user-tie"></i> PRINCIPAL DESK</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-user-tie"></i> Principal's Desk</h1>
            <p>Messages and insights from the Principal of SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Principal Messages -->
            <div class="principal-messages">
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <h3><?php echo $message['title']; ?></h3>
                                <div class="message-meta">
                                    <span class="message-type">
                                        <i class="fas fa-tag"></i> <?php echo ucfirst($message['message_type']); ?>
                                    </span>
                                    <span class="message-date">
                                        <i class="fas fa-calendar"></i> <?php echo format_date($message['created_at']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="message-content">
                                <div class="message-text">
                                    <?php echo nl2br($message['message']); ?>
                                </div>
                            </div>
                            
                            <div class="message-footer">
                                <span class="target-audience">
                                    <i class="fas fa-users"></i> For: <?php echo ucfirst($message['message_type']); ?>
                                </span>
                                <span class="updated-date">
                                    <i class="fas fa-edit"></i> Updated: <?php echo format_date($message['updated_at'], 'd/m/Y'); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-messages">
                        <i class="fas fa-user-tie"></i>
                        <h3>No Messages Available</h3>
                        <p>Messages from the Principal will be available soon.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Principal Information -->
            <div class="principal-info-section">
                <h2><i class="fas fa-info-circle"></i> About the Principal</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-user-tie"></i>
                            <h3>Principal's Message</h3>
                        </div>
                        <div class="info-content">
                            <p>The Principal's desk serves as the bridge between the college administration, faculty, students, and parents. It is committed to fostering academic excellence, maintaining discipline, and ensuring the overall development of students.</p>
                            <div class="key-points">
                                <h4>Key Responsibilities:</h4>
                                <ul>
                                    <li>Academic leadership and vision</li>
                                    <li>Student welfare and development</li>
                                    <li>Faculty management and development</li>
                                    <li>Administrative oversight</li>
                                    <li>Community engagement</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-bullhorn"></i>
                            <h3>Communication Channels</h3>
                        </div>
                        <div class="info-content">
                            <p>The Principal maintains open communication channels with all stakeholders through regular meetings, circulars, and personal interactions.</p>
                            <div class="contact-methods">
                                <h4>Available For:</h4>
                                <ul>
                                    <li><i class="fas fa-users"></i> Student Consultations</li>
                                    <li><i class="fas fa-chalkboard-teacher"></i> Faculty Meetings</li>
                                    <li><i class="fas fa-user-graduate"></i> Parent Interactions</li>
                                    <li><i class="fas fa-hands-helping"></i> Grievance Redressal</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-graduation-cap"></i>
                            <h3>Academic Vision</h3>
                        </div>
                        <div class="info-content">
                            <p>Our vision is to create a nurturing environment that promotes academic excellence, character building, and holistic development of every student.</p>
                            <div class="vision-points">
                                <h4>Our Commitments:</h4>
                                <ul>
                                    <li>Quality education for all</li>
                                    <li>Character development programs</li>
                                    <li>Research and innovation focus</li>
                                    <li>Sports and cultural activities</li>
                                    <li>Career guidance and placement</li>
                                </ul>
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
                        <li><a href="about.php">About College</a></li>
                        <li><a href="notices.php">Notices</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
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
        
        .principal-messages {
            margin-bottom: 40px;
        }
        
        .message-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .message-card:hover {
            transform: translateY(-5px);
        }
        
        .message-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .message-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }
        
        .message-meta {
            display: flex;
            gap: 15px;
            align-items: center;
            font-size: 14px;
        }
        
        .message-type {
            background: rgba(255,255,255,0.2);
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .message-date {
            opacity: 0.9;
        }
        
        .message-content {
            padding: 25px;
        }
        
        .message-text {
            line-height: 1.8;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .message-footer {
            background: #f8f9fa;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .target-audience,
        .updated-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .principal-info-section {
            margin-top: 40px;
        }
        
        .principal-info-section h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .info-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .info-header {
            background: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .info-header i {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .info-header h3 {
            margin: 0;
            font-size: 18px;
        }
        
        .info-content {
            padding: 25px;
        }
        
        .info-content p {
            line-height: 1.6;
            color: #5a6c7d;
            margin-bottom: 20px;
        }
        
        .key-points,
        .contact-methods,
        .vision-points {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .key-points h4,
        .contact-methods h4,
        .vision-points h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .key-points ul,
        .contact-methods ul,
        .vision-points ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .key-points li,
        .contact-methods li,
        .vision-points li {
            margin-bottom: 8px;
            color: #5a6c7d;
            line-height: 1.5;
        }
        
        .key-points li i,
        .contact-methods li i,
        .vision-points li i {
            color: #3498db;
            margin-right: 8px;
            width: 16px;
        }
        
        .no-messages {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .no-messages i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .no-messages h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .message-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .message-meta {
                justify-content: center;
            }
            
            .message-footer {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
