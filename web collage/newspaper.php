<?php
require_once 'config.php';

// Check if news_articles table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'news_articles'");
$articles = [];
$categories = [];

if (mysqli_num_rows($table_check) > 0) {
    // Get news articles from database
    $query = "SELECT * FROM news_articles WHERE status = 'active' ORDER BY published_date DESC";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Get unique categories for filter
    $cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM news_articles WHERE category != '' ORDER BY category");
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row['category'];
    }
} else {
    // Create sample data if table doesn't exist
    $articles = [
        [
            'id' => 1,
            'title' => 'Annual Sports Meet 2024',
            'content' => 'SDGD College is organizing its annual sports meet on February 15th, 2024. Students from all departments are encouraged to participate in various sports events including athletics, cricket, football, and indoor games.',
            'category' => 'Sports',
            'author' => 'Sports Department',
            'published_date' => '2024-01-15',
            'image_path' => 'assets/news/sports-meet.jpg',
            'views' => 156
        ],
        [
            'id' => 2,
            'title' => 'Science Exhibition Success',
            'content' => 'Our students showcased innovative projects at the inter-college science exhibition. The college won several awards for outstanding research presentations in physics, chemistry, and computer science.',
            'category' => 'Academics',
            'author' => 'Science Department',
            'published_date' => '2024-01-20',
            'image_path' => 'assets/news/science-expo.jpg',
            'views' => 203
        ],
        [
            'id' => 3,
            'title' => 'Cultural Festival - "Utsav 2024"',
            'content' => 'The annual cultural festival "Utsav 2024" was a grand success with students participating in music, dance, drama, and various cultural competitions. Chief Minister graced the occasion as chief guest.',
            'category' => 'Cultural',
            'author' => 'Cultural Committee',
            'published_date' => '2024-01-25',
            'image_path' => 'assets/news/cultural-fest.jpg',
            'views' => 289
        ],
        [
            'id' => 4,
            'title' => 'New Computer Lab Inaugurated',
            'content' => 'A state-of-the-art computer lab with 50 modern systems has been inaugurated. The lab is equipped with high-speed internet and latest software for programming and multimedia applications.',
            'category' => 'Infrastructure',
            'author' => 'IT Department',
            'published_date' => '2024-02-01',
            'image_path' => 'assets/news/computer-lab.jpg',
            'views' => 167
        ],
        [
            'id' => 5,
            'title' => 'Placement Drive Results',
            'content' => 'Major companies including TCS, Infosys, and Wipro visited our campus for recruitment. Over 45 students received job offers with packages ranging from 3.5 to 8.5 LPA.',
            'category' => 'Placement',
            'author' => 'Placement Cell',
            'published_date' => '2024-02-05',
            'image_path' => 'assets/news/placement-drive.jpg',
            'views' => 342
        ]
    ];
    
    $categories = ['Sports', 'Academics', 'Cultural', 'Infrastructure', 'Placement'];
}

// Handle category filter
$current_category = $_GET['category'] ?? 'all';
if ($current_category !== 'all' && !empty($articles)) {
    $articles = array_filter($articles, function($article) use ($current_category) {
        return $article['category'] === $current_category;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Newspaper - SDGD College</title>
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
                <li><a href="newspaper.php" class="active"><i class="fas fa-newspaper"></i> NEWSPAPER</a></li>
                <li><a href="magazine.php"><i class="fas fa-book"></i> MAGAZINE</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-newspaper"></i> College Newspaper</h1>
            <p>Latest news, events, and achievements from SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search articles..." 
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
                        <a href="newspaper.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Featured Article -->
            <?php if (count($articles) > 0): ?>
                <?php $featured = $articles[0]; ?>
                <div class="featured-article">
                    <div class="featured-image">
                        <img src="<?php echo $featured['image_path']; ?>" 
                             alt="<?php echo $featured['title']; ?>" 
                             class="featured-img">
                        <div class="featured-overlay">
                            <span class="featured-category"><?php echo ucfirst($featured['category']); ?></span>
                            <span class="featured-date"><?php echo format_date($featured['published_date']); ?></span>
                        </div>
                    </div>
                    <div class="featured-content">
                        <h2><i class="fas fa-star"></i> Featured Article</h2>
                        <h3><?php echo $featured['title']; ?></h3>
                        <p class="featured-excerpt"><?php echo substr($featured['content'], 0, 200); ?>...</p>
                        <div class="featured-meta">
                            <p><i class="fas fa-user"></i> <?php echo $featured['author']; ?></p>
                            <p><i class="fas fa-calendar"></i> <?php echo format_date($featured['published_date']); ?></p>
                            <p><i class="fas fa-eye"></i> <?php echo $featured['views']; ?> views</p>
                        </div>
                        <a href="newspaper.php?action=view&id=<?php echo $featured['id']; ?>" class="read-more-btn">
                            Read Full Article <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Articles Grid -->
            <div class="articles-section">
                <h2><i class="fas fa-newspaper"></i> Latest Articles</h2>
                <div class="articles-grid">
                    <?php if (count($articles) > 0): ?>
                        <?php foreach ($articles as $article): ?>
                            <div class="article-card">
                                <div class="article-image">
                                    <img src="<?php echo $article['image_path']; ?>" 
                                         alt="<?php echo $article['title']; ?>" 
                                         class="article-img">
                                    <div class="article-overlay">
                                        <span class="article-category"><?php echo ucfirst($article['category']); ?></span>
                                    </div>
                                </div>
                                <div class="article-content">
                                    <h4><?php echo $article['title']; ?></h4>
                                    <p class="article-excerpt"><?php echo substr($article['content'], 0, 150); ?>...</p>
                                    <div class="article-meta">
                                        <span class="article-date">
                                            <i class="fas fa-calendar"></i> <?php echo format_date($article['published_date']); ?>
                                        </span>
                                        <span class="article-views">
                                            <i class="fas fa-eye"></i> <?php echo $article['views']; ?>
                                        </span>
                                    </div>
                                    <a href="newspaper.php?action=view&id=<?php echo $article['id']; ?>" class="read-more">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-articles">
                            <i class="fas fa-newspaper"></i>
                            <h3>No Articles Available</h3>
                            <p>No articles are currently available.</p>
                            <p>Please check back later for new updates.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Load More Button -->
            <?php if (count($articles) >= 12): ?>
                <div class="load-more">
                    <button class="load-more-btn" onclick="loadMoreArticles()">
                        <i class="fas fa-plus"></i> Load More Articles
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
                        <li><a href="magazine.php">College Magazine</a></li>
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
        function loadMoreArticles() {
            // This would typically load more articles via AJAX
            alert('More articles would be loaded here in a real implementation with AJAX.');
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
        
        .featured-article {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .featured-image {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 300px;
        }
        
        .featured-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .featured-image:hover .featured-img {
            transform: scale(1.05);
        }
        
        .featured-overlay {
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
        
        .featured-category {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .featured-date {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .featured-content {
            padding: 20px;
        }
        
        .featured-content h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 20px;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 10px;
        }
        
        .featured-content h2 i {
            font-size: 18px;
            color: #f39c12;
            margin-right: 10px;
        }
        
        .featured-content h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .featured-excerpt {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .featured-meta {
            display: flex;
            gap: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .featured-meta p {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .read-more-btn {
            background: #f39c12;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .read-more-btn:hover {
            background: #e67e22;
        }
        
        .articles-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .articles-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .articles-section h2 i {
            font-size: 20px;
            color: #3498db;
            margin-right: 10px;
        }
        
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .article-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .article-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .article-image {
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
            height: 200px;
        }
        
        .article-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .article-card:hover .article-img {
            transform: scale(1.05);
        }
        
        .article-overlay {
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
        
        .article-content {
            padding: 20px;
        }
        
        .article-content h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .article-excerpt {
            color: #5a6c7d;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .article-date,
        .article-views {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .read-more {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .read-more:hover {
            color: #2980b9;
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
            .featured-article {
                grid-template-columns: 1fr;
            }
            
            .articles-grid {
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
