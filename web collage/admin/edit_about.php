<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $section_name = sanitize_input($_POST['section_name'] ?? '');
        $content = sanitize_input($_POST['content'] ?? '');
        $content_type = sanitize_input($_POST['content_type'] ?? 'text');
        
        if (empty($section_name) || empty($content)) {
            $error = 'Section name and content are required.';
        } else {
            // Check if section exists
            $check_query = "SELECT id FROM about_page_content WHERE section_name = '$section_name'";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                // Update existing section
                $query = "UPDATE about_page_content SET content = ?, content_type = ?, updated_at = CURRENT_TIMESTAMP WHERE section_name = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'ssi', $content, $content_type, $section_name);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                log_activity($_SESSION['admin_id'], 'edit_about', "Updated about section: $section_name");
                $message = 'Content updated successfully!';
            } else {
                // Create new section
                $query = "INSERT INTO about_page_content (section_name, content, content_type, created_by) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'ssi', $section_name, $content, $content_type, $_SESSION['admin_id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                log_activity($_SESSION['admin_id'], 'add_about', "Added about section: $section_name");
                $message = 'Content added successfully!';
            }
        }
    }
}

// Get all sections for display
$sections_query = "SELECT * FROM about_page_content ORDER BY section_name";
$sections_result = mysqli_query($conn, $sections_query);
$sections = [];
while ($row = mysqli_fetch_assoc($sections_result)) {
    $sections[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Page - Admin Panel</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            background: #2c3e50;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .section-list {
            margin-bottom: 30px;
        }
        
        .section-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-content {
            flex: 1;
            font-size: 14px;
            color: #666;
        }
        
        .section-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        
        .btn-edit {
            background: #3498db;
            color: #fff;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: #fff;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .add-section {
            background: #28a745;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .content-type-toggle {
            margin-bottom: 10px;
        }
        
        .content-type-toggle label {
            margin-right: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-edit"></i> Edit About Page Content</h1>
            <p>Manage the content displayed on the About Us page</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Existing Sections -->
        <div class="section-list">
            <h2><i class="fas fa-list"></i> Existing Sections</h2>
            <?php if (!empty($sections)): ?>
                <?php foreach ($sections as $section): ?>
                    <div class="section-item">
                        <div class="section-content">
                            <strong><?php echo ucfirst(str_replace('_', ' ', $section['section_name'])); ?>:</strong>
                            <span><?php echo substr(strip_tags($section['content']), 0, 100); ?>...</span>
                        </div>
                        <div class="section-actions">
                            <a href="edit_about.php?section=<?php echo $section['section_name']; ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="edit_about.php?delete=<?php echo $section['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this section?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No sections found. <a href="edit_about.php?add=new" class="btn btn-edit">Add First Section</a></p>
            <?php endif; ?>
        </div>
        
        <!-- Add New Section -->
        <div class="add-section">
            <h2><i class="fas fa-plus"></i> Add New Section</h2>
            <form method="POST" class="form-container">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                
                <div class="form-group">
                    <label for="section_name">Section Name</label>
                    <input type="text" id="section_name" name="section_name" required 
                           placeholder="e.g., main_title, introduction, mission, vision, values, history, principal_message">
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required placeholder="Enter the content for this section..."></textarea>
                </div>
                
                <div class="form-group content-type-toggle">
                    <label>
                        <input type="radio" name="content_type" value="text" <?php echo (isset($_POST['content_type']) && $_POST['content_type'] === 'text') ? 'checked' : ''); ?>>
                        Text Content
                    </label>
                    <label>
                        <input type="radio" name="content_type" value="html" <?php echo (isset($_POST['content_type']) && $_POST['content_type'] === 'html') ? 'checked' : ''); ?>>
                        HTML Content
                    </label>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-edit">
                        <i class="fas fa-save"></i> Save Section
                    </button>
                    <a href="edit_about.php" class="btn" style="background: #6c757d;">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="index.php" class="btn" style="background: #6c757d;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
