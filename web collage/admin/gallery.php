<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle form submissions
$action = $_GET['action'] ?? 'list';
$type = $_GET['type'] ?? 'photo';
$gallery_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Add/Edit Photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $type === 'photo') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $category = sanitize_input($_POST['category']);
        $album_name = sanitize_input($_POST['album_name']);
        
        // Handle image upload
        $image_path = '';
        $image_name = '';
        if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_file($_FILES['gallery_image'], '../uploads/gallery/photos/');
            if ($upload_result['success']) {
                $image_path = $upload_result['path'];
                $image_name = $_FILES['gallery_image']['name'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                $query = "INSERT INTO photo_gallery (title, description, image_path, image_name, category, album_name, created_by) 
                          VALUES ('$title', '$description', '$image_path', '$image_name', '$category', '$album_name', " . $_SESSION['admin_id'] . ")";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'add_photo', "Added photo: $title");
                    $message = 'Photo added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add photo: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit' && $gallery_id) {
                $query = "UPDATE photo_gallery SET title='$title', description='$description', category='$category', album_name='$album_name'";
                
                if ($image_path) {
                    $query .= ", image_path='$image_path', image_name='$image_name'";
                }
                
                $query .= " WHERE id = $gallery_id";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'edit_photo', "Updated photo: $title");
                    $message = 'Photo updated successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to update photo: ' . mysqli_error($conn);
                }
            }
        }
    }
}

// Add/Edit Video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $type === 'video') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $video_url = sanitize_input($_POST['video_url']);
        $category = sanitize_input($_POST['category']);
        $duration = sanitize_input($_POST['duration']);
        
        // Handle thumbnail upload
        $thumbnail = '';
        if (isset($_FILES['video_thumbnail']) && $_FILES['video_thumbnail']['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_file($_FILES['video_thumbnail'], '../uploads/gallery/videos/');
            if ($upload_result['success']) {
                $thumbnail = $upload_result['path'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            if ($action === 'add') {
                $query = "INSERT INTO video_gallery (title, description, video_url, thumbnail, category, duration, created_by) 
                          VALUES ('$title', '$description', '$video_url', '$thumbnail', '$category', '$duration', " . $_SESSION['admin_id'] . ")";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'add_video', "Added video: $title");
                    $message = 'Video added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add video: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit' && $gallery_id) {
                $query = "UPDATE video_gallery SET title='$title', description='$description', video_url='$video_url', category='$category', duration='$duration'";
                
                if ($thumbnail) {
                    $query .= ", thumbnail='$thumbnail'";
                }
                
                $query .= " WHERE id = $gallery_id";
                
                if (mysqli_query($conn, $query)) {
                    log_activity($_SESSION['admin_id'], 'edit_video', "Updated video: $title");
                    $message = 'Video updated successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to update video: ' . mysqli_error($conn);
                }
            }
        }
    }
}

// Delete Photo/Video
if ($action === 'delete' && $gallery_id) {
    if ($type === 'photo') {
        $query = "DELETE FROM photo_gallery WHERE id = $gallery_id";
        $log_action = 'delete_photo';
    } else {
        $query = "DELETE FROM video_gallery WHERE id = $gallery_id";
        $log_action = 'delete_video';
    }
    
    if (mysqli_query($conn, $query)) {
        log_activity($_SESSION['admin_id'], $log_action, "Deleted gallery ID: $gallery_id");
        $message = ucfirst($type) . ' deleted successfully!';
        $action = 'list';
    } else {
        $error = 'Failed to delete ' . $type . ': ' . mysqli_error($conn);
    }
}

// Get item for editing
$item = null;
if (($action === 'edit' || $action === 'view') && $gallery_id) {
    if ($type === 'photo') {
        $query = "SELECT * FROM photo_gallery WHERE id = $gallery_id";
    } else {
        $query = "SELECT * FROM video_gallery WHERE id = $gallery_id";
    }
    $result = mysqli_query($conn, $query);
    $item = mysqli_fetch_assoc($result);
}

// Get all photos and videos for listing
$photos = [];
$videos = [];
if ($action === 'list') {
    $search = $_GET['search'] ?? '';
    $category_filter = $_GET['category'] ?? '';
    
    // Get photos
    $photo_query = "SELECT * FROM photo_gallery WHERE 1=1";
    if ($search) {
        $photo_query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
    }
    if ($category_filter) {
        $photo_query .= " AND category = '$category_filter'";
    }
    $photo_query .= " ORDER BY created_at DESC";
    
    $photo_result = mysqli_query($conn, $photo_query);
    $photos = mysqli_fetch_all($photo_result, MYSQLI_ASSOC);
    
    // Get videos
    $video_query = "SELECT * FROM video_gallery WHERE 1=1";
    if ($search) {
        $video_query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
    }
    if ($category_filter) {
        $video_query .= " AND category = '$category_filter'";
    }
    $video_query .= " ORDER BY created_at DESC";
    
    $video_result = mysqli_query($conn, $video_query);
    $videos = mysqli_fetch_all($video_result, MYSQLI_ASSOC);
}

// Get unique categories for filters
$categories = [];
$cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM photo_gallery WHERE category != '' UNION SELECT DISTINCT category FROM video_gallery WHERE category != '' ORDER BY category");
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management - SDGD College</title>
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
                    <li><a href="gallery.php" class="active"><i class="fas fa-images"></i> Gallery</a></li>
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
                    <h1>Gallery Management</h1>
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
                    <!-- Gallery Type Tabs -->
                    <div class="gallery-tabs">
                        <button class="tab-btn <?php echo $type === 'photo' ? 'active' : ''; ?>" onclick="location.href='gallery.php?type=photo'">
                            <i class="fas fa-image"></i> Photo Gallery
                        </button>
                        <button class="tab-btn <?php echo $type === 'video' ? 'active' : ''; ?>" onclick="location.href='gallery.php?type=video'">
                            <i class="fas fa-video"></i> Video Gallery
                        </button>
                    </div>
                    
                    <div class="content-header">
                        <h2><?php echo $type === 'photo' ? 'Photos' : 'Videos'; ?></h2>
                        <a href="gallery.php?action=add&type=<?php echo $type; ?>" class="btn">
                            <i class="fas fa-plus"></i> Add New <?php echo ucfirst($type); ?>
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <div class="search-filters">
                        <form method="GET" class="filter-form">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <div class="filter-row">
                                <input type="text" name="search" placeholder="Search by title or description..." 
                                       value="<?php echo $_GET['search'] ?? ''; ?>">
                                
                                <select name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category; ?>" <?php echo ($_GET['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                            <?php echo $category; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <button type="submit" class="btn">Filter</button>
                                <a href="gallery.php?type=<?php echo $type; ?>" class="btn btn-danger">Clear</a>
                            </div>
                        </form>
                    </div>
                    
                    <?php if ($type === 'photo'): ?>
                        <div class="gallery-grid">
                            <?php foreach ($photos as $item): ?>
                                <div class="gallery-item">
                                    <div class="gallery-image">
                                        <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>">
                                        <div class="gallery-overlay">
                                            <div class="gallery-actions">
                                                <a href="gallery.php?action=view&id=<?php echo $item['id']; ?>&type=photo" class="action-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="gallery.php?action=edit&id=<?php echo $item['id']; ?>&type=photo" class="action-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="gallery.php?action=delete&id=<?php echo $item['id']; ?>&type=photo" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this photo?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gallery-info">
                                        <h4><?php echo $item['title']; ?></h4>
                                        <p class="gallery-meta">
                                            <span class="category"><?php echo $item['category'] ?: 'Uncategorized'; ?></span>
                                            <span class="date"><?php echo format_date($item['created_at']); ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="video-grid">
                            <?php foreach ($videos as $item): ?>
                                <div class="video-item">
                                    <div class="video-thumbnail">
                                        <?php if ($item['thumbnail']): ?>
                                            <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['title']; ?>">
                                        <?php else: ?>
                                            <div class="no-thumbnail">
                                                <i class="fas fa-play"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="video-overlay">
                                            <div class="video-actions">
                                                <a href="gallery.php?action=view&id=<?php echo $item['id']; ?>&type=video" class="action-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="gallery.php?action=edit&id=<?php echo $item['id']; ?>&type=video" class="action-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="gallery.php?action=delete&id=<?php echo $item['id']; ?>&type=video" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this video?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="video-info">
                                        <h4><?php echo $item['title']; ?></h4>
                                        <p class="video-meta">
                                            <span class="category"><?php echo $item['category'] ?: 'Uncategorized'; ?></span>
                                            <span class="duration"><?php echo $item['duration'] ?: 'Unknown'; ?></span>
                                            <span class="date"><?php echo format_date($item['created_at']); ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif (($action === 'add' || $action === 'edit') && $type === 'photo'): ?>
                    <div class="content-header">
                        <h2><?php echo $action === 'add' ? 'Add New Photo' : 'Edit Photo'; ?></h2>
                        <a href="gallery.php?type=photo" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Photos
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <label for="title">Photo Title *</label>
                                <input type="text" id="title" name="title" required 
                                       value="<?php echo $item['title'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4"><?php echo $item['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <input type="text" id="category" name="category" 
                                           value="<?php echo $item['category'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="album_name">Album Name</label>
                                    <input type="text" id="album_name" name="album_name" 
                                           value="<?php echo $item['album_name'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="gallery_image">Photo Image *</label>
                                <input type="file" id="gallery_image" name="gallery_image" accept="image/*" required>
                                <?php if ($item && $item['image_name']): ?>
                                    <p class="file-info">Current image: <?php echo $item['image_name']; ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Photo' : 'Update Photo'; ?>
                                </button>
                                <a href="gallery.php?type=photo" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif (($action === 'add' || $action === 'edit') && $type === 'video'): ?>
                    <div class="content-header">
                        <h2><?php echo $action === 'add' ? 'Add New Video' : 'Edit Video'; ?></h2>
                        <a href="gallery.php?type=video" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to Videos
                        </a>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="form-group">
                                <label for="title">Video Title *</label>
                                <input type="text" id="title" name="title" required 
                                       value="<?php echo $item['title'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="video_url">Video URL *</label>
                                <input type="url" id="video_url" name="video_url" required 
                                       placeholder="https://youtube.com/watch?v=..."
                                       value="<?php echo $item['video_url'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <input type="text" id="category" name="category" 
                                           value="<?php echo $item['category'] ?? ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="duration">Duration</label>
                                    <input type="text" id="duration" name="duration" 
                                           placeholder="e.g., 5:30"
                                           value="<?php echo $item['duration'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4"><?php echo $item['description'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="video_thumbnail">Video Thumbnail</label>
                                <input type="file" id="video_thumbnail" name="video_thumbnail" accept="image/*">
                                <?php if ($item && $item['thumbnail']): ?>
                                    <p class="file-info">Current thumbnail: <img src="<?php echo $item['thumbnail']; ?>" alt="Current" class="current-thumbnail"></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Video' : 'Update Video'; ?>
                                </button>
                                <a href="gallery.php?type=video" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && $item): ?>
                    <div class="content-header">
                        <h2><?php echo ucfirst($type); ?> Details</h2>
                        <a href="gallery.php?type=<?php echo $type; ?>" class="btn">
                            <i class="fas fa-arrow-left"></i> Back to <?php echo ucfirst($type); ?>s
                        </a>
                    </div>
                    
                    <div class="view-container">
                        <div class="view-header">
                            <h3><?php echo $item['title']; ?></h3>
                            <div class="view-meta">
                                <span class="category"><?php echo $item['category'] ?: 'Uncategorized'; ?></span>
                                <span class="date"><?php echo format_date($item['created_at']); ?></span>
                            </div>
                        </div>
                        
                        <div class="view-content">
                            <?php if ($type === 'photo'): ?>
                                <div class="photo-view">
                                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>" class="large-image">
                                </div>
                            <?php else: ?>
                                <div class="video-view">
                                    <?php if ($item['thumbnail']): ?>
                                        <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['title']; ?>" class="large-thumbnail">
                                    <?php endif; ?>
                                    <div class="video-info">
                                        <p><strong>Video URL:</strong> <a href="<?php echo $item['video_url']; ?>" target="_blank"><?php echo $item['video_url']; ?></a></p>
                                        <?php if ($item['duration']): ?>
                                            <p><strong>Duration:</strong> <?php echo $item['duration']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($item['description']): ?>
                                <div class="description">
                                    <h4>Description</h4>
                                    <p><?php echo nl2br($item['description']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="details">
                                <h4>Details</h4>
                                <p><strong>Category:</strong> <?php echo $item['category'] ?: 'Not specified'; ?></p>
                                <?php if ($item['album_name']): ?>
                                    <p><strong>Album:</strong> <?php echo $item['album_name']; ?></p>
                                <?php endif; ?>
                                <p><strong>Added Date:</strong> <?php echo format_date($item['created_at'], 'd/m/Y H:i'); ?></p>
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
        
        .gallery-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .tab-btn {
            background: #ecf0f1;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .tab-btn:hover {
            background: #d5dbdd;
        }
        
        .tab-btn.active {
            background: #3498db;
            color: white;
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
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        
        .gallery-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            background: #3498db;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .action-btn:hover {
            background: #2980b9;
        }
        
        .action-btn.delete {
            background: #e74c3c;
        }
        
        .action-btn.delete:hover {
            background: #c0392b;
        }
        
        .gallery-info {
            padding: 15px;
        }
        
        .gallery-info h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .gallery-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .category {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .video-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .video-item:hover {
            transform: translateY(-5px);
        }
        
        .video-thumbnail {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .video-thumbnail img,
        .no-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .no-thumbnail {
            background: #ecf0f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 40px;
        }
        
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .video-item:hover .video-overlay {
            opacity: 1;
        }
        
        .video-info {
            padding: 15px;
        }
        
        .video-info h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .duration {
            background: #27ae60;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
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
        
        .file-info {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .current-thumbnail {
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
        
        .large-image {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .large-thumbnail {
            width: 200px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .video-view {
            text-align: center;
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
            
            .gallery-grid,
            .video-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
