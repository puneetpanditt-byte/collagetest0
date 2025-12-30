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

// Mark as read
if ($action === 'mark_read' && $message_id) {
    $query = "UPDATE contact_messages SET status = 'read' WHERE id = $message_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'mark_read', "Marked message ID $message_id as read");
        $message = 'Message marked as read!';
    } else {
        $error = 'Failed to update message: ' . mysqli_error($conn);
    }
    $action = 'list';
}

// Mark as replied
if ($action === 'mark_replied' && $message_id) {
    $query = "UPDATE contact_messages SET status = 'replied' WHERE id = $message_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'mark_replied', "Marked message ID $message_id as replied");
        $message = 'Message marked as replied!';
    } else {
        $error = 'Failed to update message: ' . mysqli_error($conn);
    }
    $action = 'list';
}

// Delete message
if ($action === 'delete' && $message_id) {
    $query = "DELETE FROM contact_messages WHERE id = $message_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'delete_message', "Deleted message ID $message_id");
        $message = 'Message deleted successfully!';
    } else {
        $error = 'Failed to delete message: ' . mysqli_error($conn);
    }
    $action = 'list';
}

// Get message for viewing
$contact_message = null;
if (($action === 'view' || $action === 'reply') && $message_id) {
    $query = "SELECT * FROM contact_messages WHERE id = $message_id";
    $result = mysqli_query($conn, $query);
    $contact_message = mysqli_fetch_assoc($result);
    
    // Mark as read when viewing
    if ($contact_message && $contact_message['status'] === 'unread') {
        mysqli_query($conn, "UPDATE contact_messages SET status = 'read' WHERE id = $message_id");
    }
}

// Get all messages for listing
$messages = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    
    $query = "SELECT * FROM contact_messages WHERE 1=1";
    
    if ($search) {
        $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%')";
    }
    
    if ($status_filter) {
        $query .= " AND status = '$status_filter'";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get message counts
$unread_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'"))['count'];
$read_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'read'"))['count'];
$replied_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'replied'"))['count'];
$total_count = $unread_count + $read_count + $replied_count;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Management - SDGD College</title>
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
                    <li><a href="contact-management.php" class="active"><i class="fas fa-phone"></i> Contact Management</a></li>
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
                    <h1>Contact Management</h1>
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
                    <!-- Message Statistics -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo $total_count; ?></h3>
                                <p>Total Messages</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo $unread_count; ?></h3>
                                <p>Unread</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-envelope-open"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo $read_count; ?></h3>
                                <p>Read</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-reply"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo $replied_count; ?></h3>
                                <p>Replied</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by name, email, or subject..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="unread" <?php echo ($_GET['status'] ?? '') === 'unread' ? 'selected' : ''; ?>>Unread</option>
                                    <option value="read" <?php echo ($_GET['status'] ?? '') === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="replied" <?php echo ($_GET['status'] ?? '') === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="contact-management.php" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $item): ?>
                                    <tr class="<?php echo $item['status']; ?>">
                                        <td>
                                            <?php echo $item['name']; ?>
                                            <?php if ($item['status'] === 'unread'): ?>
                                                <span class="unread-indicator">●</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $item['email']; ?></td>
                                        <td><?php echo $item['subject']; ?></td>
                                        <td><?php echo format_date($item['created_at'], 'd/m/Y H:i'); ?></td>
                                        <td>
                                            <span class="status status-<?php echo $item['status']; ?>">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="contact-management.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($item['status'] === 'unread'): ?>
                                                <a href="contact-management.php?action=mark_read&id=<?php echo $item['id']; ?>" class="btn-edit" title="Mark as Read">
                                                    <i class="fas fa-envelope-open"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($item['status'] === 'read'): ?>
                                                <a href="contact-management.php?action=mark_replied&id=<?php echo $item['id']; ?>" class="btn-edit" title="Mark as Replied">
                                                    <i class="fas fa-reply"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="mailto:<?php echo $item['email']; ?>?subject=Re: <?php echo urlencode($item['subject']); ?>" class="btn-edit" title="Reply via Email">
                                                <i class="fas fa-paper-plane"></i>
                                            </a>
                                            <a href="contact-management.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this message?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php elseif ($action === 'view' && $contact_message): ?>
                    <div class="content-header">
                        <h2>Contact Message Details</h2>
                        <a href="contact-management.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="message-header">
                            <div class="message-from">
                                <h3><?php echo $contact_message['subject']; ?></h3>
                                <div class="sender-info">
                                    <p><strong>From:</strong> <?php echo $contact_message['name']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $contact_message['email']; ?></p>
                                    <?php if ($contact_message['phone']): ?>
                                        <p><strong>Phone:</strong> <?php echo $contact_message['phone']; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="message-meta">
                                <span class="status status-<?php echo $contact_message['status']; ?>">
                                    <?php echo ucfirst($contact_message['status']); ?>
                                </span>
                                <span class="date"><?php echo format_date($contact_message['created_at'], 'd/m/Y H:i'); ?></span>
                            </div>
                        </div>
                        
                        <div class="message-content">
                            <h4>Message</h4>
                            <div class="message-text">
                                <?php echo nl2br($contact_message['message']); ?>
                            </div>
                        </div>
                        
                        <div class="message-actions">
                            <?php if ($contact_message['status'] === 'unread'): ?>
                                <a href="contact-management.php?action=mark_read&id=<?php echo $contact_message['id']; ?>" class="btn">
                                    <i class="fas fa-envelope-open"></i> Mark as Read
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($contact_message['status'] === 'read'): ?>
                                <a href="contact-management.php?action=mark_replied&id=<?php echo $contact_message['id']; ?>" class="btn">
                                    <i class="fas fa-reply"></i> Mark as Replied
                                </a>
                            <?php endif; ?>
                            
                            <a href="mailto:<?php echo $contact_message['email']; ?>?subject=Re: <?php echo urlencode($contact_message['subject']); ?>" class="btn">
                                <i class="fas fa-paper-plane"></i> Reply via Email
                            </a>
                            
                            <a href="contact-management.php?action=delete&id=<?php echo $contact_message['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
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
        
        .unread-indicator {
            color: #e74c3c;
            font-weight: bold;
            margin-left: 5px;
        }
        
        tr.unread {
            background: #fff3cd;
            font-weight: 500;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-unread { background: #e74c3c; color: white; }
        .status-read { background: #3498db; color: white; }
        .status-replied { background: #27ae60; color: white; }
        
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
        
        .message-header {
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .message-from {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .message-from h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
        }
        
        .sender-info p {
            margin: 5px 0;
            color: #5a6c7d;
        }
        
        .sender-info strong {
            color: #2c3e50;
        }
        
        .message-meta {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .message-content h4 {
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
        
        .message-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
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
            
            .message-from {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</body>
</html>
