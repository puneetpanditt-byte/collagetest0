<?php
require_once '../config.php';

// Check if admin is logged in
if (is_admin()) {
    // Log activity
    log_activity($_SESSION['admin_id'], 'logout', 'Admin logged out from IP: ' . get_client_ip());
    
    // Destroy session
    session_destroy();
}

// Redirect to login page
redirect('login.php');
?>
