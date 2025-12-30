<?php
require_once 'config.php';

// Handle form submission
$message_sent = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $phone = sanitize_input($_POST['phone']);
        $subject = sanitize_input($_POST['subject']);
        $message_content = sanitize_input($_POST['message']);
        
        if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
            $error = 'All fields are required!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address!';
        } else {
            // Insert into database
            $query = "INSERT INTO contact_messages (name, email, phone, subject, message) 
                      VALUES ('$name', '$email', '$phone', '$subject', '$message_content')";
            
            if (mysqli_query($conn, $query)) {
                $message_sent = 'Your message has been sent successfully! We will get back to you soon.';
                
                // Send email notification to admin
                $admin_email = SITE_EMAIL;
                $headers = "From: $email\r\n";
                $headers .= "Reply-To: $email\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                
                $email_body = "
                    <html>
                    <body>
                        <h2>New Contact Message from SDGD College Website</h2>
                        <table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>
                            <tr><td><strong>Name:</strong></td><td>$name</td></tr>
                            <tr><td><strong>Email:</strong></td><td>$email</td></tr>
                            <tr><td><strong>Phone:</strong></td><td>" . ($phone ?: 'Not provided') . "</td></tr>
                            <tr><td><strong>Subject:</strong></td><td>$subject</td></tr>
                            <tr><td><strong>Message:</strong></td><td>" . nl2br($message_content) . "</td></tr>
                        </table>
                        <hr>
                        <p><em>This message was sent from the SDGD College website contact form.</em></p>
                    </body>
                    </html>
                ";
                
                mail($admin_email, "New Contact Message: $subject", $email_body, $headers);
            } else {
                $error = 'Failed to send message. Please try again later.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SDGD College</title>
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
                <li><a href="principal-desk.php"><i class="fas fa-user-tie"></i> PRINCIPAL DESK</a></li>
                <li><a href="contact.php" class="active"><i class="fas fa-envelope"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-envelope"></i> Contact Us</h1>
            <p>Get in touch with SDGD College for admissions, inquiries, or any assistance you need</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Form -->
                <section class="contact-form-section">
                    <h2><i class="fas fa-paper-plane"></i> Send us a Message</h2>
                    
                    <?php if ($message_sent): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $message_sent; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="contact-form">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                       placeholder="Enter your full name">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       placeholder="Enter your email address">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                       placeholder="Enter your phone number">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" id="subject" name="subject" required 
                                       value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                                       placeholder="Enter message subject">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="6" required 
                                      placeholder="Write your message here..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                            <button type="reset" class="reset-btn">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Contact Information -->
                <section class="contact-info-section">
                    <h2><i class="fas fa-map-marker-alt"></i> Contact Information</h2>
                    
                    <div class="contact-cards">
                        <div class="contact-card">
                            <div class="card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-content">
                                <h3>College Address</h3>
                                <p>Sub Divisional Government Degree College</p>
                                <p>Nauhatta, Rohtas</p>
                                <p>Bihar - 821301</p>
                                <p>India</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="card-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="card-content">
                                <h3>Phone Numbers</h3>
                                <p><strong>Office:</strong> +91-XXXX-XXXXXX</p>
                                <p><strong>Principal:</strong> +91-XXXX-XXXXXX</p>
                                <p><strong>Emergency:</strong> +91-XXXX-XXXXXX</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="card-content">
                                <h3>Email Addresses</h3>
                                <p><strong>Principal:</strong> principal@sdgdcnauhatta.ac.in</p>
                                <p><strong>Office:</strong> office@sdgdcnauhatta.ac.in</p>
                                <p><strong>Admissions:</strong> admissions@sdgdcnauhatta.ac.in</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="map-section">
                        <h3><i class="fas fa-map"></i> Location Map</h3>
                        <div class="map-placeholder">
                            <i class="fas fa-map-marked-alt"></i>
                            <p>Interactive map will be available soon</p>
                            <p>SDGD College is located in Nauhatta, Rohtas district, Bihar</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Quick Links -->
            <section class="quick-links-section">
                <h2><i class="fas fa-link"></i> Quick Links</h2>
                <div class="links-grid">
                    <a href="notices.php" class="link-item">
                        <i class="fas fa-bullhorn"></i>
                        <h3>Latest Notices</h3>
                        <p>View all college notices and announcements</p>
                    </a>
                    
                    <a href="tender.php" class="link-item">
                        <i class="fas fa-file-contract"></i>
                        <h3>Tender Notices</h3>
                        <p>Check latest tender opportunities</p>
                    </a>
                    
                    <a href="admin/login.php" class="link-item">
                        <i class="fas fa-user-shield"></i>
                        <h3>Admin Login</h3>
                        <p>Access admin panel for staff</p>
                    </a>
                    
                    </a>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="notices.php">Notices</a></li>
                        <li><a href="tender.php">Tenders</a></li>
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
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .contact-form-section,
        .contact-info-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .contact-form-section h2,
        .contact-info-section h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #155724;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #721c24;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .contact-form {
            margin: 0;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .submit-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background: #2980b9;
        }
        
        .reset-btn {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .reset-btn:hover {
            background: #7f8c8d;
        }
        
        .contact-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .contact-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        
        .card-icon {
            flex-shrink: 0;
        }
        
        .card-icon i {
            font-size: 32px;
            color: #27ae60;
        }
        
        .card-content {
            flex: 1;
        }
        
        .card-content h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .card-content p {
            color: #7f8c8d;
            margin: 5px 0;
            line-height: 1.5;
        }
        
        .card-content strong {
            color: #2c3e50;
        }
        
        .map-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        
        .map-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .map-section i {
            color: #3498db;
        }
        
        .map-placeholder {
            padding: 40px;
            background: #ecf0f1;
            border-radius: 10px;
            border: 2px dashed #bdc3c7;
        }
        
        .map-placeholder i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 15px;
        }
        
        .quick-links-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .quick-links-section h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .link-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            text-decoration: none;
            color: #2c3e50;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid #f39c12;
        }
        
        .link-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .link-item i {
            font-size: 32px;
            color: #f39c12;
            margin-bottom: 15px;
            display: block;
            text-align: center;
        }
        
        .link-item h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        
        .link-item p {
            color: #7f8c8d;
            margin: 0;
            line-height: 1.4;
        }
        
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .contact-cards,
            .links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
