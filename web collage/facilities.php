<?php
require_once 'config.php';

// Get facilities from database
$query = "SELECT * FROM facilities WHERE status = 'active' ORDER BY category, facility_name";
$result = mysqli_query($conn, $query);
$facilities = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get unique categories for filter
$categories = [];
$cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM facilities WHERE category != '' ORDER BY category");
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities - SDGD College</title>
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
                    <a href="student-portal.php" class="student-link"><i class="fas fa-user-graduate"></i> STUDENT PORTAL</a>
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
                <li><a href="facilities.php" class="active"><i class="fas fa-building"></i> FACILITIES</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-building"></i> College Facilities</h1>
            <p>Explore our modern infrastructure and learning resources</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search facilities..." 
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
                        <a href="facilities.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Facilities Grid -->
            <div class="facilities-grid">
                <?php if (count($facilities) > 0): ?>
                    <?php foreach ($facilities as $facility): ?>
                        <div class="facility-card">
                            <div class="facility-image">
                                <img src="<?php echo $facility['image_path']; ?>" 
                                     alt="<?php echo $facility['facility_name']; ?>" 
                                     class="facility-img">
                                <div class="facility-overlay">
                                    <span class="facility-category"><?php echo ucfirst($facility['category']); ?></span>
                                </div>
                            </div>
                            <div class="facility-content">
                                <h4><?php echo $facility['facility_name']; ?></h4>
                                <p class="facility-description"><?php echo substr($facility['description'], 0, 150); ?>...</p>
                                <div class="facility-features">
                                    <?php if ($facility['capacity']): ?>
                                        <span class="feature-item">
                                            <i class="fas fa-users"></i>
                                            Capacity: <?php echo $facility['capacity']; ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($facility['equipment']): ?>
                                        <span class="feature-item">
                                            <i class="fas fa-tools"></i>
                                            <?php echo $facility['equipment']; ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($facility['availability']): ?>
                                        <span class="feature-item">
                                            <i class="fas fa-clock"></i>
                                            <?php echo $facility['availability']; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="facility-actions">
                                    <a href="facilities.php?action=view&id=<?php echo $facility['id']; ?>" class="view-btn">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-facilities">
                        <i class="fas fa-building"></i>
                        <h3>No Facilities Available</h3>
                        <p>No facilities information is currently available.</p>
                        <p>Please check back later for updates.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Facility Categories -->
            <div class="facility-categories">
                <h2><i class="fas fa-th-large"></i> Facility Categories</h2>
                <div class="categories-grid">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="category-content">
                            <h4>Academic Facilities</h4>
                            <p>Classrooms, laboratories, library, and learning resources</p>
                        </div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="category-content">
                            <h4>Sports & Recreation</h4>
                            <p>Playgrounds, sports equipment, and recreational facilities</p>
                        </div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="category-content">
                            <h4>Hostel & Accommodation</h4>
                            <p>Student hostels, mess facilities, and accommodation</p>
                        </div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="category-content">
                            <h4>Dining & Services</h4>
                            <p>Canteen, cafeteria, and other campus services</p>
                        </div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <div class="category-content">
                            <h4>IT & Infrastructure</h4>
                            <p>Computer labs, internet, and campus infrastructure</p>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="library.php">Library</a></li>
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
        function loadMoreFacilities() {
            // This would typically load more facilities via AJAX
            alert('More facilities would be loaded here in a real implementation with AJAX.');
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
        
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .facility-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .facility-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .facility-image {
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
            height: 200px;
        }
        
        .facility-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .facility-card:hover .facility-img {
            transform: scale(1.05);
        }
        
        .facility-overlay {
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
        
        .facility-content {
            padding: 20px;
        }
        
        .facility-content h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .facility-description {
            color: #5a6c7d;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .facility-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .feature-item {
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .facility-actions {
            text-align: center;
            margin-top: 15px;
        }
        
        .view-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .view-btn:hover {
            background: #2980b9;
        }
        
        .facility-categories {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .facility-categories h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 10px;
        }
        
        .facility-categories h2 i {
            font-size: 20px;
            color: #27ae60;
            margin-right: 10px;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .category-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .category-icon {
            font-size: 32px;
            color: #27ae60;
            margin-bottom: 15px;
        }
        
        .category-content h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .category-content p {
            color: #5a6c7d;
            margin: 0;
            line-height: 1.5;
        }
        
        .no-facilities {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-facilities h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
        }
        
        .no-facilities p {
            color: #7f8c8d;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .facilities-grid,
            .categories-grid {
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
