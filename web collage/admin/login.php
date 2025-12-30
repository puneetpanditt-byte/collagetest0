<?php
require_once '../config.php';

// Check if admin is already logged in
if (is_admin()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $csrf_token = $_POST['csrf_token'];
    
    // Verify CSRF token
    if (!verify_csrf_token($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        // Validate input
        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            // Check credentials
            $query = "SELECT id, username, email, password, full_name, role FROM admin_users WHERE username = '$username' AND status = 'active'";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) === 1) {
                $admin = mysqli_fetch_assoc($result);
                
                // Verify password
                if (password_verify($password, $admin['password'])) {
                    // Set session variables
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_name'] = $admin['full_name'];
                    $_SESSION['admin_role'] = $admin['role'];
                    
                    // Update last login
                    $update_query = "UPDATE admin_users SET last_login = NOW() WHERE id = " . $admin['id'];
                    mysqli_query($conn, $update_query);
                    
                    // Log activity
                    log_activity($admin['id'], 'login', 'Admin logged in from IP: ' . get_client_ip());
                    
                    // Redirect to dashboard
                    redirect('dashboard.php');
                } else {
                    $error = 'Invalid username or password.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SDGD College</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .college-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: block;
            border-radius: 50%;
            border: 3px solid #3498db;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../assets/logo.png" alt="College Logo" class="college-logo" onerror="this.style.display='none'">
            <h1>Admin Login</h1>
            <p>SDGD College, Nauhatta</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="back-link">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>
    
    <script>
        // Clear form on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please fill in all fields.');
                return false;
            }
        });
    </script>
</body>
</html>
