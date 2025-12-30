<?php
require_once 'config.php';

// Get tenders with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

// Build query
$query = "SELECT * FROM tenders WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM tenders WHERE 1=1";

if ($search) {
    $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
    $count_query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}

if ($status) {
    $query .= " AND status = '$status'";
    $count_query .= " AND status = '$status'";
}

$query .= " ORDER BY created_at DESC LIMIT $offset, $per_page";

$result = mysqli_query($conn, $query);
$tenders = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get total count for pagination
$count_result = mysqli_query($conn, $count_query);
$total_tenders = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_tenders / $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenders - SDGD College</title>
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
                <li><a href="notices.php"><i class="fas fa-bullhorn"></i> NOTICES</a></li>
                <li><a href="tender.php" class="active"><i class="fas fa-file-contract"></i> TENDER</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-file-contract"></i> Tender Notices</h1>
            <p>Official tender notices and procurement opportunities from SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Search and Filter -->
            <div class="search-section">
                <form method="GET" class="search-form">
                    <div class="search-row">
                        <input type="text" name="search" placeholder="Search tenders..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                            <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tenders List -->
            <div class="tenders-list-page">
                <?php if (count($tenders) > 0): ?>
                    <?php foreach ($tenders as $tender): ?>
                        <div class="tender-card">
                            <div class="tender-header">
                                <h3><?php echo $tender['title']; ?></h3>
                                <div class="tender-meta">
                                    <span class="tender-type"><?php echo $tender['tender_type'] ?: 'General'; ?></span>
                                    <span class="status status-<?php echo $tender['status']; ?>">
                                        <?php echo ucfirst($tender['status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if ($tender['description']): ?>
                                <div class="tender-description">
                                    <?php echo nl2br($tender['description']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="tender-details">
                                <div class="detail-row">
                                    <span class="detail-label">
                                        <i class="fas fa-calendar-alt"></i> Last Date:
                                    </span>
                                    <span class="detail-value <?php 
                                        $last_date = new DateTime($tender['last_date']);
                                        $today = new DateTime();
                                        $days_left = $today->diff($last_date)->format('%r%a');
                                        
                                        if ($days_left < 0) {
                                            echo 'expired';
                                        } elseif ($days_left <= 7) {
                                            echo 'urgent';
                                        } else {
                                            echo 'normal';
                                        }
                                    ?>">
                                        <?php echo format_date($tender['last_date']); ?>
                                        <?php 
                                        if ($days_left < 0) {
                                            echo ' (Expired)';
                                        } elseif ($days_left <= 7) {
                                            echo ' (' . $days_left . ' days left)';
                                        }
                                        ?>
                                    </span>
                                </div>
                                
                                <?php if ($tender['file_name']): ?>
                                    <div class="detail-row">
                                        <span class="detail-label">
                                            <i class="fas fa-file-pdf"></i> Document:
                                        </span>
                                        <a href="<?php echo $tender['file_path']; ?>" target="_blank" class="document-btn">
                                            <i class="fas fa-download"></i> <?php echo $tender['file_name']; ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="tender-footer">
                                <span class="posted-date">
                                    <i class="fas fa-clock"></i> Posted: <?php echo format_date($tender['created_at']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-tenders">
                        <i class="fas fa-file-contract"></i>
                        <h3>No Tenders Found</h3>
                        <p><?php echo $search ? 'No tenders found matching your search criteria.' : 'No tender notices available at this time.'; ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>" class="page-link">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>
                    
                    <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>" class="page-link">
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
        
        .tenders-list-page {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
        }
        
        .tender-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .tender-card:hover {
            transform: translateY(-5px);
        }
        
        .tender-header {
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .tender-header h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 18px;
        }
        
        .tender-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .tender-type {
            background: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-active { background: #27ae60; color: white; }
        .status-closed { background: #95a5a6; color: white; }
        .status-cancelled { background: #e74c3c; color: white; }
        
        .tender-description {
            padding: 0 20px;
            line-height: 1.6;
            color: #5a6c7d;
        }
        
        .tender-details {
            padding: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .detail-label {
            color: #7f8c8d;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .detail-value {
            font-weight: 500;
            color: #2c3e50;
        }
        
        .detail-value.expired {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .detail-value.urgent {
            color: #f39c12;
            font-weight: bold;
        }
        
        .document-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e74c3c;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            font-size: 12px;
        }
        
        .document-btn:hover {
            background: #c0392b;
        }
        
        .tender-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .posted-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .no-tenders {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .no-tenders i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .no-tenders h3 {
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
            
            .tenders-list-page {
                grid-template-columns: 1fr;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .pagination {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</body>
</html>
