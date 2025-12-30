<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$student_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $roll_number = sanitize_input($_POST['roll_number']);
        $registration_number = sanitize_input($_POST['registration_number']);
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $phone = sanitize_input($_POST['phone']);
        $course = sanitize_input($_POST['course']);
        $semester = sanitize_input($_POST['semester']);
        $session = sanitize_input($_POST['session']);
        $date_of_birth = $_POST['date_of_birth'];
        $gender = sanitize_input($_POST['gender']);
        $category = sanitize_input($_POST['category']);
        $address = sanitize_input($_POST['address']);
        $admission_date = $_POST['admission_date'] ?? date('Y-m-d');
        $status = sanitize_input($_POST['status']);
        
        // Handle profile image upload
        $profile_image = '';
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_file($_FILES['profile_image'], '../uploads/students/');
            if ($upload_result['success']) {
                $profile_image = $upload_result['path'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                // Check for duplicate roll number and registration number
                $check_query = "SELECT id FROM students WHERE roll_number = '$roll_number' OR registration_number = '$registration_number'";
                $check_result = mysqli_query($conn, $check_query);
                
                if (mysqli_num_rows($check_result) > 0) {
                    $error = 'Roll number or Registration number already exists!';
                } else {
                    $query = "INSERT INTO students (roll_number, registration_number, name, email, phone, course, semester, session, date_of_birth, gender, category, address, admission_date, profile_image, status) 
                              VALUES ('$roll_number', '$registration_number', '$name', '$email', '$phone', '$course', '$semester', '$session', '$date_of_birth', '$gender', '$category', '$address', '$admission_date', '$profile_image', '$status')";
                    
                    if (mysqli_query($conn, $query)) {
                        log_activity($_SESSION['admin_id'], 'add_student', "Added student: $name (Roll: $roll_number)");
                        $message = 'Student added successfully!';
                        $action = 'list';
                    } else {
                        $error = 'Failed to add student: ' . mysqli_error($conn);
                    }
                }
            } elseif ($action === 'edit' && $student_id) {
                // Check for duplicates (excluding current student)
                $check_query = "SELECT id FROM students WHERE (roll_number = '$roll_number' OR registration_number = '$registration_number') AND id != $student_id";
                $check_result = mysqli_query($conn, $check_query);
                
                if (mysqli_num_rows($check_result) > 0) {
                    $error = 'Roll number or Registration number already exists!';
                } else {
                    $query = "UPDATE students SET roll_number='$roll_number', registration_number='$registration_number', name='$name', email='$email', phone='$phone', course='$course', semester='$semester', session='$session', date_of_birth='$date_of_birth', gender='$gender', category='$category', address='$address', admission_date='$admission_date', status='$status'";
                    
                    if ($profile_image) {
                        $query .= ", profile_image='$profile_image'";
                    }
                    
                    $query .= " WHERE id = $student_id";
                    
                    if (mysqli_query($conn, $query)) {
                        log_activity($_SESSION['admin_id'], 'edit_student', "Updated student: $name (Roll: $roll_number)");
                        $message = 'Student updated successfully!';
                        $action = 'list';
                    } else {
                        $error = 'Failed to update student: ' . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

// Delete Student
if ($action === 'delete' && $student_id) {
    $query = "DELETE FROM students WHERE id = $student_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_student', "Deleted student ID: $student_id");
        $message = 'Student deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete student: ' . mysqli_error($conn);
    }
}

// Get student for editing
$student = null;
if (($action === 'edit' || $action === 'view') && $student_id) {
    $query = "SELECT * FROM students WHERE id = $student_id";
    $result = mysqli_query($conn, $query);
    $student = mysqli_fetch_assoc($result);
}

// Get all students for listing
$students = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $course_filter = $_GET['course'] ?? '';
    $semester_filter = $_GET['semester'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    
    $query = "SELECT * FROM students WHERE 1=1";
    
    if ($search) {
        $query .= " AND (name LIKE '%$search%' OR roll_number LIKE '%$search%' OR registration_number LIKE '%$search%')";
    }
    
    if ($course_filter) {
        $query .= " AND course = '$course_filter'";
    }
    
    if ($semester_filter) {
        $query .= " AND semester = '$semester_filter'";
    }
    
    if ($status_filter) {
        $query .= " AND status = '$status_filter'";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get unique courses and semesters for filters
$courses = [];
$semesters = [];

$course_result = mysqli_query($conn, "SELECT DISTINCT course FROM students WHERE course != '' ORDER BY course");
while ($row = mysqli_fetch_assoc($course_result)) {
    $courses[] = $row['course'];
}

$semester_result = mysqli_query($conn, "SELECT DISTINCT semester FROM students WHERE semester != '' ORDER BY semester");
while ($row = mysqli_fetch_assoc($semester_result)) {
    $semesters[] = $row['semester'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management - SDGD College</title>
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
                    <li><a href="students.php" class="active"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="teachers.php"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
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
                    <h1>Students Management</h1>
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
                        <h2>All Students</h2>
                        <a href="students.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Student
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by name, roll number, or registration..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="course">
                                    <option value="">All Courses</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo $course; ?>" <?php echo ($_GET['course'] ?? '') === $course ? 'selected' : ''; ?>>
                                            <?php echo $course; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <select name="semester">
                                    <option value="">All Semesters</option>
                                    <?php foreach ($semesters as $semester): ?>
                                        <option value="<?php echo $semester; ?>" <?php echo ($_GET['semester'] ?? '') === $semester ? 'selected' : ''; ?>>
                                            <?php echo $semester; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($_GET['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="passed" <?php echo ($_GET['status'] ?? '') === 'passed' ? 'selected' : ''; ?>>Passed</option>
                                    <option value="left" <?php echo ($_GET['status'] ?? '') === 'left' ? 'selected' : ''; ?>>Left</option>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="students.php" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Roll Number</th>
                                    <th>Registration</th>
                                    <th>Course</th>
                                    <th>Semester</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item['profile_image']): ?>
                                                <img src="<?php echo $item['profile_image']; ?>" alt="Photo" class="student-photo">
                                            <?php else: ?>
                                                <div class="no-photo">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $item['name']; ?></td>
                                        <td><?php echo $item['roll_number']; ?></td>
                                        <td><?php echo $item['registration_number']; ?></td>
                                        <td><?php echo $item['course']; ?></td>
                                        <td><?php echo $item['semester']; ?></td>
                                        <td><?php echo $item['phone']; ?></td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="students.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="students.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="students.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')">
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
                        <h2><?php echo $action === 'add' ? 'Add New Student' : 'Edit Student'; ?></h2>
                        <a href="students.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Students
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="roll_number">Roll Number *</label>
                                    <input type="text" id="roll_number" name="roll_number" required 
                                           value="<?php echo $student['roll_number'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="registration_number">Registration Number *</label>
                                    <input type="text" id="registration_number" name="registration_number" required 
                                           value="<?php echo $student['registration_number'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo $student['name'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required 
                                           value="<?php echo $student['email'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" 
                                           value="<?php echo $student['phone'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="course">Course *</label>
                                    <input type="text" id="course" name="course" required 
                                           value="<?php echo $student['course'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="semester">Semester *</label>
                                    <input type="text" id="semester" name="semester" required 
                                           value="<?php echo $student['semester'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="session">Session *</label>
                                    <input type="text" id="session" name="session" required 
                                           placeholder="e.g., 2025-28"
                                           value="<?php echo $student['session'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth *</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" required 
                                           value="<?php echo $student['date_of_birth'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="gender">Gender *</label>
                                    <select id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" <?php echo ($student['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="female" <?php echo ($student['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="other" <?php echo ($student['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select id="category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="General" <?php echo ($student['category'] ?? '') === 'General' ? 'selected' : ''; ?>>General</option>
                                        <option value="OBC" <?php echo ($student['category'] ?? '') === 'OBC' ? 'selected' : ''; ?>>OBC</option>
                                        <option value="SC" <?php echo ($student['category'] ?? '') === 'SC' ? 'selected' : ''; ?>>SC</option>
                                        <option value="ST" <?php echo ($student['category'] ?? '') === 'ST' ? 'selected' : ''; ?>>ST</option>
                                        <option value="EWS" <?php echo ($student['category'] ?? '') === 'EWS' ? 'selected' : ''; ?>>EWS</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" rows="3"><?php echo $student['address'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="admission_date">Admission Date *</label>
                                    <input type="date" id="admission_date" name="admission_date" required 
                                           value="<?php echo $student['admission_date'] ?? date('Y-m-d'); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select id="status" name="status" required>
                                        <option value="active" <?php echo ($student['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($student['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="passed" <?php echo ($student['status'] ?? '') === 'passed' ? 'selected' : ''; ?>>Passed</option>
                                        <option value="left" <?php echo ($student['status'] ?? '') === 'left' ? 'selected' : ''; ?>>Left</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="profile_image">Profile Photo</label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                                <?php if ($student && $student['profile_image']): ?>
                                    <p class="file-info">Current photo: <img src="<?php echo $student['profile_image']; ?>" alt="Current" class="current-photo"></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Student' : 'Update Student'; ?>
                                </button>
                                <a href="students.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $student): ?>
                    <div class="content-header">
                        <h2>Student Details</h2>
                        <a href="students.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Students
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <div class="student-info">
                                <?php if ($student['profile_image']): ?>
                                    <img src="<?php echo $student['profile_image']; ?>" alt="Photo" class="student-photo-large">
                                <?php else: ?>
                                    <div class="no-photo-large">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="student-details">
                                    <h3><?php echo $student['name']; ?></h3>
                                    <p><strong>Roll Number:</strong> <?php echo $student['roll_number']; ?></p>
                                    <p><strong>Registration Number:</strong> <?php echo $student['registration_number']; ?></p>
                                    <span class="status status-<?php echo $student['status']; ?>">
                                        <?php echo ucfirst($student['status']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <div class="info-grid">
                                <div class="info-section">
                                    <h4>Academic Information</h4>
                                    <p><strong>Course:</strong> <?php echo $student['course']; ?></p>
                                    <p><strong>Semester:</strong> <?php echo $student['semester']; ?></p>
                                    <p><strong>Session:</strong> <?php echo $student['session']; ?></p>
                                    <p><strong>Admission Date:</strong> <?php echo format_date($student['admission_date']); ?></p>
                                </div>
                                
                                <div class="info-section">
                                    <h4>Personal Information</h4>
                                    <p><strong>Date of Birth:</strong> <?php echo format_date($student['date_of_birth']); ?></p>
                                    <p><strong>Gender:</strong> <?php echo ucfirst($student['gender']); ?></p>
                                    <p><strong>Category:</strong> <?php echo $student['category'] ?: 'Not specified'; ?></p>
                                    <p><strong>Address:</strong> <?php echo $student['address'] ?: 'Not specified'; ?></p>
                                </div>
                                
                                <div class="info-section">
                                    <h4>Contact Information</h4>
                                    <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
                                    <p><strong>Phone:</strong> <?php echo $student['phone'] ?: 'Not specified'; ?></p>
                                </div>
                                
                                <div class="info-section">
                                    <h4>System Information</h4>
                                    <p><strong>Status:</strong> <?php echo ucfirst($student['status']); ?></p>
                                    <p><strong>Added Date:</strong> <?php echo format_date($student['created_at'], 'd/m/Y H:i'); ?></p>
                                    <p><strong>Last Updated:</strong> <?php echo format_date($student['updated_at'], 'd/m/Y H:i'); ?></p>
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
        
        // Set maximum date for date of birth (should be at least 16 years old)
        document.addEventListener('DOMContentLoaded', function() {
            const dobField = document.getElementById('date_of_birth');
            if (dobField) {
                const maxDate = new Date();
                maxDate.setFullYear(maxDate.getFullYear() - 16);
                dobField.max = maxDate.toISOString().split('T')[0];
            }
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
            grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
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
        
        .student-photo {
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
        .status-passed { background: #3498db; color: white; }
        .status-left { background: #e67e22; color: white; }
        
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
        
        .student-info {
            display: flex;
            gap: 20px;
            align-items: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .student-photo-large {
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
        
        .student-details h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .student-details p {
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
            
            .student-info {
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
