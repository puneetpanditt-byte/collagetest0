<?php
// This file is included in student-portal.php
// Student dashboard content
?>

<div class="student-welcome">
    <h2>Welcome, <?php echo $student['name']; ?>!</h2>
    <p>Roll Number: <strong><?php echo $student['roll_number']; ?></strong></p>
    <p>Registration Number: <strong><?php echo $student['registration_number']; ?></strong></p>
</div>

<div class="student-info">
    <div class="info-card">
        <h3><i class="fas fa-user-graduate"></i> Academic Information</h3>
        <p><strong>Course:</strong> <?php echo $student['course']; ?></p>
        <p><strong>Semester:</strong> <?php echo $student['semester']; ?></p>
        <p><strong>Session:</strong> <?php echo $student['session']; ?></p>
        <p><strong>Admission Date:</strong> <?php echo format_date($student['admission_date']); ?></p>
    </div>
    
    <div class="info-card">
        <h3><i class="fas fa-user"></i> Personal Information</h3>
        <p><strong>Date of Birth:</strong> <?php echo format_date($student['date_of_birth']); ?></p>
        <p><strong>Gender:</strong> <?php echo ucfirst($student['gender']); ?></p>
        <p><strong>Category:</strong> <?php echo $student['category']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($student['status']); ?></p>
    </div>
    
    <div class="info-card">
        <h3><i class="fas fa-phone"></i> Contact Information</h3>
        <p><strong>Phone:</strong> <?php echo $student['phone']; ?></p>
        <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
        <p><strong>Address:</strong> <?php echo $student['address']; ?></p>
    </div>
</div>

<div class="quick-links">
    <a href="student-portal.php?page=examination" class="quick-link">
        <i class="fas fa-clipboard-check"></i>
        <h4>Examination</h4>
    </a>
    
    <a href="student-portal.php?page=results" class="quick-link">
        <i class="fas fa-chart-line"></i>
        <h4>Results</h4>
    </a>
    
    <a href="student-portal.php?page=profile" class="quick-link">
        <i class="fas fa-user-edit"></i>
        <h4>Edit Profile</h4>
    </a>
    
    <a href="student-portal.php?page=fees" class="quick-link">
        <i class="fas fa-rupee-sign"></i>
        <h4>Fee Status</h4>
    </a>
</div>

<div class="recent-notices">
    <h3><i class="fas fa-bullhorn"></i> Recent Notices</h3>
    <?php if (count($notices) > 0): ?>
        <?php foreach ($notices as $notice): ?>
            <div class="notice-item">
                <h4><?php echo $notice['title']; ?></h4>
                <p><?php echo substr($notice['description'], 0, 150); ?>...</p>
                <p class="date"><i class="fas fa-calendar"></i> <?php echo format_date($notice['created_at']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notices available.</p>
    <?php endif; ?>
</div>
