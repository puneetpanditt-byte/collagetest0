<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sdgd_college');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8");

// Session Configuration
session_start();

// Site Configuration
define('SITE_NAME', 'SDGD College, Nauhatta');
define('SITE_URL', 'http://localhost/web%20collage/');
define('ADMIN_EMAIL', 'admin@sdgdcnauhatta.ac.in');

// File Upload Configuration
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']);

// Security Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_admin() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function is_teacher() {
    return isset($_SESSION['teacher_id']) && !empty($_SESSION['teacher_id']);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

// File Upload Function
function upload_file($file, $destination_folder) {
    if (!file_exists($destination_folder)) {
        mkdir($destination_folder, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size too large'];
    }
    
    $new_filename = uniqid() . '.' . $file_extension;
    $destination = $destination_folder . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $new_filename, 'path' => $destination];
    } else {
        return ['success' => false, 'message' => 'File upload failed'];
    }
}

// Pagination Function
function paginate($query, $page, $per_page = 10) {
    global $conn;
    
    $page = max(1, (int)$page);
    $offset = ($page - 1) * $per_page;
    
    // Get total records
    $count_query = str_replace('*', 'COUNT(*) as total', $query);
    $count_result = mysqli_query($conn, $count_query);
    $total_records = mysqli_fetch_assoc($count_result)['total'];
    
    // Get paginated results
    $paginated_query = $query . " LIMIT $offset, $per_page";
    $result = mysqli_query($conn, $paginated_query);
    
    $total_pages = ceil($total_records / $per_page);
    
    return [
        'data' => $result,
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'current_page' => $page,
        'per_page' => $per_page
    ];
}

// Email Function (Basic)
function send_email($to, $subject, $message) {
    $headers = "From: " . ADMIN_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Log Function
function log_activity($user_id, $action, $details = '') {
    global $conn;
    
    $user_id = (int)$user_id;
    $action = sanitize_input($action);
    $details = sanitize_input($details);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $query = "INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent, created_at) 
              VALUES ('$user_id', '$action', '$details', '$ip_address', '$user_agent', NOW())";
    
    mysqli_query($conn, $query);
}

// Error Handler
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    $error_message = "Error: [$errno] $errstr in $errfile on line $errline";
    error_log($error_message);
    
    if (ini_get('display_errors')) {
        echo "<div class='error-message'>$error_message</div>";
    }
}

// Set custom error handler
set_error_handler('custom_error_handler');

// Date Format Function
function format_date($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

// Time Ago Function
function time_ago($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        return round($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return round($diff / 3600) . ' hours ago';
    } elseif ($diff < 2592000) {
        return round($diff / 86400) . ' days ago';
    } else {
        return format_date($datetime);
    }
}

// Generate CSRF Token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF Token
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Get Client IP
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
