<?php
require_once 'config.php';

// Get contractual staff from database
$query = "SELECT * FROM teachers WHERE status = 'active' AND department = 'contractual' ORDER BY department, full_name";
$result = mysqli_query($conn, $query);
$contractual_staff = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractual Staff - SDGD College</title>
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
                <li><a href="teaching-staff.php" class="active"><i class="fas fa-chalkboard-teacher"></i> TEACHING STAFF</a></li>
                    <li><a href="non-teaching-staff.php" class=""><i class="fas fa-users"></i> NON-TEACHING STAFF</a></li>
                    <li><a href="contractual-staff.php" class=""><i class="fas fa-briefcase"></i> CONTRACTUAL STAFF</a></li>
                    <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-briefcase"></i> Contractual Staff</h1>
            <p>Contractual and visiting faculty who enrich our academic programs</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Contractual Staff Section -->
            <section class="staff-section">
                <h2><i class="fas fa-briefcase"></i> Visiting Faculty</h2>
                <div class="staff-grid">
                    <?php foreach ($contractual_staff as $staff): ?>
                        <div class="staff-card">
                            <div class="staff-photo">
                                <img src="<?php echo $staff['profile_image'] ?: 'assets/staff/default-avatar.jpg'; ?>" 
                                     alt="<?php echo $staff['full_name']; ?>" 
                                     class="staff-image">
                            </div>
                            <div class="staff-info">
                                <h4><?php echo $staff['full_name']; ?></h4>
                                <p class="designation"><?php echo $staff['designation']; ?></p>
                                <p class="department"><?php echo $staff['department']; ?></p>
                                <p class="expertise"><?php echo $staff['qualification']; ?></p>
                                <p><strong>Visiting Period:</strong> <?php echo $staff['visiting_period']; ?></p>
                                <p><strong>Areas of Expertise:</strong> <?php echo $staff['areas_of_expertise']; ?></p>
                                <div class="contact-info">
                                    <p><i class="fas fa-envelope"></i> <?php echo $staff['email']; ?></p>
                                    <p><i class="fas fa-phone"></i> <?php echo $staff['phone']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Staff Statistics -->
            <section class="stats-section">
                <h2><i class="fas fa-chart-bar"></i> Contractual Staff Statistics</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($contractual_staff); ?></h3>
                            <p>Contractual Staff</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($contractual_staff); ?></h3>
                            <p>Regular Visiting Faculty</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($contractual_staff); ?></h3>
                            <p>Contractual Staff</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($contractual_staff); ?></h3>
                            <p>Total Staff</p>
                        </div>
                    </div>
                </div>
                
                <div class="dept-stats">
                    <h3><i class="fas fa-building"></i> Department-wise Distribution</h3>
                    <div class="dept-stats">
                        <?php
                        $dept_counts = [];
                        foreach ($contractual_staff as $staff) {
                            $dept = $staff['department'];
                            if (!isset($dept_counts[$dept])) {
                                $dept_counts[$dept] = 0;
                            }
                            $dept_counts[$dept]++;
                        }
                        ?>
                        
                        <?php foreach ($dept_counts as $dept => $count): ?>
                            <div class="dept-stat">
                                <h5><?php echo $dept; ?></h5>
                                        <span class="dept-count"><?php echo $count; ?></span>
                                    </div>
                            </div>
                        <?php endforeach; ?>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="teaching-staff.php">Teaching Staff</a></li>
                        <li><a href="non-teaching-staff.php">Non-Teaching Staff</a></li>
                        <li><a href="contractual-staff.php">Contractual Staff</a></li>
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

    <script>
        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.staff-section');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
        }
    </script>

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
        
        .staff-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .staff-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .staff-section h2 i {
            font-size: 20px;
            color: #3498db;
            margin-right: 10px;
        }
        
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .staff-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .staff-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .staff-photo {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .staff-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .staff-info {
            padding: 20px;
        }
        
        .staff-info h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 18px;
        }
        
        .staff-info p {
            color: #5a6c7d;
            margin: 5px 0;
            line-height: 1.6;
        }
        
        .staff-info strong {
            color: #2c3e50;
        }
        
        .contact-info {
            margin-top: 10px;
        }
        
        .contact-info p {
            margin: 2px 0;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .stats-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .stats-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-icon {
            font-size: 32px;
            color: #3498db;
            margin-bottom: 10px;
        }
        
        .stat-info {
            color: #2c3e50;
        }
        
        .stat-info h3 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .dept-stats {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .dept-stat {
            background: #3498db;
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        
        .dept-stat h5 {
            margin: 0;
            color: #2c3e50;
            font-size: 14px;
        }
        
        .dept-count {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 18px;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .dept-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
