<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$course_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $course_name = sanitize_input($_POST['course_name']);
        $course_code = sanitize_input($_POST['course_code']);
        $duration = sanitize_input($_POST['duration']);
        $eligibility = sanitize_input($_POST['eligibility']);
        $fees = sanitize_input($_POST['fees']);
        $description = sanitize_input($_POST['description']);
        $status = sanitize_input($_POST['status']);
        
        // Handle file upload
        $course_image = '';
        if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['course_image'];
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($file['tmp_name']);
            
            if (!in_array($file_type, $allowed_types)) {
                $error = 'Invalid file type. Only JPG, PNG, and GIF images are allowed.';
            } elseif ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
                $error = 'File size too large. Maximum size is 5MB.';
            } else {
                // Create upload directory if it doesn't exist
                $upload_dir = '../assets/courses/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Generate unique filename
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'course_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $course_image = 'assets/courses/' . $filename;
                } else {
                    $error = 'Failed to upload image. Please try again.';
                }
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                // Check for duplicate course code
                $check_query = "SELECT id FROM courses WHERE course_code = '$course_code'";
                $check_result = mysqli_query($conn, $check_query);
                
                if (mysqli_num_rows($check_result) > 0) {
                    $error = 'Course code already exists!';
                } else {
                    $query = "INSERT INTO courses (course_name, course_code, duration, eligibility, fees, course_image, description, status) 
                              VALUES ('$course_name', '$course_code', '$duration', '$eligibility', '$fees', '$course_image', '$description', '$status')";
                    
                    if (mysqli_query($conn, $query)) {
                        log_activity($_SESSION['admin_id'], 'add_course', "Added course: $course_name ($course_code)");
                        $message = 'Course added successfully!';
                        $action = 'list';
                    } else {
                        $error = 'Failed to add course: ' . mysqli_error($conn);
                    }
                }
            } elseif ($action === 'edit' && $course_id) {
                // Check for duplicates (excluding current course)
                $check_query = "SELECT id FROM courses WHERE course_code = '$course_code' AND id != $course_id";
                $check_result = mysqli_query($conn, $check_query);
                
                if (mysqli_num_rows($check_result) > 0) {
                    $error = 'Course code already exists!';
                } else {
                    // Handle image update for edit
                    $image_update = '';
                    if (!empty($course_image)) {
                        $image_update = ", course_image='$course_image'";
                    }
                    
                    $query = "UPDATE courses SET course_name='$course_name', course_code='$course_code', duration='$duration', eligibility='$eligibility', fees='$fees'$image_update, description='$description', status='$status' WHERE id = $course_id";
                    
                    if (mysqli_query($conn, $query)) {
                        log_activity($_SESSION['admin_id'], 'edit_course', "Updated course: $course_name ($course_code)");
                        $message = 'Course updated successfully!';
                        $action = 'list';
                    } else {
                        $error = 'Failed to update course: ' . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

// Delete Course
if ($action === 'delete' && $course_id) {
    $query = "DELETE FROM courses WHERE id = $course_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_course', "Deleted course ID: $course_id");
        $message = 'Course deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete course: ' . mysqli_error($conn);
    }
}

// Get course for editing
$course = null;
if (($action === 'edit' || $action === 'view') && $course_id) {
    $query = "SELECT * FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);
    $course = mysqli_fetch_assoc($result);
}

// Get all courses for listing
$courses = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    
    $query = "SELECT * FROM courses WHERE 1=1";
    
    if ($search) {
        $query .= " AND (course_name LIKE '%$search%' OR course_code LIKE '%$search%')";
    }
    
    if ($status_filter) {
        $query .= " AND status = '$status_filter'";
    }
    
    $query .= " ORDER BY course_name";
    
    $result = mysqli_query($conn, $query);
    $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management - SDGD College</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-graduation-cap"></i> SDGD College</h3>
                <p>Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="notices.php"><i class="fas fa-bullhorn"></i> Notices</a></li>
                    <li><a href="tenders.php"><i class="fas fa-file-contract"></i> Tenders</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="teachers.php"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
                    <li><a href="courses.php" class="active"><i class="fas fa-book"></i> Courses</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Courses Management</h1>
                </div>
                <div class="header-right">
                    <div class="admin-info">
                        <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                        <img src="../assets/admin-avatar.png" alt="Admin" class="admin-avatar" onerror="this.style.display='none'">
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="dashboard-content">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($action === 'list'): ?>
                    <div class="content-header">
                        <h2>All Courses</h2>
                        <a href="courses.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Course
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by course name or code..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($_GET['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="courses.php" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>Course Code</th>
                                    <th>Duration</th>
                                    <th>Fees</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $item): ?>
                                    <tr>
                                        <td><?php echo $item['course_name']; ?></td>
                                        <td><strong><?php echo $item['course_code']; ?></strong></td>
                                        <td><?php echo $item['duration']; ?></td>
                                        <td>₹<?php echo number_format($item['fees'], 2); ?></td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="courses.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="courses.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="courses.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this course?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                    <div class="content-header">
                        <h2><?php echo $action === 'add' ? 'Add New Course' : 'Edit Course'; ?></h2>
                        <a href="courses.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Courses
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="course_name">Course Name *</label>
                                    <input type="text" id="course_name" name="course_name" required 
                                           value="<?php echo $course['course_name'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="course_code">Course Code *</label>
                                    <input type="text" id="course_code" name="course_code" required 
                                           value="<?php echo $course['course_code'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="duration">Duration</label>
                                    <input type="text" id="duration" name="duration" 
                                           placeholder="e.g., 3 Years"
                                           value="<?php echo $course['duration'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="fees">Course Fees (₹)</label>
                                    <input type="number" id="fees" name="fees" step="0.01" min="0"
                                           value="<?php echo $course['fees'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="course_image">Course Image</label>
                                <input type="file" id="course_image" name="course_image" accept="image/*" class="file-input">
                                <small class="form-help">Allowed formats: JPG, PNG, GIF. Maximum size: 5MB</small>
                                <?php if (isset($course['course_image']) && !empty($course['course_image'])): ?>
                                    <div class="current-image">
                                        <img src="<?php echo $course['course_image']; ?>" alt="Current course image" style="max-width: 200px; margin-top: 10px;">
                                        <br>
                                        <small>Current image</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="eligibility">Eligibility Criteria</label>
                                <textarea id="eligibility" name="eligibility" rows="4"><?php echo $course['eligibility'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Course Description</label>
                                <textarea id="description" name="description" rows="6"><?php echo $course['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select id="status" name="status" required>
                                    <option value="active" <?php echo ($course['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($course['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Course' : 'Update Course'; ?>
                                </button>
                                <a href="courses.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $course): ?>
                    <div class="content-header">
                        <h2>Course Details</h2>
                        <a href="courses.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Courses
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <h3><?php echo $course['course_name']; ?></h3>
                            <div class="view-meta">
                                <span class="course-code"><?php echo $course['course_code']; ?></span>
                                <span class="status status-<?php echo $course['status']; ?>">
                                    <?php echo ucfirst($course['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <div class="course-details">
                                <div class="detail-section">
                                    <h4>Basic Information</h4>
                                    <p><strong>Course Name:</strong> <?php echo $course['course_name']; ?></p>
                                    <p><strong>Course Code:</strong> <?php echo $course['course_code']; ?></p>
                                    <p><strong>Duration:</strong> <?php echo $course['duration'] ?: 'Not specified'; ?></p>
                                    <p><strong>Course Fees:</strong> ₹<?php echo number_format($course['fees'], 2); ?></p>
                                    <p><strong>Status:</strong> <?php echo ucfirst($course['status']); ?></p>
                                </div>
                                
                                <?php if ($course['eligibility']): ?>
                                    <div class="detail-section">
                                        <h4>Eligibility Criteria</h4>
                                        <p><?php echo nl2br($course['eligibility']); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($course['description']): ?>
                                    <div class="detail-section">
                                        <h4>Course Description</h4>
                                        <p><?php echo nl2br($course['description']); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="detail-section">
                                    <h4>System Information</h4>
                                    <p><strong>Added Date:</strong> <?php echo format_date($course['created_at'], 'd/m/Y H:i'); ?></p>
                                    <p><strong>Last Updated:</strong> <?php echo format_date($course['updated_at'], 'd/m/Y H:i'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
    
    <style>
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .content-header h2 {
            margin: 0;
            color: #2c3e50;
        }
        
        .search-filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .filter-form {
            margin: 0;
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr auto auto;
            gap: 15px;
            align-items: end;
        }
        
        .filter-row input,
        .filter-row select {
            padding: 10px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 8px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
            margin-left: 5px;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-active { background: #27ae60; color: white; }
        .status-inactive { background: #95a5a6; color: white; }
        
        .course-code {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .view-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .view-header {
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .view-header h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .view-meta {
            display: flex;
            gap: 10px;
        }
        
        .course-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .detail-section h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 5px;
        }
        
        .detail-section p {
            margin: 8px 0;
            color: #5a6c7d;
        }
        
        .detail-section strong {
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .content-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .course-details {
                grid-template-columns: 1fr;
            }
        }
        
        /* File Upload Styles */
        .file-input {
            border: 2px dashed #ddd;
            padding: 10px;
            border-radius: 5px;
            background: #f9f9f9;
            transition: border-color 0.3s;
        }
        
        .file-input:hover {
            border-color: #3498db;
        }
        
        .form-help {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 12px;
        }
        
        .current-image {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .current-image img {
            border-radius: 3px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</body>
</html>
