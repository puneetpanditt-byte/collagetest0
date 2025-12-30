<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$cost_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Cost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $cost_type = sanitize_input($_POST['cost_type']);
        $cost_name = sanitize_input($_POST['cost_name']);
        $amount = sanitize_input($_POST['amount']);
        $description = sanitize_input($_POST['description']);
        $academic_year = sanitize_input($_POST['academic_year']);
        $semester = sanitize_input($_POST['semester']);
        $course = sanitize_input($_POST['course']);
        $payment_method = sanitize_input($_POST['payment_method']);
        $due_date = sanitize_input($_POST['due_date']);
        $status = sanitize_input($_POST['status']);
        
        if (empty($cost_type) || empty($cost_name) || empty($amount) || empty($academic_year)) {
            $error = 'Required fields are missing!';
        } elseif (!is_numeric($amount) || $amount <= 0) {
            $error = 'Please enter a valid amount!';
        } else {
            if ($action === 'add') {
                $query = "INSERT INTO college_costs (cost_type, cost_name, amount, description, academic_year, semester, course, payment_method, due_date, status, created_by) 
                          VALUES ('$cost_type', '$cost_name', '$amount', '$description', '$academic_year', '$semester', '$course', '$payment_method', '$due_date', '$status', " . $_SESSION['admin_id'] . ")";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'add_cost', "Added cost: $cost_name - ₹$amount");
                    $message = 'Cost added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add cost: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit' && $cost_id) {
                $query = "UPDATE college_costs SET cost_type='$cost_type', cost_name='$cost_name', amount='$amount', description='$description', academic_year='$academic_year', semester='$semester', course='$course', payment_method='$payment_method', due_date='$due_date', status='$status', updated_by=" . $_SESSION['admin_id'] . ", updated_at=NOW() WHERE id = $cost_id";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'edit_cost', "Updated cost: $cost_name - ₹$amount");
                    $message = 'Cost updated successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to update cost: ' . mysqli_error($conn);
                }
            }
        }
    }
}

// Delete Cost
if ($action === 'delete' && $cost_id) {
    $query = "DELETE FROM college_costs WHERE id = $cost_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_cost', "Deleted cost ID: $cost_id");
        $message = 'Cost deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete cost: ' . mysqli_error($conn);
    }
}

// Get cost for editing
$cost = null;
if (($action === 'edit' || $action === 'view') && $cost_id) {
    $query = "SELECT * FROM college_costs WHERE id = $cost_id";
    $result = mysqli_query($conn, $query);
    $cost = mysqli_fetch_assoc($result);
}

// Get all costs for listing
$costs = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $type_filter = $_GET['type'] ?? '';
    $year_filter = $_GET['year'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    
    $query = "SELECT * FROM college_costs WHERE 1=1";
    
    if ($search) {
        $query .= " AND (cost_name LIKE '%$search%' OR description LIKE '%$search%' OR course LIKE '%$search%')";
    }
    
    if ($type_filter) {
        $query .= " AND cost_type = '$type_filter'";
    }
    
    if ($year_filter) {
        $query .= " AND academic_year = '$year_filter'";
    }
    
    if ($status_filter) {
        $query .= " AND status = '$status_filter'";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $costs = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get cost statistics
$total_costs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM college_costs"))['count'];
$total_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM college_costs"))['total'];
$paid_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM college_costs WHERE status = 'paid'"))['total'];
$pending_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM college_costs WHERE status = 'pending'"))['total'];
$overdue_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM college_costs WHERE status = 'pending' AND due_date < CURDATE()"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cost Management - SDGD College</title>
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
                    <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="cost-management.php" class="active"><i class="fas fa-rupee-sign"></i> Cost Management</a></li>
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
                    <h1>Cost Management</h1>
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
                    <!-- Cost Statistics -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-rupee-sign"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo $total_costs; ?></h3>
                                <p>Total Costs</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3>₹<?php echo number_format($paid_amount, 2); ?></h3>
                                <p>Total Paid</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <h3>₹<?php echo number_format($pending_amount, 2); ?></h3>
                                <p>Pending</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-info">
                                <h3>₹<?php echo number_format($overdue_amount, 2); ?></h3>
                                <p>Overdue</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by name, description, or course..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="type">
                                    <option value="">All Types</option>
                                    <option value="tuition_fee" <?php echo ($_GET['type'] ?? '') === 'tuition_fee' ? 'selected' : ''; ?>>Tuition Fee</option>
                                    <option value="exam_fee" <?php echo ($_GET['type'] ?? '') === 'exam_fee' ? 'selected' : ''; ?>>Exam Fee</option>
                                    <option value="library_fee" <?php echo ($_GET['type'] ?? '') === 'library_fee' ? 'selected' : ''; ?>>Library Fee</option>
                                    <option value="lab_fee" <?php echo ($_GET['type'] ?? '') === 'lab_fee' ? 'selected' : ''; ?>>Lab Fee</option>
                                    <option value="hostel_fee" <?php echo ($_GET['type'] ?? '') === 'hostel_fee' ? 'selected' : ''; ?>>Hostel Fee</option>
                                    <option value="other" <?php echo ($_GET['type'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                                
                                <select name="year">
                                    <option value="">All Years</option>
                                    <?php
                                    $years = range(date('Y'), date('Y') - 5);
                                    foreach ($years as $year) {
                                        echo "<option value='$year'" . (($_GET['year'] ?? '') === $year ? 'selected' : '') . ">$year</option>";
                                    }
                                    ?>
                                </select>
                                
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="paid" <?php echo ($_GET['status'] ?? '') === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="overdue" <?php echo ($_GET['status'] ?? '') === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="cost-management.php" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="content-header">
                        <h2>All Costs</h2>
                        <a href="cost-management.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Cost
                        </a>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cost Name</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Course</th>
                                    <th>Academic Year</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($costs as $item): ?>
                                    <tr class="<?php echo $item['status']; ?>">
                                        <td>
                                            <strong><?php echo $item['cost_name']; ?></strong>
                                            <?php if ($item['description']): ?>
                                                <br><small><?php echo substr($item['description'], 0, 50); ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="cost-type cost-type-<?php echo $item['cost_type']; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $item['cost_type'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="amount">₹<?php echo number_format($item['amount'], 2); ?></span>
                                        </td>
                                        <td><?php echo $item['course']; ?></td>
                                        <td><?php echo $item['academic_year']; ?></td>
                                        <td>
                                            <?php echo format_date($item['due_date']); ?>
                                            <?php 
                                            $due_date = new DateTime($item['due_date']);
                                            $today = new DateTime();
                                            $days_left = $today->diff($due_date)->days;
                                            
                                            if ($days_left < 0) {
                                                echo '<span class="overdue-indicator">Overdue</span>';
                                            } elseif ($days_left <= 7) {
                                                echo '<span class="due-soon">Due Soon</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="cost-management.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="cost-management.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="cost-management.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this cost?')">
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
                        <h2><?php echo $action === 'add' ? 'Add New Cost' : 'Edit Cost'; ?></h2>
                        <a href="cost-management.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Costs
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cost_type">Cost Type *</label>
                                    <select id="cost_type" name="cost_type" required>
                                        <option value="">Select Type</option>
                                        <option value="tuition_fee" <?php echo ($cost['cost_type'] ?? '') === 'tuition_fee' ? 'selected' : ''; ?>>Tuition Fee</option>
                                        <option value="exam_fee" <?php echo ($cost['cost_type'] ?? '') === 'exam_fee' ? 'selected' : ''; ?>>Exam Fee</option>
                                        <option value="library_fee" <?php echo ($cost['cost_type'] ?? '') === 'library_fee' ? 'selected' : ''; ?>>Library Fee</option>
                                        <option value="lab_fee" <?php echo ($cost['cost_type'] ?? '') === 'lab_fee' ? 'selected' : ''; ?>>Lab Fee</option>
                                        <option value="hostel_fee" <?php echo ($cost['cost_type'] ?? '') === 'hostel_fee' ? 'selected' : ''; ?>>Hostel Fee</option>
                                        <option value="other" <?php echo ($cost['cost_type'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cost_name">Cost Name *</label>
                                    <input type="text" id="cost_name" name="cost_name" required 
                                           value="<?php echo $cost['cost_name'] ?? ''; ?>"
                                           placeholder="e.g., First Semester Tuition Fee">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="amount">Amount (₹) *</label>
                                    <input type="number" id="amount" name="amount" step="0.01" min="0" required 
                                           value="<?php echo $cost['amount'] ?? ''; ?>"
                                           placeholder="Enter amount">
                                </div>
                                
                                <div class="form-group">
                                    <label for="academic_year">Academic Year *</label>
                                    <select id="academic_year" name="academic_year" required>
                                        <option value="">Select Year</option>
                                        <?php
                                        $years = range(date('Y'), date('Y') + 2);
                                        foreach ($years as $year) {
                                            echo "<option value='$year'" . (($cost['academic_year'] ?? '') === $year ? 'selected' : '') . ">$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="semester">Semester</label>
                                    <select id="semester" name="semester">
                                        <option value="">Select Semester</option>
                                        <option value="1st" <?php echo ($cost['semester'] ?? '') === '1st' ? 'selected' : ''; ?>>1st Semester</option>
                                        <option value="2nd" <?php echo ($cost['semester'] ?? '') === '2nd' ? 'selected' : ''; ?>>2nd Semester</option>
                                        <option value="3rd" <?php echo ($cost['semester'] ?? '') === '3rd' ? 'selected' : ''; ?>>3rd Semester</option>
                                        <option value="4th" <?php echo ($cost['semester'] ?? '') === '4th' ? 'selected' : ''; ?>>4th Semester</option>
                                        <option value="5th" <?php echo ($cost['semester'] ?? '') === '5th' ? 'selected' : ''; ?>>5th Semester</option>
                                        <option value="6th" <?php echo ($cost['semester'] ?? '') === '6th' ? 'selected' : ''; ?>>6th Semester</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="course">Course</label>
                                    <select id="course" name="course">
                                        <option value="">All Courses</option>
                                        <option value="BA" <?php echo ($cost['course'] ?? '') === 'BA' ? 'selected' : ''; ?>>Bachelor of Arts</option>
                                        <option value="BSc" <?php echo ($cost['course'] ?? '') === 'BSc' ? 'selected' : ''; ?>>Bachelor of Science</option>
                                        <option value="BCom" <?php echo ($cost['course'] ?? '') === 'BCom' ? 'selected' : ''; ?>>Bachelor of Commerce</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select id="payment_method" name="payment_method">
                                    <option value="">Select Method</option>
                                    <option value="cash" <?php echo ($cost['payment_method'] ?? '') === 'cash' ? 'selected' : ''; ?>>Cash</option>
                                    <option value="cheque" <?php echo ($cost['payment_method'] ?? '') === 'cheque' ? 'selected' : ''; ?>>Cheque</option>
                                    <option value="dd" <?php echo ($cost['payment_method'] ?? '') === 'dd' ? 'selected' : ''; ?>>Demand Draft</option>
                                    <option value="online" <?php echo ($cost['payment_method'] ?? '') === 'online' ? 'selected' : ''; ?>>Online Payment</option>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" id="due_date" name="due_date" 
                                           value="<?php echo $cost['due_date'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select id="status" name="status" required>
                                        <option value="pending" <?php echo ($cost['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo ($cost['status'] ?? '') === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="overdue" <?php echo ($cost['status'] ?? '') === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4" 
                                          placeholder="Enter cost description..."><?php echo $cost['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Cost' : 'Update Cost'; ?>
                                </button>
                                <a href="cost-management.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $cost): ?>
                    <div class="content-header">
                        <h2>Cost Details</h2>
                        <a href="cost-management.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Costs
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="cost-details">
                            <div class="detail-section">
                                <h4>Basic Information</h4>
                                <p><strong>Cost Name:</strong> <?php echo $cost['cost_name']; ?></p>
                                <p><strong>Type:</strong> <?php echo ucfirst(str_replace('_', ' ', $cost['cost_type'])); ?></p>
                                <p><strong>Amount:</strong> ₹<?php echo number_format($cost['amount'], 2); ?></p>
                                <p><strong>Status:</strong> <?php echo ucfirst($cost['status']); ?></p>
                            </div>
                            
                            <div class="detail-section">
                                <h4>Academic Information</h4>
                                <p><strong>Course:</strong> <?php echo $cost['course']; ?></p>
                                <p><strong>Semester:</strong> <?php echo $cost['semester']; ?></p>
                                <p><strong>Academic Year:</strong> <?php echo $cost['academic_year']; ?></p>
                                <p><strong>Due Date:</strong> <?php echo format_date($cost['due_date']); ?></p>
                            </div>
                            
                            <?php if ($cost['description']): ?>
                                <div class="detail-section">
                                    <h4>Description</h4>
                                    <p><?php echo nl2br($cost['description']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="detail-section">
                                <h4>Payment Information</h4>
                                <p><strong>Payment Method:</strong> <?php echo ucfirst($cost['payment_method']); ?></p>
                                <p><strong>Created Date:</strong> <?php echo format_date($cost['created_at'], 'd/m/Y H:i'); ?></p>
                                <p><strong>Last Updated:</strong> <?php echo format_date($cost['updated_at'], 'd/m/Y H:i'); ?></p>
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
        
        .amount {
            font-weight: bold;
            color: #27ae60;
            font-size: 16px;
        }
        
        .cost-type {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .cost-type-tuition_fee { background: #3498db; color: white; }
        .cost-type-exam_fee { background: #e74c3c; color: white; }
        .cost-type-library_fee { background: #f39c12; color: white; }
        .cost-type-lab_fee { background: #9b59b6; color: white; }
        .cost-type-hostel_fee { background: #1abc9c; color: white; }
        .cost-type-other { background: #95a5a6; color: white; }
        
        .overdue-indicator {
            background: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .due-soon {
            background: #f39c12;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-pending { background: #f39c12; color: white; }
        .status-paid { background: #27ae60; color: white; }
        .status-overdue { background: #e74c3c; color: white; }
        
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
        
        .view-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .cost-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .detail-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
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
            line-height: 1.6;
        }
        
        .detail-section strong {
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .content-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .cost-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
