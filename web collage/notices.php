<?php
require_once 'config.php';

// Get notices with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search = $_GET['search'] ?? '';
$type = $_GET['type'] ?? '';

// Build query
$query = "SELECT * FROM notices WHERE status = 'active'";
$count_query = "SELECT COUNT(*) as total FROM notices WHERE status = 'active'";

if ($search) {
    $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
    $count_query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}

if ($type) {
    $query .= " AND notice_type = '$type'";
    $count_query .= " AND notice_type = '$type'";
}

$query .= " ORDER BY publish_date DESC LIMIT $offset, $per_page";

$result = mysqli_query($conn, $query);
$notices = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get total count for pagination
$count_result = mysqli_query($conn, $count_query);
$total_notices = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_notices / $per_page);

// Get unique notice types for filter
$types = [];
$type_result = mysqli_query($conn, "SELECT DISTINCT notice_type FROM notices WHERE notice_type != '' ORDER BY notice_type");
while ($row = mysqli_fetch_assoc($type_result)) {
    $types[] = $row['notice_type'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices - SDGD College</title>
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
                <li><a href="index.php" class=""><i class="fas fa-home"></i> HOME</a></li>
                <li><a href="notices.php" class="active"><i class="fas fa-bullhorn"></i> NOTICES</a></li>
                <li><a href="tender.php"><i class="fas fa-file-contract"></i> TENDER</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-bullhorn"></i> College Notices</h1>
            <p>Stay updated with the latest announcements and notices from SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Search and Filter -->
            <div class="search-section">
                <form method="GET" class="search-form">
                    <div class="search-row">
                        <input type="text" name="search" placeholder="Search notices..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        
                        <select name="type">
                            <option value="">All Types</option>
                            <?php foreach ($types as $notice_type): ?>
                                <option value="<?php echo $notice_type; ?>" <?php echo $type === $notice_type ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($notice_type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notices List -->
            <div class="notices-list-page">
                <?php if (count($notices) > 0): ?>
                    <?php foreach ($notices as $notice): ?>
                        <div class="notice-card">
                            <div class="notice-header">
                                <h3><?php echo $notice['title']; ?></h3>
                                <div class="notice-meta">
                                    <span class="notice-type"><?php echo ucfirst($notice['notice_type']); ?></span>
                                    <span class="priority priority-<?php echo $notice['priority']; ?>">
                                        <?php echo ucfirst($notice['priority']); ?>
                                    </span>
                                    <span class="notice-date">
                                        <i class="fas fa-calendar"></i> <?php echo format_date($notice['publish_date']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if ($notice['description']): ?>
                                <div class="notice-description">
                                    <?php echo nl2br($notice['description']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($notice['file_path']): ?>
                                <div class="notice-attachment">
                                    <a href="<?php echo $notice['file_path']; ?>" target="_blank" class="attachment-btn">
                                        <i class="fas fa-download"></i> Download Attachment
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="notice-footer">
                                <span class="target-audience">
                                    <i class="fas fa-users"></i> For: <?php echo ucfirst($notice['target_audience']); ?>
                                </span>
                                <?php if ($notice['expiry_date'] && $notice['expiry_date'] >= date('Y-m-d')): ?>
                                    <span class="expiry-info">
                                        <i class="fas fa-clock"></i> Expires: <?php echo format_date($notice['expiry_date']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-notices">
                        <i class="fas fa-inbox"></i>
                        <h3>No Notices Found</h3>
                        <p><?php echo $search ? 'No notices found matching your search criteria.' : 'No notices available at this time.'; ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>" class="page-link">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>
                    
                    <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>" class="page-link">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
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
                        <li><a href="about.php">About College</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
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
        // Search form enhancement
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput.value.trim() === '') {
                searchInput.name = ''; // Remove empty search from URL
            }
        });
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
        
        .search-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .search-form {
            margin: 0;
        }
        
        .search-row {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-row input,
        .search-row select {
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .search-row input {
            flex: 1;
        }
        
        .search-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .search-btn:hover {
            background: #2980b9;
        }
        
        .notices-list-page {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .notice-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .notice-card:hover {
            transform: translateY(-5px);
        }
        
        .notice-header {
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .notice-header h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 18px;
        }
        
        .notice-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .notice-type {
            background: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .priority {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .priority-high { background: #e74c3c; color: white; }
        .priority-medium { background: #f39c12; color: white; }
        .priority-low { background: #27ae60; color: white; }
        
        .notice-date {
            color: #7f8c8d;
            font-size: 12px;
        }
        
        .notice-description {
            padding: 0 20px;
            line-height: 1.6;
            color: #5a6c7d;
        }
        
        .notice-attachment {
            padding: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        .attachment-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .attachment-btn:hover {
            background: #229954;
        }
        
        .notice-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .target-audience,
        .expiry-info {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .no-notices {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .no-notices i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .no-notices h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 40px;
            padding: 20px;
        }
        
        .page-link {
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .page-link:hover {
            background: #2980b9;
        }
        
        .page-info {
            color: #7f8c8d;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .search-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .notices-list-page {
                grid-template-columns: 1fr;
            }
            
            .pagination {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</body>
</html>
