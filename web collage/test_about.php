<?php
require_once 'config.php';

echo "<h2>Testing About Page Setup</h2>";

// Test database connection
if ($conn) {
    echo "<p>✅ Database connection: SUCCESS</p>";
} else {
    echo "<p>❌ Database connection: FAILED</p>";
    exit;
}

// Test if about_page_content table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'about_page_content'");
if (mysqli_num_rows($table_check) > 0) {
    echo "<p>✅ about_page_content table: EXISTS</p>";
} else {
    echo "<p>❌ about_page_content table: MISSING</p>";
}

// Test if content exists
$content_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM about_page_content");
$content_count = mysqli_fetch_assoc($content_check)['count'];
echo "<p>📝 Content records: $content_count</p>";

// Test fetching content
$sections_query = "SELECT * FROM about_page_content WHERE status = 'active' ORDER BY section_name";
$sections_result = mysqli_query($conn, $sections_query);

echo "<h3>Available Sections:</h3>";
echo "<ul>";
while ($row = mysqli_fetch_assoc($sections_result)) {
    echo "<li><strong>" . $row['section_name'] . "</strong> - " . substr($row['content'], 0, 50) . "...</li>";
}
echo "</ul>";

echo "<p><a href='about-dynamic.php'>Test Dynamic About Page</a></p>";
echo "<p><a href='admin/edit_about.php'>Admin Panel</a></p>";

mysqli_close($conn);
?>
