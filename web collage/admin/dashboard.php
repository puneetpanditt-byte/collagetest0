<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Get dashboard statistics
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM students WHERE status = 'active'"))['count'];
$total_teachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM teachers WHERE status = 'active'"))['count'];
$total_notices = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM notices WHERE status = 'active'"))['count'];
$total_tenders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tenders WHERE status = 'active'"))['count'];

// Get recent activities
$recent_activities = mysqli_query($conn, "
    SELECT al.*, au.full_name 
    FROM activity_logs al 
    LEFT JOIN admin_users au ON al.user_id = au.id 
    ORDER BY al.created_at DESC 
    LIMIT 10
");

// Get recent notices
$recent_notices = mysqli_query($conn, "
    SELECT * FROM notices 
    WHERE status = 'active' 
    ORDER BY created_at DESC 
    LIMIT 5
");

// Get unread messages
$unread_messages = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SDGD College</title>
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
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="notices.php"><i class="fas fa-bullhorn"></i> Notices</a></li>
                    <li><a href="tenders.php"><i class="fas fa-file-contract"></i> Tenders</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="teachers.php"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
                    <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages <?php if ($unread_messages > 0): ?><span class="badge"><?php echo $unread_messages; ?></span><?php endif; ?></a></li>
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
                    <h1>Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="admin-info">
                        <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                        <img src="../assets/admin-avatar.png" alt="Admin" class="admin-avatar" onerror="this.style.display='none'">
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $total_students; ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $total_teachers; ?></h3>
                            <p>Total Teachers</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $total_notices; ?></h3>
                            <p>Active Notices</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $total_tenders; ?></h3>
                            <p>Active Tenders</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="actions-grid">
                        <a href="notices.php?action=add" class="action-card">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Notice</span>
                        </a>
                        <a href="tenders.php?action=add" class="action-card">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Tender</span>
                        </a>
                        <a href="students.php?action=add" class="action-card">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Student</span>
                        </a>
                        <a href="teachers.php?action=add" class="action-card">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Teacher</span>
                        </a>
                    </div>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Recent Notices -->
                    <section class="dashboard-section">
                        <h2>Recent Notices</h2>
                        <div class="recent-list">
                            <?php if (mysqli_num_rows($recent_notices) > 0): ?>
                                <?php while ($notice = mysqli_fetch_assoc($recent_notices)): ?>
                                    <div class="recent-item">
                                        <div class="recent-content">
                                            <h4><?php echo $notice['title']; ?></h4>
                                            <p class="recent-meta">
                                                <i class="fas fa-calendar"></i> <?php echo format_date($notice['publish_date']); ?>
                                                <span class="priority priority-<?php echo $notice['priority']; ?>">
                                                    <?php echo ucfirst($notice['priority']); ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="recent-actions">
                                            <a href="notices.php?action=edit&id=<?php echo $notice['id']; ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No notices found.</p>
                            <?php endif; ?>
                        </div>
                        <a href="notices.php" class="view-all">View All Notices</a>
                    </section>
                    
                    <!-- Recent Activities -->
                    <section class="dashboard-section">
                        <h2>Recent Activities</h2>
                        <div class="recent-list">
                            <?php if (mysqli_num_rows($recent_activities) > 0): ?>
                                <?php while ($activity = mysqli_fetch_assoc($recent_activities)): ?>
                                    <div class="recent-item">
                                        <div class="recent-content">
                                            <h4><?php echo $activity['action']; ?></h4>
                                            <p class="recent-meta">
                                                <i class="fas fa-user"></i> <?php echo $activity['full_name'] ?: 'System'; ?>
                                                <i class="fas fa-clock"></i> <?php echo time_ago($activity['created_at']); ?>
                                            </p>
                                            <?php if ($activity['details']): ?>
                                                <p class="recent-details"><?php echo $activity['details']; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No recent activities.</p>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Auto-refresh dashboard every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
