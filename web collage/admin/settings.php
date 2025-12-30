<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        // Update settings
        $settings = [
            'site_name' => sanitize_input($_POST['site_name']),
            'site_description' => sanitize_input($_POST['site_description']),
            'contact_email' => sanitize_input($_POST['contact_email']),
            'contact_phone' => sanitize_input($_POST['contact_phone']),
            'address' => sanitize_input($_POST['address']),
            'facebook_url' => sanitize_input($_POST['facebook_url']),
            'twitter_url' => sanitize_input($_POST['twitter_url']),
            'linkedin_url' => sanitize_input($_POST['linkedin_url']),
            'youtube_url' => sanitize_input($_POST['youtube_url'])
        ];
        
        foreach ($settings as $key => $value) {
            $query = "UPDATE settings SET setting_value = '$value', updated_by = " . $_SESSION['admin_id'] . ", updated_at = NOW() WHERE setting_key = '$key'";
            if (!mysqli_query($conn, $query)) {
                $error = "Failed to update $key: " . mysqli_error($conn);
            }
        }
        
        if (empty($error)) {
            log_activity($_SESSION['admin_id'], 'update_settings', 'Updated site settings');
            $message = 'Settings updated successfully!';
        }
    }
}

// Get current settings
$current_settings = [];
$result = mysqli_query($conn, "SELECT setting_key, setting_value FROM settings");
while ($row = mysqli_fetch_assoc($result)) {
    $current_settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SDGD College</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-graduation-cap"></i> SDGD College</h3>
                <p>Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="notices.php"><i class="fas fa-bullhorn"></i> Notices</a></li>
                    <li><a href="tenders.php"><i class="fas fa-file-contract"></i> Tenders</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="teachers.php"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
                    <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Site Settings</h1>
                </div>
                <div class="header-right">
                    <div class="admin-info">
                        <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                        <img src="../assets/admin-avatar.png" alt="Admin" class="admin-avatar" onerror="this.style.display='none'">
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="dashboard-content">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="content-header">
                    <h2>General Settings</h2>
                </div>
                
                <div class="form-container">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="settings-section">
                            <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                            
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input type="text" id="site_name" name="site_name" required 
                                       value="<?php echo $current_settings['site_name'] ?? SITE_NAME; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description">Site Description</label>
                                <textarea id="site_description" name="site_description" rows="4"><?php echo $current_settings['site_description'] ?? ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="settings-section">
                            <h3><i class="fas fa-address-card"></i> Contact Information</h3>
                            
                            <div class="form-group">
                                <label for="contact_email">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" required 
                                       value="<?php echo $current_settings['contact_email'] ?? ADMIN_EMAIL; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_phone">Contact Phone</label>
                                <input type="tel" id="contact_phone" name="contact_phone" 
                                       value="<?php echo $current_settings['contact_phone'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">College Address</label>
                                <textarea id="address" name="address" rows="3"><?php echo $current_settings['address'] ?? ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="settings-section">
                            <h3><i class="fas fa-share-alt"></i> Social Media Links</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="facebook_url">Facebook Page URL</label>
                                    <input type="url" id="facebook_url" name="facebook_url" 
                                           placeholder="https://facebook.com/yourpage"
                                           value="<?php echo $current_settings['facebook_url'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="twitter_url">Twitter Profile URL</label>
                                    <input type="url" id="twitter_url" name="twitter_url" 
                                           placeholder="https://twitter.com/yourprofile"
                                           value="<?php echo $current_settings['twitter_url'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="linkedin_url">LinkedIn Profile URL</label>
                                    <input type="url" id="linkedin_url" name="linkedin_url" 
                                           placeholder="https://linkedin.com/in/yourprofile"
                                           value="<?php echo $current_settings['linkedin_url'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="youtube_url">YouTube Channel URL</label>
                                    <input type="url" id="youtube_url" name="youtube_url" 
                                           placeholder="https://youtube.com/yourchannel"
                                           value="<?php echo $current_settings['youtube_url'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="dashboard.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
                
                <!-- System Information -->
                <div class="content-header">
                    <h2>System Information</h2>
                </div>
                
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="fas fa-server"></i> Server Information</h4>
                        <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
                        <p><strong>MySQL Version:</strong> <?php echo mysqli_get_server_info($conn); ?></p>
                        <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
                        <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
                    </div>
                    
                    <div class="info-card">
                        <h4><i class="fas fa-database"></i> Database Statistics</h4>
                        <?php
                        $stats = [
                            'notices' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM notices"))['count'],
                            'tenders' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tenders"))['count'],
                            'students' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM students"))['count'],
                            'teachers' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM teachers"))['count'],
                            'photos' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM photo_gallery"))['count'],
                            'videos' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM video_gallery"))['count']
                        ];
                        ?>
                        <p><strong>Total Notices:</strong> <?php echo $stats['notices']; ?></p>
                        <p><strong>Total Tenders:</strong> <?php echo $stats['tenders']; ?></p>
                        <p><strong>Total Students:</strong> <?php echo $stats['students']; ?></p>
                        <p><strong>Total Teachers:</strong> <?php echo $stats['teachers']; ?></p>
                        <p><strong>Total Photos:</strong> <?php echo $stats['photos']; ?></p>
                        <p><strong>Total Videos:</strong> <?php echo $stats['videos']; ?></p>
                    </div>
                    
                    <div class="info-card">
                        <h4><i class="fas fa-shield-alt"></i> Security Information</h4>
                        <p><strong>Upload Max Size:</strong> <?php echo ini_get('upload_max_filesize'); ?></p>
                        <p><strong>Post Max Size:</strong> <?php echo ini_get('post_max_size'); ?></p>
                        <p><strong>Memory Limit:</strong> <?php echo ini_get('memory_limit'); ?></p>
                        <p><strong>Max Execution Time:</strong> <?php echo ini_get('max_execution_time'); ?>s</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
    
    <style>
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .content-header h2 {
            margin: 0;
            color: #2c3e50;
        }
        
        .settings-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .settings-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .settings-section h3 i {
            margin-right: 10px;
            color: #3498db;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .info-card h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 16px;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 5px;
        }
        
        .info-card h4 i {
            margin-right: 8px;
            color: #3498db;
        }
        
        .info-card p {
            margin: 8px 0;
            color: #5a6c7d;
        }
        
        .info-card strong {
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .content-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
