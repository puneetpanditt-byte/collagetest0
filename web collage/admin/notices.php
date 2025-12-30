<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$notice_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Notice
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $notice_type = sanitize_input($_POST['notice_type']);
        $priority = sanitize_input($_POST['priority']);
        $target_audience = sanitize_input($_POST['target_audience']);
        $publish_date = $_POST['publish_date'];
        $expiry_date = $_POST['expiry_date'] ?: null;
        
        // Handle file upload
        $file_path = '';
        $file_name = '';
        if (isset($_FILES['notice_file']) && $_FILES['notice_file']['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_file($_FILES['notice_file'], '../uploads/notices/');
            if ($upload_result['success']) {
                $file_path = $upload_result['path'];
                $file_name = $_FILES['notice_file']['name'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                $query = "INSERT INTO notices (title, description, file_path, file_name, notice_type, priority, target_audience, publish_date, expiry_date, created_by) 
                          VALUES ('$title', '$description', '$file_path', '$file_name', '$notice_type', '$priority', '$target_audience', '$publish_date', '$expiry_date', " . $_SESSION['admin_id'] . ")";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'add_notice', "Added notice: $title");
                    $message = 'Notice added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add notice: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit' && $notice_id) {
                $query = "UPDATE notices SET title='$title', description='$description', notice_type='$notice_type', priority='$priority', target_audience='$target_audience', publish_date='$publish_date', expiry_date='$expiry_date'";
                
                if ($file_path) {
                    $query .= ", file_path='$file_path', file_name='$file_name'";
                }
                
                $query .= " WHERE id = $notice_id";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'edit_notice', "Updated notice: $title");
                    $message = 'Notice updated successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to update notice: ' . mysqli_error($conn);
                }
            }
        }
    }
}

// Delete Notice
if ($action === 'delete' && $notice_id) {
    $query = "DELETE FROM notices WHERE id = $notice_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_notice', "Deleted notice ID: $notice_id");
        $message = 'Notice deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete notice: ' . mysqli_error($conn);
    }
}

// Get notice for editing
$notice = null;
if (($action === 'edit' || $action === 'view') && $notice_id) {
    $query = "SELECT * FROM notices WHERE id = $notice_id";
    $result = mysqli_query($conn, $query);
    $notice = mysqli_fetch_assoc($result);
}

// Get all notices for listing
$notices = [];
if ($action === 'list') {
    $query = "SELECT n.*, au.full_name as created_by_name 
              FROM notices n 
              LEFT JOIN admin_users au ON n.created_by = au.id 
              ORDER BY n.created_at DESC";
    $result = mysqli_query($conn, $query);
    $notices = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices Management - SDGD College</title>
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
                    <li><a href="notices.php" class="active"><i class="fas fa-bullhorn"></i> Notices</a></li>
                    <li><a href="tenders.php"><i class="fas fa-file-contract"></i> Tenders</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
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
                    <h1>Notices Management</h1>
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
                        <h2>All Notices</h2>
                        <a href="notices.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Notice
                        </a>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Publish Date</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notices as $item): ?>
                                    <tr>
                                        <td><?php echo $item['title']; ?></td>
                                        <td><?php echo ucfirst($item['notice_type']); ?></td>
                                        <td>
                                            <span class="priority priority-<?php echo $item['priority']; ?>">
                                                <?php echo ucfirst($item['priority']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo format_date($item['publish_date']); ?></td>
                                        <td><?php echo $item['created_by_name'] ?: 'System'; ?></td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="notices.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="notices.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="notices.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this notice?')">
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
                        <h2><?php echo $action === 'add' ? 'Add New Notice' : 'Edit Notice'; ?></h2>
                        <a href="notices.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Notices
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <label for="title">Notice Title *</label>
                                <input type="text" id="title" name="title" required 
                                       value="<?php echo $notice['title'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="5"><?php echo $notice['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="notice_type">Notice Type</label>
                                    <select id="notice_type" name="notice_type">
                                        <option value="general" <?php echo ($notice['notice_type'] ?? '') === 'general' ? 'selected' : ''; ?>>General</option>
                                        <option value="examination" <?php echo ($notice['notice_type'] ?? '') === 'examination' ? 'selected' : ''; ?>>Examination</option>
                                        <option value="admission" <?php echo ($notice['notice_type'] ?? '') === 'admission' ? 'selected' : ''; ?>>Admission</option>
                                        <option value="holiday" <?php echo ($notice['notice_type'] ?? '') === 'holiday' ? 'selected' : ''; ?>>Holiday</option>
                                        <option value="important" <?php echo ($notice['notice_type'] ?? '') === 'important' ? 'selected' : ''; ?>>Important</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select id="priority" name="priority">
                                        <option value="low" <?php echo ($notice['priority'] ?? '') === 'low' ? 'selected' : ''; ?>>Low</option>
                                        <option value="medium" <?php echo ($notice['priority'] ?? '') === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                        <option value="high" <?php echo ($notice['priority'] ?? '') === 'high' ? 'selected' : ''; ?>>High</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="target_audience">Target Audience</label>
                                    <select id="target_audience" name="target_audience">
                                        <option value="all" <?php echo ($notice['target_audience'] ?? '') === 'all' ? 'selected' : ''; ?>>All</option>
                                        <option value="students" <?php echo ($notice['target_audience'] ?? '') === 'students' ? 'selected' : ''; ?>>Students</option>
                                        <option value="teachers" <?php echo ($notice['target_audience'] ?? '') === 'teachers' ? 'selected' : ''; ?>>Teachers</option>
                                        <option value="staff" <?php echo ($notice['target_audience'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="publish_date">Publish Date *</label>
                                    <input type="date" id="publish_date" name="publish_date" required 
                                           value="<?php echo $notice['publish_date'] ?? date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" 
                                       value="<?php echo $notice['expiry_date'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="notice_file">Attachment (PDF, DOC, JPG, PNG)</label>
                                <input type="file" id="notice_file" name="notice_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <?php if ($notice && $notice['file_name']): ?>
                                    <p class="file-info">Current file: <?php echo $notice['file_name']; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Notice' : 'Update Notice'; ?>
                                </button>
                                <a href="notices.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $notice): ?>
                    <div class="content-header">
                        <h2>Notice Details</h2>
                        <a href="notices.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Notices
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <h3><?php echo $notice['title']; ?></h3>
                            <div class="view-meta">
                                <span class="priority priority-<?php echo $notice['priority']; ?>">
                                    <?php echo ucfirst($notice['priority']); ?> Priority
                                </span>
                                <span class="type"><?php echo ucfirst($notice['notice_type']); ?></span>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <?php if ($notice['description']): ?>
                                <div class="description">
                                    <h4>Description</h4>
                                    <p><?php echo nl2br($notice['description']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="details">
                                <h4>Details</h4>
                                <p><strong>Publish Date:</strong> <?php echo format_date($notice['publish_date']); ?></p>
                                <?php if ($notice['expiry_date']): ?>
                                    <p><strong>Expiry Date:</strong> <?php echo format_date($notice['expiry_date']); ?></p>
                                <?php endif; ?>
                                <p><strong>Target Audience:</strong> <?php echo ucfirst($notice['target_audience']); ?></p>
                                <p><strong>Status:</strong> <?php echo ucfirst($notice['status']); ?></p>
                                <p><strong>Created:</strong> <?php echo format_date($notice['created_at'], 'd/m/Y H:i'); ?></p>
                            </div>
                            
                            <?php if ($notice['file_name']): ?>
                                <div class="attachment">
                                    <h4>Attachment</h4>
                                    <a href="<?php echo $notice['file_path']; ?>" target="_blank" class="btn">
                                        <i class="fas fa-download"></i> Download <?php echo $notice['file_name']; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
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
        
        .view-meta {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .type {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .view-content h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .view-content .description,
        .view-content .details,
        .view-content .attachment {
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .content-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</body>
</html>
