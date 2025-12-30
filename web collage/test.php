<?php
// Test database connection
require_once 'config.php';

echo "<h1>Website Status Check</h1>";

// Test database connection
if ($conn) {
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Test if database exists
    $db_check = mysqli_query($conn, "SHOW DATABASES LIKE 'sdgd_college'");
    if (mysqli_num_rows($db_check) > 0) {
        echo "<p style='color: green;'>✅ Database 'sdgd_college' exists</p>";
        
        // Test if tables exist
        $tables_query = "SHOW TABLES";
        $tables_result = mysqli_query($conn, $tables_query);
        $table_count = mysqli_num_rows($tables_result);
        echo "<p style='color: green;'>✅ Found $table_count tables in database</p>";
        
        // List some important tables
        $important_tables = ['admin_users', 'students', 'teachers', 'courses', 'notices'];
        foreach ($important_tables as $table) {
            $table_check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
            if (mysqli_num_rows($table_check) > 0) {
                echo "<p style='color: green;'>✅ Table '$table' exists</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Table '$table' missing</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>❌ Database 'sdgd_college' does not exist</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Database connection failed</p>";
}

// Test file structure
echo "<h2>File Structure Check</h2>";
$required_files = [
    'index.php' => 'Main homepage',
    'config.php' => 'Configuration file',
    'styles.css' => 'Stylesheet',
    'admin/login.php' => 'Admin login',
    'about.php' => 'About page'
];

foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file - $description exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file - $description missing</p>";
    }
}

// Test PHP version
echo "<h2>PHP Environment</h2>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

// Test if required extensions are loaded
$required_extensions = ['mysqli', 'session', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ Extension '$ext' is loaded</p>";
    } else {
        echo "<p style='color: red;'>❌ Extension '$ext' is NOT loaded</p>";
    }
}

echo "<h2>Next Steps</h2>";
echo "<p>If all checks pass, your website should work properly at: <a href='index.php'>http://localhost/web%20collage/</a></p>";
echo "<p>If you see any red items, those need to be fixed first.</p>";
?>
