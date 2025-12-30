<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$tender_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Tender
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $tender_type = sanitize_input($_POST['tender_type']);
        $last_date = $_POST['last_date'];
        $status = sanitize_input($_POST['status']);
        
        // Handle file upload
        $file_path = '';
        $file_name = '';
        if (isset($_FILES['tender_file']) && $_FILES['tender_file']['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_file($_FILES['tender_file'], '../uploads/tenders/');
            if ($upload_result['success']) {
                $file_path = $upload_result['path'];
                $file_name = $_FILES['tender_file']['name'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                $query = "INSERT INTO tenders (title, description, file_path, file_name, tender_type, last_date, status, created_by) 
                          VALUES ('$title', '$description', '$file_path', '$file_name', '$tender_type', '$last_date', '$status', " . $_SESSION['admin_id'] . ")";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'add_tender', "Added tender: $title");
                    $message = 'Tender added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add tender: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit' && $tender_id) {
                $query = "UPDATE tenders SET title='$title', description='$description', tender_type='$tender_type', last_date='$last_date', status='$status'";
                
                if ($file_path) {
                    $query .= ", file_path='$file_path', file_name='$file_name'";
                }
                
                $query .= " WHERE id = $tender_id";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'edit_tender', "Updated tender: $title");
                    $message = 'Tender updated successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to update tender: ' . mysqli_error($conn);
                }
            }
        }
    }
}

// Delete Tender
if ($action === 'delete' && $tender_id) {
    $query = "DELETE FROM tenders WHERE id = $tender_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_tender', "Deleted tender ID: $tender_id");
        $message = 'Tender deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete tender: ' . mysqli_error($conn);
    }
}

// Get tender for editing
$tender = null;
if (($action === 'edit' || $action === 'view') && $tender_id) {
    $query = "SELECT t.*, au.full_name as created_by_name 
              FROM tenders t 
              LEFT JOIN admin_users au ON t.created_by = au.id 
              WHERE t.id = $tender_id";
    $result = mysqli_query($conn, $query);
    $tender = mysqli_fetch_assoc($result);
}

// Get all tenders for listing
$tenders = [];
if ($action === 'list') {
    $query = "SELECT t.*, au.full_name as created_by_name 
              FROM tenders t 
              LEFT JOIN admin_users au ON t.created_by = au.id 
              ORDER BY t.created_at DESC";
    $result = mysqli_query($conn, $query);
    $tenders = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenders Management - SDGD College</title>
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
                    <li><a href="tenders.php" class="active"><i class="fas fa-file-contract"></i> Tenders</a></li>
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
                    <h1>Tenders Management</h1>
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
                        <h2>All Tenders</h2>
                        <a href="tenders.php?action=add" class="btn">
                            <i class="fas fa-plus"></i> Add New Tender
                        </a>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Last Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tenders as $item): ?>
                                    <tr>
                                        <td><?php echo $item['title']; ?></td>
                                        <td><?php echo $item['tender_type'] ?: 'General'; ?></td>
                                        <td>
                                            <?php 
                                            $last_date = new DateTime($item['last_date']);
                                            $today = new DateTime();
                                            $days_left = $today->diff($last_date)->format('%r%a');
                                            
                                            if ($days_left < 0) {
                                                echo '<span style="color: #e74c3c;">' . format_date($item['last_date']) . ' (Expired)</span>';
                                            } elseif ($days_left <= 7) {
                                                echo '<span style="color: #f39c12;">' . format_date($item['last_date']) . ' (' . $days_left . ' days left)</span>';
                                            } else {
                                                echo format_date($item['last_date']);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $item['created_by_name'] ?: 'System'; ?></td>
                                        <td><?php echo format_date($item['created_at'], 'd/m/Y'); ?></td>
                                        <td>
                                            <a href="tenders.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="tenders.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="tenders.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this tender?')">
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
                        <h2><?php echo $action === 'add' ? 'Add New Tender' : 'Edit Tender'; ?></h2>
                        <a href="tenders.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Tenders
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <label for="title">Tender Title *</label>
                                <input type="text" id="title" name="title" required 
                                       value="<?php echo $tender['title'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="5"><?php echo $tender['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tender_type">Tender Type</label>
                                    <input type="text" id="tender_type" name="tender_type" 
                                           placeholder="e.g., Construction, Supplies, Services"
                                           value="<?php echo $tender['tender_type'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_date">Last Date for Submission *</label>
                                    <input type="date" id="last_date" name="last_date" required 
                                           value="<?php echo $tender['last_date'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status">
                                    <option value="active" <?php echo ($tender['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="closed" <?php echo ($tender['status'] ?? '') === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                    <option value="cancelled" <?php echo ($tender['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="tender_file">Tender Document (PDF, DOC, DOCX)</label>
                                <input type="file" id="tender_file" name="tender_file" accept=".pdf,.doc,.docx">
                                <?php if ($tender && $tender['file_name']): ?>
                                    <p class="file-info">Current file: <?php echo $tender['file_name']; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Tender' : 'Update Tender'; ?>
                                </button>
                                <a href="tenders.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $tender): ?>
                    <div class="content-header">
                        <h2>Tender Details</h2>
                        <a href="tenders.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Tenders
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <h3><?php echo $tender['title']; ?></h3>
                            <div class="view-meta">
                                <span class="status status-<?php echo $tender['status']; ?>">
                                    <?php echo ucfirst($tender['status']); ?>
                                </span>
                                <?php if ($tender['tender_type']): ?>
                                    <span class="type"><?php echo $tender['tender_type']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <?php if ($tender['description']): ?>
                                <div class="description">
                                    <h4>Description</h4>
                                    <p><?php echo nl2br($tender['description']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="details">
                                <h4>Tender Details</h4>
                                <p><strong>Last Date for Submission:</strong> <?php echo format_date($tender['last_date']); ?></p>
                                <p><strong>Status:</strong> <?php echo ucfirst($tender['status']); ?></p>
                                <p><strong>Created By:</strong> <?php echo $tender['created_by_name'] ?: 'System'; ?></p>
                                <p><strong>Created Date:</strong> <?php echo format_date($tender['created_at'], 'd/m/Y H:i'); ?></p>
                                
                                <?php 
                                $last_date = new DateTime($tender['last_date']);
                                $today = new DateTime();
                                $days_left = $today->diff($last_date)->format('%r%a');
                                
                                if ($days_left < 0) {
                                    echo '<p><strong>Status:</strong> <span style="color: #e74c3c; font-weight: bold;">EXPIRED</span></p>';
                                } elseif ($days_left <= 7) {
                                    echo '<p><strong>Days Left:</strong> <span style="color: #f39c12; font-weight: bold;">' . $days_left . ' days</span></p>';
                                } else {
                                    echo '<p><strong>Days Left:</strong> ' . $days_left . ' days</p>';
                                }
                                ?>
                            </div>
                            
                            <?php if ($tender['file_name']): ?>
                                <div class="attachment">
                                    <h4>Tender Document</h4>
                                    <a href="<?php echo $tender['file_path']; ?>" target="_blank" class="btn">
                                        <i class="fas fa-download"></i> Download <?php echo $tender['file_name']; ?>
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
        
        // Set minimum date for last date field (today)
        document.addEventListener('DOMContentLoaded', function() {
            const lastDateField = document.getElementById('last_date');
            if (lastDateField) {
                const today = new Date().toISOString().split('T')[0];
                lastDateField.min = today;
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
        .status-closed { background: #95a5a6; color: white; }
        .status-cancelled { background: #e74c3c; color: white; }
        
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
