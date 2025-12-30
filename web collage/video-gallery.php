<?php
require_once 'config.php';

// Get videos from database
$query = "SELECT * FROM video_gallery WHERE status = 'active' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$videos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get unique categories for filter
$categories = [];
$cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM video_gallery WHERE category != '' ORDER BY category");
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Gallery - SDGD College</title>
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
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="media.php" class="active"><i class="fas fa-video"></i> MEDIA</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-video"></i> Video Gallery</h1>
            <p>Educational videos, events, and activities from SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search videos..." 
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
                        <a href="video-gallery.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Video Grid -->
            <div class="video-grid">
                <?php if (count($videos) > 0): ?>
                    <?php foreach ($videos as $video): ?>
                        <div class="video-item">
                            <div class="video-container">
                                <?php if ($video['thumbnail']): ?>
                                    <div class="video-thumbnail">
                                        <img src="<?php echo $video['thumbnail']; ?>" 
                                             alt="<?php echo $video['title']; ?>" 
                                             class="video-thumb">
                                        <div class="play-overlay">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="video-placeholder">
                                        <i class="fas fa-video"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="video-info">
                                    <h4><?php echo $video['title']; ?></h4>
                                    <span class="video-category"><?php echo ucfirst($video['category']); ?></span>
                                    <span class="video-duration">
                                        <i class="fas fa-clock"></i>
                                        <?php echo $video['duration']; ?>
                                    </span>
                                    <span class="video-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo format_date($video['created_at']); ?>
                                    </span>
                                    </div>
                                </div>
                                
                                <div class="video-description">
                                    <p><?php echo substr($video['description'], 0, 150); ?></p>
                                </div>
                                
                                <div class="video-actions">
                                    <a href="<?php echo $video['video_url']; ?>" target="_blank" class="watch-btn">
                                        <i class="fas fa-play"></i> Watch Now
                                    </a>
                                    <a href="<?php echo $video['video_url']; ?>" target="_blank" class="download-btn">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-videos">
                        <i class="fas fa-video"></i>
                        <h3>No Videos Available</h3>
                        <p>There are currently no videos in the gallery.</p>
                        <p>Please check back later for new updates.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Load More Button -->
            <?php if (count($videos) >= 12): ?>
                <div class="load-more">
                    <button class="load-more-btn" onclick="loadMoreVideos()">
                        <i class="fas fa-plus"></i> Load More Videos
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

    <script>
        function loadMoreVideos() {
            // This would typically load more videos via AJAX
            // For demo purposes, we'll show a message
            alert('More videos would be loaded here in a real implementation with AJAX.');
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
        
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .video-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .video-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .video-container {
            position: relative;
        }
        
        .video-thumbnail {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .video-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }
        
        .video-item:hover .play-overlay {
            background: rgba(0,0,0,0.9);
        }
        
        .video-placeholder {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            border-radius: 10px;
            border: 2px dashed #bdc3c7;
        }
        
        .video-placeholder i {
            font-size: 48px;
            color: #bdc3c7;
        }
        
        .video-info {
            padding: 20px;
        }
        
        .video-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #2c3e50;
        }
        
        .video-category {
            background: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .video-duration,
        .video-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            opacity: 0.9;
        }
        
        .video-description {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .video-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .watch-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .watch-btn:hover {
            background: #c0392b;
        }
        
        .download-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .download-btn:hover {
            background: #2980b9;
        }
        
        .no-videos {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-videos i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .no-videos h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .no-videos p {
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
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .video-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
