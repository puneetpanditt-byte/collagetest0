<?php
// Admin Directory Index - Redirect to Login
require_once '../config.php';

// Check if admin is already logged in
if (is_admin()) {
    redirect('dashboard.php');
} else {
    redirect('login.php');
}
?>
