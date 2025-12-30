<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$message_id = $_GET['id'] ?? null;
$success = '';
$error = '';

// Compose and send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'compose') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $message_type = sanitize_input($_POST['message_type']);
        $recipient_email = sanitize_input($_POST['recipient_email']);
        $recipient_phone = sanitize_input($_POST['recipient_phone']);
        $subject = sanitize_input($_POST['subject']);
        $message_content = sanitize_input($_POST['message']);
        
        if ($message_type === 'email') {
            // Email validation and sending
            if (empty($recipient_email) || empty($subject) || empty($message_content)) {
                $error = 'All email fields are required!';
            } elseif (!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address!';
            } else {
                $headers = "From: " . SITE_EMAIL . "\r\n";
                $headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                
                $email_body = "
                    <html>
                    <body>
                        <h2>Message from SDGD College</h2>
                        <p><strong>Subject:</strong> $subject</p>
                        <p><strong>Message:</strong></p>
                        <p>" . nl2br($message_content) . "</p>
                        <hr>
                        <p><em>This message was sent from SDGD College Admin Panel</em></p>
                    </body>
                    </html>
                ";
                
                if (mail($recipient_email, $subject, $email_body, $headers)) {
                    $query = "INSERT INTO sent_messages (recipient_email, subject, message, sent_by) 
                              VALUES ('$recipient_email', '$subject', '$message_content', " . $_SESSION['admin_id'] . ")";
                    mysqli_query($conn, $query);
                    
                    log_activity($_SESSION['admin_id'], 'send_email', "Sent email to: $recipient_email");
                    $success = 'Email sent successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to send email. Please try again.';
                }
            }
        } elseif ($message_type === 'sms') {
            // SMS validation and sending
            if (empty($recipient_phone) || empty($message_content)) {
                $error = 'Phone number and message are required!';
            } elseif (!preg_match('/^[0-9]{10}$/', $recipient_phone)) {
                $error = 'Invalid phone number! Please enter 10-digit number.';
            } else {
                // SMS API integration (you can implement actual SMS API here)
                $sms_api_key = 'YOUR_SMS_API_KEY'; // Configure this
                $sms_sender_id = 'SDGDCOL';
                $sms_url = "https://api.smsprovider.com/send?apikey=$sms_api_key&sender=$sms_sender_id&to=$recipient_phone&message=" . urlencode($message_content);
                
                // For demo purposes, we'll simulate SMS sending
                $sms_sent = true; // In production, use actual API call: file_get_contents($sms_url);
                
                if ($sms_sent) {
                    $query = "INSERT INTO sent_messages (recipient_email, subject, message, sent_by) 
                              VALUES ('$recipient_phone', 'SMS', '$message_content', " . $_SESSION['admin_id'] . ")";
                    mysqli_query($conn, $query);
                    
                    log_activity($_SESSION['admin_id'], 'send_sms', "Sent SMS to: $recipient_phone");
                    $success = 'SMS sent successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to send SMS. Please check API configuration.';
                }
            }
        } else {
            $error = 'Please select message type!';
        }
    }
}

// Mark as read
if ($action === 'mark_read' && $message_id) {
    $query = "UPDATE contact_messages SET status = 'read' WHERE id = $message_id";
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], 'mark_read', "Marked message ID $message_id as read");
        $success = 'Message marked as read!';
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
        $success = 'Message marked as replied!';
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
        $success = 'Message deleted successfully!';
    } else {
        $error = 'Failed to delete message: ' . mysqli_error($conn);
    }
    $action = 'list';
}

// Get message for viewing
$message = null;
if ($action === 'view' && $message_id) {
    $query = "SELECT * FROM contact_messages WHERE id = $message_id";
    $result = mysqli_query($conn, $query);
    $message = mysqli_fetch_assoc($result);
    
    // Mark as read when viewing
    if ($message && $message['status'] === 'unread') {
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
    <title>Messages Management - SDGD College</title>
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
                    <li><a href="messages.php" class="active"><i class="fas fa-envelope"></i> Messages <?php if ($unread_count > 0): ?><span class="badge"><?php echo $unread_count; ?></span><?php endif; ?></a></li>
                    <li><a href="contact-management.php"><i class="fas fa-phone"></i> Contact Management</a></li>
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
                    <h1>Messages Management</h1>
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
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
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
                                <a href="messages.php" class="btn btn-danger">Clear</a>
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
                                            <a href="messages.php?action=view&id=<?php echo $item['id']; ?>" class="btn-edit" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($item['status'] === 'unread'): ?>
                                                <a href="messages.php?action=mark_read&id=<?php echo $item['id']; ?>" class="btn-edit" title="Mark as Read">
                                                    <i class="fas fa-envelope-open"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($item['status'] === 'read'): ?>
                                                <a href="messages.php?action=mark_replied&id=<?php echo $item['id']; ?>" class="btn-edit" title="Mark as Replied">
                                                    <i class="fas fa-reply"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="messages.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this message?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php elseif ($action === 'compose'): ?>
                    <div class="content-header">
                        <h2>Compose Message</h2>
                        <div class="header-actions">
                            <a href="messages.php" class="btn">
                                <i class="fas fa-arrow-left"></i> Back to Messages
                            </a>
                        </div>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" id="composeForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <label for="message_type">Message Type *</label>
                                <select id="message_type" name="message_type" required onchange="toggleMessageFields()">
                                    <option value="">Select Message Type</option>
                                    <option value="email">Email Message</option>
                                    <option value="sms">SMS Message</option>
                                </select>
                            </div>
                            
                            <!-- Email Fields -->
                            <div id="emailFields" class="message-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="recipient_email">Recipient Email *</label>
                                    <input type="email" id="recipient_email" name="recipient_email" 
                                           placeholder="Enter recipient's email address...">
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Subject *</label>
                                    <input type="text" id="subject" name="subject" 
                                           placeholder="Enter message subject...">
                                </div>
                            </div>
                            
                            <!-- SMS Fields -->
                            <div id="smsFields" class="message-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="recipient_phone">Recipient Phone Number *</label>
                                    <input type="tel" id="recipient_phone" name="recipient_phone" 
                                           placeholder="Enter 10-digit mobile number..." maxlength="10">
                                    <small>Format: 10-digit mobile number (e.g., 9876543210)</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>SMS Character Count</label>
                                    <div class="char-counter">
                                        <span id="charCount">0</span> / 160 characters
                                        <small id="charWarning" style="color: #e74c3c; display: none;">Message exceeds 160 characters!</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" rows="10" required 
                                          placeholder="Write your message here..." oninput="updateCharCount()"></textarea>
                                <small id="emailHint">You can use HTML tags for formatting (e.g., &lt;b&gt;bold&lt;/b&gt;, &lt;i&gt;italic&lt;/i&gt;, &lt;br&gt; for line breaks)</small>
                                <small id="smsHint" style="display: none;">SMS supports plain text only. Maximum 160 characters per message.</small>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                                <a href="messages.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $message): ?>
                    <div class="content-header">
                        <h2>Message Details</h2>
                        <div class="header-actions">
                            <a href="messages.php?action=compose" class="btn">
                                <i class="fas fa-paper-plane"></i> Compose Message
                            </a>
                        </div>
                    </div>
                    <a href="messages.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="message-header">
                            <div class="message-from">
                                <h3><?php echo $message['subject']; ?></h3>
                                <div class="sender-info">
                                    <p><strong>From:</strong> <?php echo $message['name']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $message['email']; ?></p>
                                    <?php if ($message['phone']): ?>
                                        <p><strong>Phone:</strong> <?php echo $message['phone']; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="message-meta">
                                <span class="status status-<?php echo $message['status']; ?>">
                                    <?php echo ucfirst($message['status']); ?>
                                </span>
                                <span class="date"><?php echo format_date($message['created_at'], 'd/m/Y H:i'); ?></span>
                            </div>
                        </div>
                        
                        <div class="message-content">
                            <h4>Message</h4>
                            <div class="message-text">
                                <?php echo nl2br($message['message']); ?>
                            </div>
                        </div>
                        
                        <div class="message-actions">
                            <?php if ($message['status'] === 'unread'): ?>
                                <a href="messages.php?action=mark_read&id=<?php echo $message['id']; ?>" class="btn">
                                    <i class="fas fa-envelope-open"></i> Mark as Read
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($message['status'] === 'read'): ?>
                                <a href="messages.php?action=mark_replied&id=<?php echo $message['id']; ?>" class="btn">
                                    <i class="fas fa-reply"></i> Mark as Replied
                                </a>
                            <?php endif; ?>
                            
                            <a href="mailto:<?php echo $message['email']; ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" class="btn">
                                <i class="fas fa-paper-plane"></i> Reply via Email
                            </a>
                            
                            <a href="messages.php?action=delete&id=<?php echo $message['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
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
        
        // Toggle message fields based on type
        function toggleMessageFields() {
            const messageType = document.getElementById('message_type').value;
            const emailFields = document.getElementById('emailFields');
            const smsFields = document.getElementById('smsFields');
            const emailHint = document.getElementById('emailHint');
            const smsHint = document.getElementById('smsHint');
            const recipientEmail = document.getElementById('recipient_email');
            const subject = document.getElementById('subject');
            
            // Hide all fields first
            emailFields.style.display = 'none';
            smsFields.style.display = 'none';
            emailHint.style.display = 'none';
            smsHint.style.display = 'none';
            
            // Remove required attributes
            recipientEmail.removeAttribute('required');
            subject.removeAttribute('required');
            
            // Show relevant fields
            if (messageType === 'email') {
                emailFields.style.display = 'block';
                emailHint.style.display = 'block';
                recipientEmail.setAttribute('required', 'required');
                subject.setAttribute('required', 'required');
            } else if (messageType === 'sms') {
                smsFields.style.display = 'block';
                smsHint.style.display = 'block';
                document.getElementById('recipient_phone').setAttribute('required', 'required');
            }
            
            updateCharCount();
        }
        
        // Update character count for SMS
        function updateCharCount() {
            const messageType = document.getElementById('message_type').value;
            const message = document.getElementById('message').value;
            const charCount = document.getElementById('charCount');
            const charWarning = document.getElementById('charWarning');
            
            if (messageType === 'sms') {
                const count = message.length;
                charCount.textContent = count;
                
                if (count > 160) {
                    charWarning.style.display = 'block';
                    charCount.style.color = '#e74c3c';
                } else {
                    charWarning.style.display = 'none';
                    charCount.style.color = '#27ae60';
                }
            } else {
                charCount.textContent = '0';
                charWarning.style.display = 'none';
            }
        }
        
        // Form validation before submit
        document.getElementById('composeForm').addEventListener('submit', function(e) {
            const messageType = document.getElementById('message_type').value;
            const message = document.getElementById('message').value;
            
            if (!messageType) {
                e.preventDefault();
                alert('Please select message type!');
                return false;
            }
            
            if (messageType === 'sms' && message.length > 160) {
                e.preventDefault();
                alert('SMS message cannot exceed 160 characters!');
                return false;
            }
            
            if (messageType === 'email') {
                const recipientEmail = document.getElementById('recipient_email').value;
                const subject = document.getElementById('subject').value;
                
                if (!recipientEmail || !subject || !message) {
                    e.preventDefault();
                    alert('Please fill all email fields!');
                    return false;
                }
                
                if (!validateEmail(recipientEmail)) {
                    e.preventDefault();
                    alert('Please enter a valid email address!');
                    return false;
                }
            }
            
            if (messageType === 'sms') {
                const recipientPhone = document.getElementById('recipient_phone').value;
                
                if (!recipientPhone || !message) {
                    e.preventDefault();
                    alert('Please fill all SMS fields!');
                    return false;
                }
                
                if (!validatePhone(recipientPhone)) {
                    e.preventDefault();
                    alert('Please enter a valid 10-digit phone number!');
                    return false;
                }
            }
        });
        
        // Email validation
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Phone validation
        function validatePhone(phone) {
            const re = /^[0-9]{10}$/;
            return re.test(phone);
        }
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
        
        .header-actions {
            display: flex;
            gap: 10px;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-group small {
            display: block;
            margin-top: 5px;
            color: #7f8c8d;
            font-size: 12px;
        }
        
        .message-fields {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }
        
        .char-counter {
            background: white;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #e1e8ed;
            text-align: center;
            font-weight: 500;
        }
        
        .char-counter span {
            font-size: 18px;
            font-weight: bold;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
            
            .message-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>
