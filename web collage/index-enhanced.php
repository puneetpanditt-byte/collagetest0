<?php
require_once 'config.php';

// Get collage content from database
$collage_items = [];
$collage_query = "SELECT * FROM collage_items WHERE status = 'active' ORDER BY created_at DESC LIMIT 12";
$collage_result = mysqli_query($conn, $collage_query);

if ($collage_result) {
    while ($row = mysqli_fetch_assoc($collage_result)) {
        $collage_items[] = $row;
    }
}

// Get categories for filtering
$categories = [];
$cat_query = "SELECT DISTINCT category FROM collage_items WHERE category != '' ORDER BY category";
$cat_result = mysqli_query($conn, $cat_query);
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row['category'];
}

// Dynamic greeting based on time of day
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good Morning! Start your creative journey today.";
    $greeting_icon = "sun";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon! Discover visual inspiration.";
    $greeting_icon = "cloud-sun";
} else {
    $greeting = "Good Evening! Explore moments captured in time.";
    $greeting_icon = "moon";
}

// Hero prompt style based on day of week
$day_of_week = date('l');
$hero_prompts = [
    'Monday' => 'A visual journey through moments frozen in time. Explore our curated collection of light, shadow, and motion.',
    'Tuesday' => 'Creative Visuals & Cinematic Stories. A portfolio of professional photography and high-definition videography.',
    'Wednesday' => 'Where pixels meet passion. Discover the art of visual storytelling through our lens.',
    'Thursday' => 'Moments that matter. A curated collection of life\'s most precious memories.',
    'Friday' => 'Visual poetry in motion. Experience the rhythm of everyday life captured in stunning detail.',
    'Saturday' => 'Weekend adventures await. Explore our latest visual journeys and creative experiments.',
    'Sunday' => 'Reflections in pixels. A peaceful collection of moments that inspire and delight.'
];

$hero_prompt = $hero_prompts[$day_of_week] ?? 'A visual journey through moments frozen in time. Explore our curated collection of light, shadow, and motion.';

// Get statistics
$stats = [
    'items' => count($collage_items),
    'categories' => count($categories),
    'views' => 2500,
    'creators' => 5
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Collage - Visual Stories & Creative Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="top-bar">
            <div class="container">
                <div class="user-pathways">
                    <a href="#" class="pathway-link"><i class="fas fa-camera"></i> Gallery</a>
                    <a href="#" class="pathway-link"><i class="fas fa-video"></i> Videos</a>
                    <a href="#" class="pathway-link"><i class="fas fa-palette"></i> Collections</a>
                </div>
                <div class="top-links">
                    <div class="search-bar">
                        <input type="text" placeholder="Search visual stories..." class="search-input">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <a href="admin/login.php" class="admin-link"><i class="fas fa-user-shield"></i> ADMIN</a>
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <div class="logo-section">
                    <i class="fas fa-camera-retro logo-icon"></i>
                    <div class="college-info">
                        <h1>WEB COLLAGE</h1>
                        <h2>Visual Stories & Creative Moments</h2>
                        <p>A curated collection of photography, videography, and digital art</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> HOME</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">GALLERY <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="photo-gallery.php">Photography</a></li>
                        <li><a href="video-gallery.php">Videography</a></li>
                        <li><a href="digital-art.php">Digital Art</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">COLLECTIONS <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="portraits.php">Portraits</a></li>
                        <li><a href="landscapes.php">Landscapes</a></li>
                        <li><a href="street.php">Street Photography</a></li>
                        <li><a href="abstract.php">Abstract</a></li>
                    </ul>
                </li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> CONTACT</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background">
            <img src="assets/collage-hero.jpg" alt="Visual Collage Background" loading="lazy">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-text">
                    <div class="greeting">
                        <i class="fas fa-<?php echo $greeting_icon; ?>"></i>
                        <span><?php echo $greeting; ?></span>
                    </div>
                    <h1 class="hero-prompt"><?php echo $hero_prompt; ?></h1>
                    <p>Discover moments frozen in time, stories told through pixels, and emotions captured in frames.</p>
                    <div class="hero-cta">
                        <a href="#collage-gallery" class="cta-primary">
                            <i class="fas fa-images"></i> Explore Gallery
                        </a>
                        <a href="admin/upload.php" class="cta-secondary">
                            <i class="fas fa-plus"></i> Add Your Story
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-chart-bar"></i> Our Visual Journey</h2>
                <p>Numbers that tell our creative story</p>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['items']); ?>+</h3>
                        <p>Visual Items</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['categories']; ?>+</h3>
                        <p>Collections</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['views']); ?>+</h3>
                        <p>Total Views</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['creators']; ?>+</h3>
                        <p>Contributors</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-header">
                <h3><i class="fas fa-filter"></i> Explore by Category</h3>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-category="all">All Items</button>
                    <?php foreach ($categories as $category): ?>
                        <button class="filter-btn" data-category="<?php echo strtolower($category); ?>">
                            <?php echo ucfirst($category); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Collage Gallery Section -->
    <section id="collage-gallery" class="collage-gallery">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-th"></i> Visual Stories Gallery</h2>
                <p>Discover moments captured through different lenses and perspectives</p>
            </div>
            
            <div class="collage-grid">
                <?php if (count($collage_items) > 0): ?>
                    <?php foreach ($collage_items as $item): ?>
                        <div class="collage-item" data-category="<?php echo strtolower($item['category'] ?? 'general'); ?>">
                            <div class="item-media">
                                <?php if ($item['type'] === 'video'): ?>
                                    <video class="item-video" controls poster="uploads/<?php echo $item['thumbnail']; ?>">
                                        <source src="uploads/<?php echo $item['file_path']; ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="play-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                <?php else: ?>
                                    <img src="uploads/<?php echo $item['file_path']; ?>" 
                                         alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                         class="item-image"
                                         loading="lazy">
                                    <div class="image-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="item-content">
                                <div class="item-category">
                                    <span class="category-badge"><?php echo ucfirst($item['category'] ?? 'General'); ?></span>
                                    <?php if ($item['type'] === 'video'): ?>
                                        <span class="type-badge video"><i class="fas fa-video"></i> Video</span>
                                    <?php else: ?>
                                        <span class="type-badge photo"><i class="fas fa-camera"></i> Photo</span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                
                                <div class="item-prompt">
                                    <i class="fas fa-quote-left"></i>
                                    <p><?php echo htmlspecialchars($item['caption_text'] ?? 'A moment captured in time.'); ?></p>
                                </div>
                                
                                <div class="item-meta">
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                                    <span><i class="fas fa-eye"></i> <?php echo number_format($item['views'] ?? 0); ?> views</span>
                                </div>
                                
                                <div class="item-actions">
                                    <button class="btn-view" onclick="viewItem(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                    <button class="btn-like" onclick="likeItem(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-heart"></i> <span class="like-count"><?php echo $item['likes'] ?? 0; ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-items">
                        <i class="fas fa-camera-retro"></i>
                        <h3>No Visual Stories Yet</h3>
                        <p>Start your creative journey by adding your first photo or video to the collection.</p>
                        <a href="admin/upload.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Item
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($collage_items) > 0): ?>
                <div class="load-more">
                    <button class="btn btn-secondary" onclick="loadMoreItems()">
                        <i class="fas fa-plus-circle"></i> Load More Stories
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Collections -->
    <section class="featured-collections">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-star"></i> Featured Collections</h2>
                <p>Curated visual stories from our creative community</p>
            </div>
            
            <div class="collections-grid">
                <div class="collection-card">
                    <div class="collection-image">
                        <img src="assets/collection-portraits.jpg" alt="Portrait Collection" loading="lazy">
                        <div class="collection-overlay">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="collection-content">
                        <h3>Portrait Stories</h3>
                        <p class="collection-prompt">Capturing the soul behind the eyes. A study in expression and personality.</p>
                        <div class="collection-stats">
                            <span><i class="fas fa-images"></i> 24 Items</span>
                            <span><i class="fas fa-eye"></i> 1.2k Views</span>
                        </div>
                        <a href="portraits.php" class="collection-link">
                            <i class="fas fa-arrow-right"></i> Explore Collection
                        </a>
                    </div>
                </div>
                
                <div class="collection-card">
                    <div class="collection-image">
                        <img src="assets/collection-landscapes.jpg" alt="Landscape Collection" loading="lazy">
                        <div class="collection-overlay">
                            <i class="fas fa-mountain"></i>
                        </div>
                    </div>
                    <div class="collection-content">
                        <h3>Landscapes</h3>
                        <p class="collection-prompt">Where the earth meets the sky. A panoramic view of nature's beauty.</p>
                        <div class="collection-stats">
                            <span><i class="fas fa-images"></i> 18 Items</span>
                            <span><i class="fas fa-eye"></i> 856 Views</span>
                        </div>
                        <a href="landscapes.php" class="collection-link">
                            <i class="fas fa-arrow-right"></i> Explore Collection
                        </a>
                    </div>
                </div>
                
                <div class="collection-card">
                    <div class="collection-image">
                        <img src="assets/collection-street.jpg" alt="Street Photography Collection" loading="lazy">
                        <div class="collection-overlay">
                            <i class="fas fa-city"></i>
                        </div>
                    </div>
                    <div class="collection-content">
                        <h3>Street Stories</h3>
                        <p class="collection-prompt">Motion in harmony. A short film exploring the rhythm of city life.</p>
                        <div class="collection-stats">
                            <span><i class="fas fa-images"></i> 32 Items</span>
                            <span><i class="fas fa-eye"></i> 2.1k Views</span>
                        </div>
                        <a href="street.php" class="collection-link">
                            <i class="fas fa-arrow-right"></i> Explore Collection
                        </a>
                    </div>
                </div>
                
                <div class="collection-card">
                    <div class="collection-image">
                        <img src="assets/collection-abstract.jpg" alt="Abstract Collection" loading="lazy">
                        <div class="collection-overlay">
                            <i class="fas fa-palette"></i>
                        </div>
                    </div>
                    <div class="collection-content">
                        <h3>Abstract Visions</h3>
                        <p class="collection-prompt">Energy, captured. One second of pure movement and color.</p>
                        <div class="collection-stats">
                            <span><i class="fas fa-images"></i> 15 Items</span>
                            <span><i class="fas fa-eye"></i> 743 Views</span>
                        </div>
                        <a href="abstract.php" class="collection-link">
                            <i class="fas fa-arrow-right"></i> Explore Collection
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Web Collage</h4>
                    <p>A visual journey through moments frozen in time</p>
                    <p><i class="fas fa-envelope"></i> contact@webcollage.com</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="photo-gallery.php">Gallery</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="admin/login.php">Admin</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Our Journey</h4>
                    <div class="social-links">
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="Pinterest"><i class="fab fa-pinterest"></i></a>
                        <a href="#" title="Behance"><i class="fab fa-behance"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Web Collage. All rights reserved. | Crafted with <i class="fas fa-heart"></i> for visual storytellers</p>
            </div>
        </div>
    </footer>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Filter items
                document.querySelectorAll('.collage-item').forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // View item modal
        function viewItem(itemId) {
            // Implement modal view for full-size image/video
            console.log('Viewing item:', itemId);
        }
        
        // Like functionality
        function likeItem(itemId) {
            const likeBtn = event.target.closest('.btn-like');
            const likeCount = likeBtn.querySelector('.like-count');
            const currentLikes = parseInt(likeCount.textContent);
            
            // Toggle like state
            if (likeBtn.classList.contains('liked')) {
                likeBtn.classList.remove('liked');
                likeCount.textContent = currentLikes - 1;
            } else {
                likeBtn.classList.add('liked');
                likeCount.textContent = currentLikes + 1;
            }
            
            // Send to server
            fetch('api/like_item.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({item_id: itemId})
            });
        }
        
        // Load more items
        function loadMoreItems() {
            // Implement infinite scroll or pagination
            console.log('Loading more items...');
        }
        
        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            if (searchTerm.trim()) {
                window.location.href = 'search.php?q=' + encodeURIComponent(searchTerm);
            }
        });
        
        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                if (searchTerm.trim()) {
                    window.location.href = 'search.php?q=' + encodeURIComponent(searchTerm);
                }
            }
        });
    </script>
    
    <style>
        /* Web Collage Specific Styles */
        .logo-icon {
            font-size: 48px;
            color: #3498db;
            margin-right: 20px;
        }
        
        .hero-prompt {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin: 20px 0;
            line-height: 1.3;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .filter-section {
            padding: 40px 0;
            background: #f8f9fa;
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #ddd;
            background: #fff;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .filter-btn.active,
        .filter-btn:hover {
            background: #3498db;
            color: #fff;
            border-color: #3498db;
        }
        
        .collage-gallery {
            padding: 60px 0;
        }
        
        .collage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .collage-item {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .collage-item:hover {
            transform: translateY(-10px);
        }
        
        .item-media {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .item-image,
        .item-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-overlay,
        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .collage-item:hover .image-overlay,
        .collage-item:hover .play-overlay {
            opacity: 1;
        }
        
        .item-content {
            padding: 20px;
        }
        
        .item-category {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .category-badge {
            background: #3498db;
            color: #fff;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .type-badge {
            background: #28a745;
            color: #fff;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .item-title {
            color: #2c3e50;
            margin-bottom: 12px;
            font-size: 18px;
        }
        
        .item-prompt {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f39c12;
            margin-bottom: 15px;
        }
        
        .item-prompt i {
            color: #f39c12;
            margin-right: 8px;
        }
        
        .item-prompt p {
            margin: 0;
            font-style: italic;
            color: #666;
            line-height: 1.5;
        }
        
        .item-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }
        
        .item-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-view,
        .btn-like {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .btn-view {
            background: #3498db;
            color: #fff;
        }
        
        .btn-like {
            background: #e74c3c;
            color: #fff;
        }
        
        .btn-like.liked {
            background: #c0392b;
        }
        
        .btn-view:hover,
        .btn-like:hover {
            opacity: 0.8;
        }
        
        .featured-collections {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .collections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .collection-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .collection-card:hover {
            transform: translateY(-8px);
        }
        
        .collection-image {
            position: relative;
            height: 180px;
            overflow: hidden;
        }
        
        .collection-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .collection-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(52, 152, 219, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
        }
        
        .collection-content {
            padding: 20px;
        }
        
        .collection-content h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .collection-prompt {
            color: #666;
            font-style: italic;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .collection-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }
        
        .collection-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        
        .collection-link:hover {
            color: #2980b9;
        }
        
        .no-items {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-items i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .load-more {
            text-align: center;
            margin-top: 40px;
        }
        
        @media (max-width: 768px) {
            .collage-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .collections-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .filter-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .filter-buttons {
                justify-content: center;
            }
        }
    </style>
</body>
</html>
