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
    <title>Principal Messages - SDGD College</title>
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
                <li><a href="principal-message.php" class="active"><i class="fas fa-envelope"></i> PRINCIPAL MESSAGES</a></li>
                <li><a href="principal-profile.php"><i class="fas fa-id-card"></i> PRINCIPAL PROFILE</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-envelope"></i> Principal Messages</h1>
            <p>Official communications and announcements from the Principal's desk</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?php if (count($messages) > 0): ?>
                <div class="messages-grid">
                    <?php foreach ($messages as $message): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <h3><?php echo $message['title']; ?></h3>
                                <div class="message-meta">
                                    <span class="message-type">
                                        <i class="fas fa-tag"></i>
                                        <?php echo ucfirst($message['message_type']); ?>
                                    </span>
                                    <span class="message-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo format_date($message['created_at']); ?>
                                    </span>
                                </div>
                            </div>
                            </div>
                            
                            <div class="message-content">
                                <div class="message-text">
                                    <?php echo nl2br($message['message']); ?>
                                </div>
                                
                                <div class="message-footer">
                                    <span class="target-audience">
                                        <i class="fas fa-users"></i>
                                        For: <?php echo ucfirst($message['message_type']); ?>
                                    </span>
                                    <span class="updated-date">
                                        <i class="fas fa-edit"></i>
                                        Updated: <?php echo format_date($message['updated_at'], 'd/m/Y'); ?>
                                    </span>
                                </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-messages">
                    <i class="fas fa-inbox"></i>
                    <h3>No Messages Available</h3>
                    <p>There are currently no messages from the Principal's desk.</p>
                    <p>Please check back later for new announcements.</p>
                </div>
            <?php endif; ?>
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
        
        .messages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .message-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .message-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
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
            font-size: 18px;
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
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .message-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .message-content {
            padding: 25px;
        }
        
        .message-text {
            line-height: 1.6;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .message-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .target-audience {
            background: #27ae60;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .updated-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .no-messages {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-messages i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .no-messages h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .no-messages p {
            color: #7f8c8d;
            margin: 0;
        }
    </style>
</body>
</html>
