<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$teacher_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $employee_id = sanitize_input($_POST['employee_id']);
        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        $full_name = sanitize_input($_POST['full_name']);
        $department = sanitize_input($_POST['department']);
        $designation = sanitize_input($_POST['designation']);
        $qualification = sanitize_input($_POST['qualification']);
        $experience = sanitize_input($_POST['experience']);
        $phone = sanitize_input($_POST['phone']);
        $address = sanitize_input($_POST['address']);
        $status = sanitize_input($_POST['status']);
        
        // Handle profile image upload
        $profile_image = '';
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_file($_FILES['profile_image'], '../uploads/teachers/');
            if ($upload_result['success']) {
                $profile_image = $upload_result['path'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                // Check for duplicate employee ID, username, and email
                $check_query = "SELECT id FROM teachers WHERE employee_id = '$employee_id' OR username = '$username' OR email = '$email'";
                $check_result = mysqli_query($conn, $check_query);
                
                if (mysqli_num_rows($check_result) > 0) {
                    $error = 'Employee ID, Username, or Email already exists!';
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $query = "INSERT INTO teachers (employee_id, username, email, password, full_name, department, designation, qualification, experience, phone, address, profile_image, status) 
                              VALUES ('$employee_id', '$username', '$email', '$hashed_password', '$full_name', '$department', '$designation', '$qualification', '$experience', '$phone', '$address', '$profile_image', '$status')";
                    
                    if (mysqli_query($conn, $query)) {
                        log_activity($_SESSION['admin_id'], 'add_teacher', "Added teacher: $full_name (Employee ID: $employee_id)");
                        $message = 'Teacher added successfully!';
                        $action = 'list';
                    } else {
                        $error = 'Failed to add teacher: ' . mysqli_error($conn);
                    }
                }
            } elseif ($action === 'edit' && $teacher_id) {
                // Check for duplicates (excluding current teacher)
                $check_query = "SELECT id FROM teachers WHERE (employee_id = '$employee_id' OR username = '$username' OR email = '$email') AND id != $teacher_id";
                $check_result = mysqli_query($conn, $check_query);
                
                if (mysqli_num_rows($check_result) > 0) {
                    $error = 'Employee ID, Username, or Email already exists!';
                } else {
                    $query = "UPDATE teachers SET employee_id='$employee_id', username='$username', email='$email', full_name='$full_name', department='$department', designation='$designation', qualification='$qualification', experience='$experience', phone='$phone', address='$address', status='$status'";
                    
                    if ($profile_image) {
                        $query .= ", profile_image='$profile_image'";
                    }
                    
                    // Update password if provided
                    if (!empty($password)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $query .= ", password='$hashed_password'";
                    }
                    
                    $query .= " WHERE id = $teacher_id";
                    
                    if (mysqli_query($conn, $query)) {
                        log_activity($_SESSION['admin_id'], 'edit_teacher', "Updated teacher: $full_name (Employee ID: $employee_id)");
                        $message = 'Teacher updated successfully!';
                        $action = 'list';
                    } else {
                        $error = 'Failed to update teacher: ' . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

// Delete Teacher
if ($action === 'delete' && $teacher_id) {
    $query = "DELETE FROM teachers WHERE id = $teacher_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_teacher', "Deleted teacher ID: $teacher_id");
        $message = 'Teacher deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete teacher: ' . mysqli_error($conn);
    }
}

// Get teacher for editing
$teacher = null;
if (($action === 'edit' || $action === 'view') && $teacher_id) {
    $query = "SELECT * FROM teachers WHERE id = $teacher_id";
    $result = mysqli_query($conn, $query);
    $teacher = mysqli_fetch_assoc($result);
}

// Get all teachers for listing
$teachers = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $department_filter = $_GET['department'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    
    $query = "SELECT * FROM teachers WHERE 1=1";
    
    if ($search) {
        $query .= " AND (full_name LIKE '%$search%' OR employee_id LIKE '%$search%' OR username LIKE '%$search%')";
    }
    
    if ($department_filter) {
        $query .= " AND department = '$department_filter'";
    }
    
    if ($status_filter) {
        $query .= " AND status = '$status_filter'";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $teachers = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get unique departments for filters
$departments = [];
$dept_result = mysqli_query($conn, "SELECT DISTINCT department FROM teachers WHERE department != '' ORDER BY department");
while ($row = mysqli_fetch_assoc($dept_result)) {
    $departments[] = $row['department'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Management - SDGD College</title>
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
                    <li><a href="teachers.php" class="active"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
                    <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
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
                    <h1>Teachers Management</h1>
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
                        <h2>All Teachers</h2>
                        <a href="teachers.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Teacher
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by name, employee ID, or username..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="department">
                                    <option value="">All Departments</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?php echo $department; ?>" <?php echo ($_GET['department'] ?? '') === $department ? 'selected' : ''; ?>>
                                            <?php echo $department; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($_GET['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="teachers.php" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Employee ID</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item['profile_image']): ?>
                                                <img src="<?php echo $item['profile_image']; ?>" alt="Photo" class="teacher-photo">
                                            <?php else: ?>
                                                <div class="no-photo">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $item['full_name']; ?></td>
                                        <td><?php echo $item['employee_id']; ?></td>
                                        <td><?php echo $item['department']; ?></td>
                                        <td><?php echo $item['designation']; ?></td>
                                        <td><?php echo $item['email']; ?></td>
                                        <td><?php echo $item['phone']; ?></td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="teachers.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="teachers.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="teachers.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this teacher?')">
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
                        <h2><?php echo $action === 'add' ? 'Add New Teacher' : 'Edit Teacher'; ?></h2>
                        <a href="teachers.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Teachers
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="employee_id">Employee ID *</label>
                                    <input type="text" id="employee_id" name="employee_id" required 
                                           value="<?php echo $teacher['employee_id'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="username">Username *</label>
                                    <input type="text" id="username" name="username" required 
                                           value="<?php echo $teacher['username'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="full_name">Full Name *</label>
                                <input type="text" id="full_name" name="full_name" required 
                                       value="<?php echo $teacher['full_name'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required 
                                           value="<?php echo $teacher['email'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password <?php echo $action === 'edit' ? '(Leave blank to keep current)' : '*'; ?></label>
                                    <input type="password" id="password" name="password" <?php echo $action === 'add' ? 'required' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="department">Department *</label>
                                    <input type="text" id="department" name="department" required 
                                           value="<?php echo $teacher['department'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="designation">Designation *</label>
                                    <input type="text" id="designation" name="designation" required 
                                           value="<?php echo $teacher['designation'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="qualification">Qualification</label>
                                    <input type="text" id="qualification" name="qualification" 
                                           value="<?php echo $teacher['qualification'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="experience">Experience</label>
                                    <input type="text" id="experience" name="experience" 
                                           placeholder="e.g., 5 years"
                                           value="<?php echo $teacher['experience'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" 
                                           value="<?php echo $teacher['phone'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select id="status" name="status" required>
                                        <option value="active" <?php echo ($teacher['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($teacher['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" rows="3"><?php echo $teacher['address'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="profile_image">Profile Photo</label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                                <?php if ($teacher && $teacher['profile_image']): ?>
                                    <p class="file-info">Current photo: <img src="<?php echo $teacher['profile_image']; ?>" alt="Current" class="current-photo"></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Teacher' : 'Update Teacher'; ?>
                                </button>
                                <a href="teachers.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $teacher): ?>
                    <div class="content-header">
                        <h2>Teacher Details</h2>
                        <a href="teachers.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Teachers
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <div class="teacher-info">
                                <?php if ($teacher['profile_image']): ?>
                                    <img src="<?php echo $teacher['profile_image']; ?>" alt="Photo" class="teacher-photo-large">
                                <?php else: ?>
                                    <div class="no-photo-large">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="teacher-details">
                                    <h3><?php echo $teacher['full_name']; ?></h3>
                                    <p><strong>Employee ID:</strong> <?php echo $teacher['employee_id']; ?></p>
                                    <p><strong>Username:</strong> <?php echo $teacher['username']; ?></p>
                                    <p><strong>Designation:</strong> <?php echo $teacher['designation']; ?></p>
                                    <span class="status status-<?php echo $teacher['status']; ?>">
                                        <?php echo ucfirst($teacher['status']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <div class="info-grid">
                                <div class="info-section">
                                    <h4>Professional Information</h4>
                                    <p><strong>Department:</strong> <?php echo $teacher['department']; ?></p>
                                    <p><strong>Designation:</strong> <?php echo $teacher['designation']; ?></p>
                                    <p><strong>Qualification:</strong> <?php echo $teacher['qualification'] ?: 'Not specified'; ?></p>
                                    <p><strong>Experience:</strong> <?php echo $teacher['experience'] ?: 'Not specified'; ?></p>
                                </div>
                                
                                <div class="info-section">
                                    <h4>Contact Information</h4>
                                    <p><strong>Email:</strong> <?php echo $teacher['email']; ?></p>
                                    <p><strong>Phone:</strong> <?php echo $teacher['phone'] ?: 'Not specified'; ?></p>
                                    <p><strong>Address:</strong> <?php echo $teacher['address'] ?: 'Not specified'; ?></p>
                                </div>
                                
                                <div class="info-section">
                                    <h4>Account Information</h4>
                                    <p><strong>Username:</strong> <?php echo $teacher['username']; ?></p>
                                    <p><strong>Status:</strong> <?php echo ucfirst($teacher['status']); ?></p>
                                    <p><strong>Last Login:</strong> <?php echo $teacher['last_login'] ? format_date($teacher['last_login'], 'd/m/Y H:i') : 'Never'; ?></p>
                                </div>
                                
                                <div class="info-section">
                                    <h4>System Information</h4>
                                    <p><strong>Added Date:</strong> <?php echo format_date($teacher['created_at'], 'd/m/Y H:i'); ?></p>
                                    <p><strong>Last Updated:</strong> <?php echo format_date($teacher['updated_at'], 'd/m/Y H:i'); ?></p>
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
            grid-template-columns: 2fr 1fr 1fr auto auto;
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
        
        .teacher-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3498db;
        }
        
        .no-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ecf0f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 16px;
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
        
        .file-info {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .current-photo {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
            vertical-align: middle;
            margin-left: 10px;
        }
        
        .view-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .teacher-info {
            display: flex;
            gap: 20px;
            align-items: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .teacher-photo-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
        }
        
        .no-photo-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #ecf0f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 40px;
            border: 3px solid #3498db;
        }
        
        .teacher-details h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .teacher-details p {
            margin: 5px 0;
            color: #5a6c7d;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .info-section h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 5px;
        }
        
        .info-section p {
            margin: 8px 0;
            color: #5a6c7d;
        }
        
        .info-section strong {
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
            
            .teacher-info {
                flex-direction: column;
                text-align: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
