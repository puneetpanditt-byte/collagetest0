<?php
require_once 'config.php';

// Get master routine from database
$query = "SELECT * FROM master_routine ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$routines = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

// Get unique categories for filter
$categories = [];
$cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM master_routine WHERE category != '' ORDER BY category");
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row['category'];
}
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Routine - SDGD College</title>
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
        </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php"><i class="fas fa-home"></i> HOME</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="master-routine.php" class="active"><i class="fas fa-calendar-alt"></i> MASTER ROUTINE</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-calendar-alt"></i> Master Routine</h1>
            <p>Academic schedule and timetable for all courses and programs</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search by title, course, or description..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        
                        <select name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category; ?>" <?php echo ($_GET['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                    <?php echo $category; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="master-routine.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Routine Grid -->
            <div class="routine-grid">
                <?php if (count($routines) > 0): ?>
                    <?php foreach ($routines as $routine): ?>
                        <div class="routine-item">
                            <div class="routine-header">
                                <h4><?php echo $routine['title']; ?></h4>
                                <span class="routine-date"><?php echo format_date($routine['created_at']); ?></span>
                                <span class="routine-category"><?php echo ucfirst($routine['category']); ?></span>
                            </div>
                            <div class="routine-details">
                                <p><?php echo substr($routine['description'], 0, 200); ?></p>
                                <div class="routine-meta">
                                    <span><i class="fas fa-calendar"></i> Schedule:</span> <?php echo $routine['start_time']; ?> - <?php echo $routine['end_time']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-routines">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>No Routines Available</h3>
                        <p>No master routines available at this time.</p>
                        <p>Please check back later for new updates.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Load More Button -->
            <?php if (count($routines) >= 12): ?>
                <div class="load-more">
                    <button class="load-more-btn" onclick="loadMoreRoutines()">
                        <i class="fas fa-plus"></i> Load More Routines
                    </button>
                </div>
            <?php endif; ?>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="master-routine.php">Master Routine</a></li>
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
        function loadMoreRoutines() {
            // This would typically load more routines via AJAX
            alert('More routines would be loaded here in a real implementation with AJAX.');
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
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .filter-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            flex: 1;
        }
        
        .filter-row input,
        .filter-row select {
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-btn,
        .clear-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .filter-btn:hover,
        .clear-btn:hover {
            background: #2980b9;
        }
        
        .routine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .routine-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .routine-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .routine-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        
        .routine-header h4 {
            margin: 0 0 10px 0;
            color: white;
            font-size: 16px;
        }
        
        .routine-date {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .routine-meta {
            display: flex;
            gap: 8px;
            font-size: 12px;
            opacity: 0.9;
        }
        
        .routine-details {
            padding: 15px;
            background: rgba(255,255,255,0.9);
        }
        
        .no-routines {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-routines h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .load-more {
            text-align: center;
            margin-top: 40px;
        }
        
        .load-more-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .load-more-btn:hover {
            background: #2980b9;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .routine-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
