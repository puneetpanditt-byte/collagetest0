<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$message_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Principal Message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $title = sanitize_input($_POST['title']);
        $message_content = sanitize_input($_POST['message']);
        $message_type = sanitize_input($_POST['message_type']);
        $status = sanitize_input($_POST['status']);
        
        if (empty($error)) {
            if ($action === 'add') {
                $query = "INSERT INTO principal_messages (title, message, message_type, status, created_by) 
                          VALUES ('$title', '$message_content', '$message_type', '$status', " . $_SESSION['admin_id'] . ")";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'add_principal_message', "Added principal message: $title");
                    $message = 'Principal message added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add message: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit' && $message_id) {
                $query = "UPDATE principal_messages SET title='$title', message='$message_content', message_type='$message_type', status='$status', updated_by=" . $_SESSION['admin_id'] . ", updated_at=NOW() WHERE id = $message_id";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'edit_principal_message', "Updated principal message: $title");
                    $message = 'Principal message updated successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to update message: ' . mysqli_error($conn);
                }
            }
        }
    }
}

// Delete Message
if ($action === 'delete' && $message_id) {
    $query = "DELETE FROM principal_messages WHERE id = $message_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_principal_message', "Deleted principal message ID: $message_id");
        $message = 'Principal message deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete message: ' . mysqli_error($conn);
    }
}

// Get message for editing
$principal_message = null;
if (($action === 'edit' || $action === 'view') && $message_id) {
    $query = "SELECT * FROM principal_messages WHERE id = $message_id";
    $result = mysqli_query($conn, $query);
    $principal_message = mysqli_fetch_assoc($result);
}

// Get all messages for listing
$messages = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    $type_filter = $_GET['type'] ?? '';
    
    $query = "SELECT * FROM principal_messages WHERE 1=1";
    
    if ($search) {
        $query .= " AND (title LIKE '%$search%' OR message LIKE '%$search%')";
    }
    
    if ($status_filter) {
        $query .= " AND status = '$status_filter'";
    }
    
    if ($type_filter) {
        $query .= " AND message_type = '$type_filter'";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal Messages Management - SDGD College</title>
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
                    <li><a href="principal-messages.php" class="active"><i class="fas fa-user-tie"></i> Principal Messages</a></li>
                    <li><a href="notices.php"><i class="fas fa-bullhorn"></i> Notices</a></li>
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
                    <h1>Principal Messages Management</h1>
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
                        <h2>All Principal Messages</h2>
                        <a href="principal-messages.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Message
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by title or message..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($_GET['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                
                                <select name="type">
                                    <option value="">All Types</option>
                                    <option value="general" <?php echo ($_GET['type'] ?? '') === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="students" <?php echo ($_GET['type'] ?? '') === 'students' ? 'selected' : ''; ?>>Students</option>
                                    <option value="staff" <?php echo ($_GET['type'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    <option value="parents" <?php echo ($_GET['type'] ?? '') === 'parents' ? 'selected' : ''; ?>>Parents</option>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="principal-messages.php" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Message Type</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $item['title']; ?></strong>
                                            <?php if (strlen($item['message']) > 100): ?>
                                                <br><small><?php echo substr($item['message'], 0, 100) . '...'; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="message-type"><?php echo ucfirst($item['message_type']); ?></span>
                                        </td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo format_date($item['created_at'], 'd/m/Y H:i'); ?></td>
                                        <td><?php echo format_date($item['updated_at'], 'd/m/Y H:i'); ?></td>
                                        <td>
                                            <a href="principal-messages.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="principal-messages.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="principal-messages.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this message?')">
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
                        <h2><?php echo $action === 'add' ? 'Add New Principal Message' : 'Edit Principal Message'; ?></h2>
                        <a href="principal-messages.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <label for="title">Message Title *</label>
                                <input type="text" id="title" name="title" required 
                                       value="<?php echo $principal_message['title'] ?? ''; ?>"
                                       placeholder="Enter message title...">
                            </div>
                            
                            <div class="form-group">
                                <label for="message_type">Message Type *</label>
                                <select id="message_type" name="message_type" required>
                                    <option value="">Select Message Type</option>
                                    <option value="general" <?php echo ($principal_message['message_type'] ?? '') === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="students" <?php echo ($principal_message['message_type'] ?? '') === 'students' ? 'selected' : ''; ?>>Students</option>
                                    <option value="staff" <?php echo ($principal_message['message_type'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    <option value="parents" <?php echo ($principal_message['message_type'] ?? '') === 'parents' ? 'selected' : ''; ?>>Parents</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message Content *</label>
                                <textarea id="message" name="message" rows="10" required 
                                          placeholder="Write the principal's message here..."><?php echo $principal_message['message'] ?? ''; ?></textarea>
                                <small>You can use HTML tags for formatting (e.g., &lt;b&gt;bold&lt;/b&gt;, &lt;i&gt;italic&lt;/i&gt;, &lt;br&gt; for line breaks)</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select id="status" name="status" required>
                                    <option value="active" <?php echo ($principal_message['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($principal_message['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Message' : 'Update Message'; ?>
                                </button>
                                <a href="principal-messages.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $principal_message): ?>
                    <div class="content-header">
                        <h2>Principal Message Details</h2>
                        <a href="principal-messages.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <h3><?php echo $principal_message['title']; ?></h3>
                            <div class="view-meta">
                                <span class="message-type"><?php echo ucfirst($principal_message['message_type']); ?></span>
                                <span class="status status-<?php echo $principal_message['status']; ?>">
                                    <?php echo ucfirst($principal_message['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <div class="message-preview">
                                <h4>Message Content</h4>
                                <div class="message-text">
                                    <?php echo nl2br($principal_message['message']); ?>
                                </div>
                            </div>
                            
                            <div class="message-details">
                                <h4>Message Information</h4>
                                <p><strong>Message Type:</strong> <?php echo ucfirst($principal_message['message_type']); ?></p>
                                <p><strong>Status:</strong> <?php echo ucfirst($principal_message['status']); ?></p>
                                <p><strong>Created Date:</strong> <?php echo format_date($principal_message['created_at'], 'd/m/Y H:i'); ?></p>
                                <p><strong>Last Updated:</strong> <?php echo format_date($principal_message['updated_at'], 'd/m/Y H:i'); ?></p>
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
        
        .message-type {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
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
        .status-inactive { background: #95a5a6; color: white; }
        
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
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
        
        .message-preview {
            margin-bottom: 30px;
        }
        
        .message-preview h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .message-text {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #3498db;
            line-height: 1.6;
        }
        
        .message-details h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .message-details p {
            margin: 8px 0;
            color: #5a6c7d;
        }
        
        .message-details strong {
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
        }
    </style>
</body>
</html>
