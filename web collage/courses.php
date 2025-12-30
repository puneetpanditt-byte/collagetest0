<?php
require_once 'config.php';

// Check database connection
if (!$conn || mysqli_connect_errno()) {
    die("Database connection failed. Please check your configuration. Error: " . mysqli_connect_error());
}

// Handle admin actions
if (isset($_GET['action']) && is_admin()) {
    $action = sanitize_input($_GET['action']);
    $course_id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
    
    if ($action === 'delete' && $course_id > 0) {
        // Delete course (soft delete by setting status to inactive) - Using prepared statement
        $delete_query = "UPDATE courses SET status = 'inactive' WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $course_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Course deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting course: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
        header("Location: courses.php");
        exit();
    }
    
    if ($action === 'edit' && $course_id > 0) {
        // Redirect to admin edit page
        header("Location: admin/courses.php?action=edit&id=$course_id");
        exit();
    }
    
    if ($action === 'view' && $course_id > 0) {
        // Redirect to admin view page
        header("Location: admin/courses.php?action=view&id=$course_id");
        exit();
    }
}

// Get filter parameters with validation
$search = sanitize_input($_GET['search'] ?? '');
$course_type_filter = sanitize_input($_GET['course_type'] ?? '');
$department_filter = sanitize_input($_GET['department'] ?? '');

// Build base query
$query = "SELECT * FROM courses WHERE status = 'active'";
$params = [];
$types = '';

// Add search filter
if (!empty($search)) {
    $query .= " AND (course_name LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

// Add course type filter
if (!empty($course_type_filter)) {
    $query .= " AND course_type = ?";
    $params[] = $course_type_filter;
    $types .= 's';
}

// Add department filter
if (!empty($department_filter)) {
    $query .= " AND department = ?";
    $params[] = $department_filter;
    $types .= 's';
}

$query .= " ORDER BY course_name";

// Execute with prepared statement
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if (!mysqli_stmt_execute($stmt)) {
    die("Query execution failed: " . mysqli_stmt_error($stmt));
}
$result = mysqli_stmt_get_result($stmt);
$courses = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
mysqli_stmt_close($stmt);

// Get unique courses for filter
$course_types = [];
$type_query = "SELECT DISTINCT course_type FROM courses WHERE course_type != '' AND status = 'active' ORDER BY course_type";
$type_stmt = mysqli_prepare($conn, $type_query);
if (!$type_stmt) {
    die("Type query preparation failed: " . mysqli_error($conn));
}
if (!mysqli_stmt_execute($type_stmt)) {
    die("Type query execution failed: " . mysqli_stmt_error($type_stmt));
}
$type_result = mysqli_stmt_get_result($type_stmt);
if ($type_result) {
    while ($row = mysqli_fetch_assoc($type_result)) {
        $course_types[] = htmlspecialchars($row['course_type']);
    }
}
mysqli_stmt_close($type_stmt);

// Get unique departments for filter
$departments = [];
$dept_query = "SELECT DISTINCT department FROM courses WHERE department != '' AND status = 'active' ORDER BY department";
$dept_stmt = mysqli_prepare($conn, $dept_query);
if (!$dept_stmt) {
    die("Department query preparation failed: " . mysqli_error($conn));
}
if (!mysqli_stmt_execute($dept_stmt)) {
    die("Department query execution failed: " . mysqli_stmt_error($dept_stmt));
}
$dept_result = mysqli_stmt_get_result($dept_stmt);
if ($dept_result) {
    while ($row = mysqli_fetch_assoc($dept_result)) {
        $departments[] = htmlspecialchars($row['department']);
    }
}
mysqli_stmt_close($dept_stmt);

// Get unique semesters for filter
$semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - SDGD College</title>
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
                <li><a href="courses.php" class="active"><i class="fas fa-book"></i> COURSES</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
        </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-book"></i> Courses Offered</h1>
            <p>Explore our academic programs and course offerings</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="filter-form">
                    <div class="filter-row">
                        <input type="text" name="search" placeholder="Search by course name or description..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        
                        <select name="course_type">
                            <option value="">All Types</option>
                            <?php foreach ($course_types as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo ($_GET['course_type'] ?? '') === $type ? 'selected' : ''; ?>>
                                    <?php echo $type; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select name="department">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept; ?>" <?php echo ($_GET['department'] ?? '') === $dept ? 'selected' : ''; ?>>
                                    <?php echo $dept; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="courses.php" class="clear-btn">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Courses Grid -->
            <div class="course-grid">
                <?php if (count($courses) > 0): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <div class="course-header">
                                <h4><?php echo htmlspecialchars($course['course_name'] ?? 'Untitled Course'); ?></h4>
                                <span class="course-code"><?php echo htmlspecialchars($course['course_code'] ?? 'N/A'); ?></span>
                                <span class="course-type"><?php echo ucfirst(htmlspecialchars($course['course_type'] ?? 'General')); ?></span>
                            </div>
                            </div>
                            
                            <div class="course-details">
                                <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration'] ?? 'N/A'); ?></p>
                                <p><strong>Eligibility:</strong> <?php echo htmlspecialchars($course['eligibility'] ?? 'N/A'); ?></p>
                                <p><strong>Fees:</strong> ₹<?php echo number_format($course['fees'] ?? 0, 2); ?></p>
                            <p><strong>Seats:</strong> <?php echo htmlspecialchars($course['seats_available'] ?? 'N/A'); ?></p>
                                <p><strong>Created:</strong> <?php echo format_date($course['created_at'] ?? date('Y-m-d'), 'd/m/Y'); ?></p>
                            </div>
                            
                            <div class="course-actions">
                                <?php if (is_admin()): ?>
                                    <a href="courses.php?action=view&id=<?php echo $course['id']; ?>" class="view-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="courses.php?action=edit&id=<?php echo $course['id']; ?>" class="edit-btn" title="Edit Course">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="courses.php?action=delete&id=<?php echo $course['id']; ?>" class="delete-btn" title="Delete Course" onclick="return confirm('Are you sure you want to delete this course?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="#" class="view-btn" title="View Details" onclick="alert('Please login as admin to manage courses')">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-courses">
                        <i class="fas fa-book"></i>
                        <h3>No Courses Available</h3>
                        <p>No courses available at this time.</p>
                        <p>Please check back later for new updates.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Load More Button -->
            <?php if (count($courses) >= 12): ?>
                <div class="load-more">
                    <button class="load-more-btn" onclick="loadMoreCourses()">
                        <i class="fas fa-plus"></i> Load More Courses
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
                        <li><a href="courses.php">Courses</a></li>
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
        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.course-section');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
        }
        
        function loadMoreCourses() {
            // This would typically load more courses via AJAX
            alert('More courses would be loaded here in a real implementation with AJAX.');
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
        
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .course-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .course-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .course-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        
        .course-header h4 {
            margin: 0 0 10px 0;
            color: white;
            font-size: 16px;
        }
        
        .course-code {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .course-details {
            padding: 15px;
            background: rgba(255,255,255,0.9);
        }
        
        .course-details p {
            color: #5a6c7d;
            line-height: 1.6;
            margin: 5px 0;
        }
        
        .course-details strong {
            color: #2c3e50;
        }
        
        .course-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .view-btn,
        .edit-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        
        .view-btn:hover,
        .edit-btn:hover {
            background: #2980b9;
        }
        
        .delete-btn,
        .delete-btn:hover {
            background: #e74c3c;
        }
        
        .delete-btn:hover {
            background: #c0392b;
        }
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .course-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
