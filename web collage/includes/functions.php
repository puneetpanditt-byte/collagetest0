<?php
require_once 'config.php';

// Helper functions for the college website

// Get page title based on current page
function get_page_title($page) {
    $titles = [
        'index.php' => 'Home',
        'about.php' => 'About Us',
        'courses.php' => 'Courses',
        'faculty.php' => 'Faculty',
        'gallery.php' => 'Gallery',
        'contact.php' => 'Contact',
        'course_details.php' => 'Course Details',
        'news_events.php' => 'News & Events',
        'login.php' => 'Login',
        'register.php' => 'Registration',
        'profile.php' => 'Student Profile',
        'logout.php' => 'Logout',
        'principal-desk.php' => 'Principal Desk',
        'principal-message.php' => 'Principal Messages',
        'principal-profile.php' => 'Principal Profile',
        'teaching-staff.php' => 'Teaching Staff',
        'non-teaching-staff.php' => 'Non-Teaching Staff',
        'contractual-staff.php' => 'Contractual Staff',
        'student-portal.php' => 'Student Portal',
        'examination.php' => 'Examination',
        'results.php' => 'Results',
        'photo-gallery.php' => 'Photo Gallery',
        'video-gallery.php' => 'Video Gallery',
        'media.php' => 'Media',
        'newspaper.php' => 'College Newspaper',
        'magazine.php' => 'College Magazine',
        'facilities.php' => 'Facilities',
        'tender.php' => 'Tender',
        'notices.php' => 'Notices',
        'rules.php' => 'Rules & Regulations',
        'holidays.php' => 'Holidays',
        'instructions.php' => 'Instructions',
        'administration.php' => 'Administration',
        'committee.php' => 'Committee'
    ];
    
    return $titles[$page] ?? 'SDGD College';
}

// Generate breadcrumbs
function generate_breadcrumbs($crumbs) {
    global $breadcrumbs;
    $breadcrumbs = $crumbs;
}

// Get college statistics
function get_college_stats() {
    global $conn;
    
    $stats = [];
    
    // Get student count
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM students WHERE status = 'active'");
    $stats['students'] = mysqli_fetch_assoc($result)['count'];
    
    // Get course count
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses WHERE status = 'active'");
    $stats['courses'] = mysqli_fetch_assoc($result)['count'];
    
    // Get faculty count
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM teachers WHERE status = 'active'");
    $stats['faculty'] = mysqli_fetch_assoc($result)['count'];
    
    // Get placement percentage (mock data for now)
    $stats['placement'] = 95;
    
    return $stats;
}

// Get latest news/events
function get_latest_news($limit = 3) {
    global $conn;
    
    $query = "SELECT * FROM notices WHERE status = 'active' AND target_audience IN ('all', 'students') 
              ORDER BY created_at DESC LIMIT $limit";
    $result = mysqli_query($conn, $query);
    
    $news = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $news[] = [
            'title' => $row['title'],
            'description' => substr($row['description'], 0, 150) . '...',
            'date' => format_date($row['created_at']),
            'type' => $row['notice_type']
        ];
    }
    
    return $news;
}

// Get featured courses
function get_featured_courses($limit = 4) {
    global $conn;
    
    $query = "SELECT * FROM courses WHERE status = 'active' ORDER BY created_at DESC LIMIT $limit";
    $result = mysqli_query($conn, $query);
    
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = [
            'id' => $row['id'],
            'name' => $row['course_name'],
            'code' => $row['course_code'],
            'type' => $row['course_type'] ?? 'undergraduate',
            'duration' => $row['duration'] ?? 'N/A',
            'fees' => $row['fees'] ?? 0,
            'image' => $row['course_image'] ?? 'assets/course-placeholder.jpg',
            'description' => substr($row['description'] ?? 'No description available', 0, 100)
        ];
    }
    
    return $courses;
}

// Get upcoming events
function get_upcoming_events($limit = 3) {
    global $conn;
    
    // Mock data for now - in real implementation, this would come from an events table
    $events = [
        [
            'title' => 'Annual Sports Meet',
            'date' => '2024-02-15',
            'description' => 'Annual sports competition for all students',
            'type' => 'sports'
        ],
        [
            'title' => 'Science Exhibition',
            'date' => '2024-02-20',
            'description' => 'Science project exhibition and competition',
            'type' => 'academic'
        ],
        [
            'title' => 'Cultural Festival',
            'date' => '2024-03-01',
            'description' => 'Annual cultural festival with various competitions',
            'type' => 'cultural'
        ]
    ];
    
    return array_slice($events, 0, $limit);
}

// Get testimonials
function get_testimonials($limit = 3) {
    // Mock data for now - in real implementation, this would come from a testimonials table
    $testimonials = [
        [
            'name' => 'Rahul Kumar',
            'course' => 'B.Sc. Computer Science',
            'message' => 'SDGD College provided me with excellent education and opportunities for growth.',
            'rating' => 5
        ],
        [
            'name' => 'Priya Singh',
            'course' => 'B.A. English',
            'message' => 'The faculty and facilities here are outstanding. I had a great learning experience.',
            'rating' => 5
        ],
        [
            'name' => 'Amit Kumar',
            'course' => 'B.Com',
            'message' => 'The college helped me develop both academically and professionally.',
            'rating' => 4
        ]
    ];
    
    return array_slice($testimonials, 0, $limit);
}

// Format date for display
function format_date_display($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

// Truncate text
function truncate_text($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

// Generate random string for IDs
function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get user information
function get_user_info() {
    if (is_logged_in()) {
        return $_SESSION['user'];
    }
    return null;
}

// Redirect with message
function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}

// Display message
function display_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'info';
        
        echo '<div class="alert alert-' . $type . '">';
        echo '<i class="fas fa-' . ($type == 'success' ? 'check-circle' : ($type == 'error' ? 'exclamation-circle' : 'info-circle')) . '"></i>';
        echo $message;
        echo '</div>';
        
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number
function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

// Clean input
function clean_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Generate pagination
function generate_pagination($total_pages, $current_page, $base_url) {
    if ($total_pages <= 1) return '';
    
    $pagination = '<div class="pagination">';
    
    // Previous button
    if ($current_page > 1) {
        $pagination .= '<a href="' . $base_url . '?page=' . ($current_page - 1) . '" class="page-link">&laquo; Previous</a>';
    }
    
    // Page numbers
    $start = max(1, $current_page - 2);
    $end = min($total_pages, $current_page + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $active_class = $i == $current_page ? ' active' : '';
        $pagination .= '<a href="' . $base_url . '?page=' . $i . '" class="page-link' . $active_class . '">' . $i . '</a>';
    }
    
    // Next button
    if ($current_page < $total_pages) {
        $pagination .= '<a href="' . $base_url . '?page=' . ($current_page + 1) . '" class="page-link">Next &raquo;</a>';
    }
    
    $pagination .= '</div>';
    
    return $pagination;
}
?>
