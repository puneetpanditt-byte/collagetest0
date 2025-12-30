<?php
require_once 'config.php';

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>SDGD COLLEGE : OFFICIAL WEBSITE</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="top-bar">
            <div class="container">
                <div class="user-pathways">
                    <a href="#" class="pathway-link"><i class="fas fa-user-graduate"></i> Prospective Students</a>
                    <a href="student-portal.php" class="pathway-link"><i class="fas fa-user"></i> Current Students</a>
                    <a href="#" class="pathway-link"><i class="fas fa-users"></i> Alumni</a>
                </div>
                <div class="top-links">
                    <div class="search-bar">
                        <input type="text" placeholder="Search..." class="search-input">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <a href="admin/login.php" class="admin-link"><i class="fas fa-user-shield"></i> ADMIN</a>
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <div class="logo-section">
                    <img src="assets/logo.png" alt="College Logo" class="logo">
                    <div class="college-info">
                        <h1>SUB DIVISIONAL GOVERNMENT DEGREE COLLEGE</h1>
                        <h2>NAUHATTA, ROHTAS</h2>
                        <p>Affiliated to Veer Kunwar Singh University, Ara</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> HOME</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo in_array($current_page, ['about.php', 'administration.php', 'committee.php', 'instructions.php', 'rules.php', 'holidays.php']) ? 'active' : ''; ?>">ABOUT US <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About College</a></li>
                        <li><a href="administration.php" class="<?php echo $current_page == 'administration.php' ? 'active' : ''; ?>">Administration</a></li>
                        <li><a href="committee.php" class="<?php echo $current_page == 'committee.php' ? 'active' : ''; ?>">Committee</a></li>
                        <li><a href="instructions.php" class="<?php echo $current_page == 'instructions.php' ? 'active' : ''; ?>">General Instruction</a></li>
                        <li><a href="rules.php" class="<?php echo $current_page == 'rules.php' ? 'active' : ''; ?>">Rules and Discipline</a></li>
                        <li><a href="holidays.php" class="<?php echo $current_page == 'holidays.php' ? 'active' : ''; ?>">Holidays List</a></li>
                        <li><a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact Us</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo in_array($current_page, ['principal-desk.php', 'principal-message.php', 'principal-profile.php', 'photo-gallery.php', 'video-gallery.php', 'media.php']) ? 'active' : ''; ?>">PRINCIPAL <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="principal-desk.php" class="<?php echo $current_page == 'principal-desk.php' ? 'active' : ''; ?>">Principal Desk</a></li>
                        <li><a href="principal-message.php" class="<?php echo $current_page == 'principal-message.php' ? 'active' : ''; ?>">Message to Students/Staffs/Parent</a></li>
                        <li><a href="principal-profile.php" class="<?php echo $current_page == 'principal-profile.php' ? 'active' : ''; ?>">Principal's Profile</a></li>
                        <li><a href="photo-gallery.php" class="<?php echo $current_page == 'photo-gallery.php' ? 'active' : ''; ?>">Photo Gallery</a></li>
                        <li><a href="video-gallery.php" class="<?php echo $current_page == 'video-gallery.php' ? 'active' : ''; ?>">Video Gallery</a></li>
                        <li><a href="media.php" class="<?php echo $current_page == 'media.php' ? 'active' : ''; ?>">In Media</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo in_array($current_page, ['teaching-staff.php', 'non-teaching-staff.php', 'contractual-staff.php']) ? 'active' : ''; ?>">OUR STAFFS <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="teaching-staff.php" class="<?php echo $current_page == 'teaching-staff.php' ? 'active' : ''; ?>">Teaching Staffs</a></li>
                        <li><a href="non-teaching-staff.php" class="<?php echo $current_page == 'non-teaching-staff.php' ? 'active' : ''; ?>">Non-Teaching Staffs</a></li>
                        <li><a href="contractual-staff.php" class="<?php echo $current_page == 'contractual-staff.php' ? 'active' : ''; ?>">Contractual Staffs</a></li>
                    </ul>
                </li>
                <li><a href="courses.php" class="<?php echo $current_page == 'courses.php' ? 'active' : ''; ?>"><i class="fas fa-book"></i> COURSES</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo in_array($current_page, ['student-portal.php', 'examination.php', 'results.php']) ? 'active' : ''; ?>">STUDENT CORNER <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="student-portal.php" class="<?php echo $current_page == 'student-portal.php' ? 'active' : ''; ?>">Student Portal</a></li>
                        <li><a href="examination.php" class="<?php echo $current_page == 'examination.php' ? 'active' : ''; ?>">Examination</a></li>
                        <li><a href="results.php" class="<?php echo $current_page == 'results.php' ? 'active' : ''; ?>">Results</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo in_array($current_page, ['newspaper.php', 'magazine.php', 'facilities.php']) ? 'active' : ''; ?>">MEDIA <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="photo-gallery.php" class="<?php echo $current_page == 'photo-gallery.php' ? 'active' : ''; ?>">Photo Gallery</a></li>
                        <li><a href="video-gallery.php" class="<?php echo $current_page == 'video-gallery.php' ? 'active' : ''; ?>">Video Gallery</a></li>
                        <li><a href="newspaper.php" class="<?php echo $current_page == 'newspaper.php' ? 'active' : ''; ?>">News Paper</a></li>
                        <li><a href="magazine.php" class="<?php echo $current_page == 'magazine.php' ? 'active' : ''; ?>">College Magazine</a></li>
                        <li><a href="facilities.php" class="<?php echo $current_page == 'facilities.php' ? 'active' : ''; ?>">Facilities</a></li>
                    </ul>
                </li>
                <li><a href="facilities.php" class="<?php echo $current_page == 'facilities.php' ? 'active' : ''; ?>"><i class="fas fa-building"></i> FACILITIES</a></li>
                <li><a href="tender.php" class="<?php echo $current_page == 'tender.php' ? 'active' : ''; ?>"><i class="fas fa-file-invoice"></i> TENDER</a></li>
            </ul>
        </div>
    </nav>

    <!-- Breadcrumbs (for detail pages) -->
    <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
    <div class="breadcrumbs">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php foreach ($breadcrumbs as $index => $crumb): ?>
                        <?php if ($index === count($breadcrumbs) - 1): ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $crumb['title']; ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?php echo $crumb['url']; ?>"><?php echo $crumb['title']; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </div>
    <?php endif; ?>
