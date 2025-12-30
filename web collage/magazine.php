<?php
require_once 'config.php';

// Get magazine issues from database
$query = "SELECT * FROM college_magazine WHERE status = 'active' ORDER BY published_date DESC";
$result = mysqli_query($conn, $query);
$magazines = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get unique volumes for filter
$volumes = [];
$vol_result = mysqli_query($conn, "SELECT DISTINCT volume FROM college_magazine WHERE volume != '' ORDER BY volume DESC");
while ($row = mysqli_fetch_assoc($vol_result)) {
    $volumes[] = $row['volume'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Magazine - SDGD College</title>
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
                <li><a href="newspaper.php"><i class="fas fa-newspaper"></i> NEWSPAPER</a></li>
                <li><a href="magazine.php" class="active"><i class="fas fa-book"></i> MAGAZINE</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-book"></i> College Magazine</h1>
            <p>Digital publications showcasing student achievements and campus life</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search magazines..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        
                        <select name="volume">
                            <option value="">All Volumes</option>
                            <?php foreach ($volumes as $volume): ?>
                                <option value="<?php echo $volume; ?>" <?php echo ($_GET['volume'] ?? '') === $volume ? 'selected' : ''; ?>>
                                    Volume <?php echo $volume; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select name="year">
                            <option value="">All Years</option>
                            <?php
                            $years = range(date('Y'), date('Y') - 5);
                            foreach ($years as $year) {
                                echo "<option value='$year'" . (($_GET['year'] ?? '') === $year ? 'selected' : '') . ">$year</option>";
                            }
                            ?>
                        </select>
                        
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="magazine.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Featured Magazine -->
            <?php if (count($magazines) > 0): ?>
                <?php $featured = $magazines[0]; ?>
                <div class="featured-magazine">
                    <div class="magazine-cover">
                        <img src="<?php echo $featured['cover_image']; ?>" 
                             alt="<?php echo $featured['title']; ?>" 
                             class="magazine-cover-img">
                        <div class="magazine-overlay">
                            <span class="magazine-volume">Volume <?php echo $featured['volume']; ?></span>
                            <span class="magazine-date"><?php echo format_date($featured['published_date']); ?></span>
                        </div>
                    </div>
                    <div class="magazine-content">
                        <h2><i class="fas fa-star"></i> Featured Issue</h2>
                        <h3><?php echo $featured['title']; ?></h3>
                        <p class="magazine-excerpt"><?php echo substr($featured['description'], 0, 200); ?>...</p>
                        <div class="magazine-meta">
                            <p><i class="fas fa-calendar"></i> Published: <?php echo format_date($featured['published_date']); ?></p>
                            <p><i class="fas fa-file-pdf"></i> Pages: <?php echo $featured['pages']; ?></p>
                            <p><i class="fas fa-eye"></i> Views: <?php echo $featured['views']; ?></p>
                        </div>
                        <div class="magazine-actions">
                            <a href="magazine.php?action=view&id=<?php echo $featured['id']; ?>" class="read-btn">
                                <i class="fas fa-book-open"></i> Read Online
                            </a>
                            <a href="<?php echo $featured['pdf_path']; ?>" class="download-btn" target="_blank">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Magazines Grid -->
            <div class="magazines-section">
                <h2><i class="fas fa-book"></i> Magazine Issues</h2>
                <div class="magazines-grid">
                    <?php if (count($magazines) > 0): ?>
                        <?php foreach ($magazines as $magazine): ?>
                            <div class="magazine-card">
                                <div class="magazine-cover">
                                    <img src="<?php echo $magazine['cover_image']; ?>" 
                                         alt="<?php echo $magazine['title']; ?>" 
                                         class="magazine-cover-img">
                                    <div class="magazine-overlay">
                                        <span class="magazine-volume">Volume <?php echo $magazine['volume']; ?></span>
                                    </div>
                                </div>
                                <div class="magazine-content">
                                    <h4><?php echo $magazine['title']; ?></h4>
                                    <p class="magazine-excerpt"><?php echo substr($magazine['description'], 0, 150); ?>...</p>
                                    <div class="magazine-meta">
                                        <span class="magazine-date">
                                            <i class="fas fa-calendar"></i> <?php echo format_date($magazine['published_date']); ?>
                                        </span>
                                        <span class="magazine-pages">
                                            <i class="fas fa-file-pdf"></i> <?php echo $magazine['pages']; ?> pages
                                        </span>
                                    </div>
                                    <div class="magazine-actions">
                                        <a href="magazine.php?action=view&id=<?php echo $magazine['id']; ?>" class="view-btn">
                                            <i class="fas fa-book-open"></i> Read
                                        </a>
                                        <a href="<?php echo $magazine['pdf_path']; ?>" class="download-btn" target="_blank">
                                            <i class="fas fa-download"></i> PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-magazines">
                            <i class="fas fa-book"></i>
                            <h3>No Magazines Available</h3>
                            <p>No magazine issues are currently available.</p>
                            <p>Please check back later for new publications.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Magazine Statistics -->
            <div class="magazine-stats">
                <h2><i class="fas fa-chart-bar"></i> Publication Statistics</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($magazines); ?></h3>
                            <p>Total Issues</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($magazines); ?></h3>
                            <p>Publications</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-info">
                            <h3>
                                <?php
                                $total_views = array_sum(array_column($magazines, 'views'));
                                echo number_format($total_views);
                                ?>
                            </h3>
                            <p>Total Views</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-info">
                            <h3>
                                <?php
                                $total_downloads = array_sum(array_column($magazines, 'downloads'));
                                echo number_format($total_downloads);
                                ?>
                            </h3>
                            <p>Total Downloads</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <?php if (count($magazines) >= 12): ?>
                <div class="load-more">
                    <button class="load-more-btn" onclick="loadMoreMagazines()">
                        <i class="fas fa-plus"></i> Load More Issues
                    </button>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="newspaper.php">College Newspaper</a></li>
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
        function loadMoreMagazines() {
            // This would typically load more magazines via AJAX
            alert('More magazines would be loaded here in a real implementation with AJAX.');
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
        
        .featured-magazine {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .magazine-cover {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 300px;
        }
        
        .magazine-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .featured-magazine:hover .magazine-cover-img {
            transform: scale(1.05);
        }
        
        .magazine-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .magazine-volume {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .magazine-date {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .magazine-content {
            padding: 20px;
        }
        
        .magazine-content h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 20px;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 10px;
        }
        
        .magazine-content h2 i {
            font-size: 18px;
            color: #f39c12;
            margin-right: 10px;
        }
        
        .magazine-content h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .magazine-excerpt {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .magazine-meta {
            display: flex;
            gap: 20px;
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .magazine-meta p {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .magazine-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .read-btn,
        .download-btn {
            background: #f39c12;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .read-btn:hover,
        .download-btn:hover {
            background: #e67e22;
        }
        
        .magazines-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .magazines-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .magazines-section h2 i {
            font-size: 20px;
            color: #3498db;
            margin-right: 10px;
        }
        
        .magazines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .magazine-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .magazine-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .magazine-cover {
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
            height: 200px;
        }
        
        .magazine-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .magazine-card:hover .magazine-cover-img {
            transform: scale(1.05);
        }
        
        .magazine-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .magazine-content {
            padding: 20px;
        }
        
        .magazine-content h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .magazine-excerpt {
            color: #5a6c7d;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .magazine-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .magazine-date,
        .magazine-pages {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .magazine-actions {
            display: flex;
            gap: 10px;
        }
        
        .view-btn,
        .download-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .view-btn:hover,
        .download-btn:hover {
            background: #2980b9;
        }
        
        .magazine-stats {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .magazine-stats h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 10px;
        }
        
        .magazine-stats h2 i {
            font-size: 20px;
            color: #27ae60;
            margin-right: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
            text-align: center;
        }
        
        .stat-icon {
            font-size: 32px;
            color: #27ae60;
            margin-bottom: 15px;
        }
        
        .stat-info {
            color: #2c3e50;
        }
        
        .stat-info h3 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .no-magazines {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-magazines h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .no-magazines p {
            color: #7f8c8d;
            margin: 0;
        }
        
        .load-more {
            text-align: center;
            margin-top: 40px;
        }
        
        .load-more-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .load-more-btn:hover {
            background: #2980b9;
        }
        
        @media (max-width: 768px) {
            .featured-magazine {
                grid-template-columns: 1fr;
            }
            
            .magazines-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</body>
</html>
