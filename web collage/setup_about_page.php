<?php
require_once 'config.php';

// Create about_page_content table for dynamic content
$create_table = "CREATE TABLE IF NOT EXISTS about_page_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_name VARCHAR(100) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    content_type ENUM('text', 'html') DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    status ENUM('active', 'inactive') DEFAULT 'active'
)";

if (mysqli_query($conn, $create_table)) {
    echo "✅ about_page_content table created successfully!<br>";
} else {
    echo "ℹ️ about_page_content table already exists<br>";
}

// Insert default content
$default_content = [
    [
        'section_name' => 'main_title',
        'content' => 'About SDGD College',
        'content_type' => 'text'
    ],
    [
        'section_name' => 'introduction',
        'content' => 'Sub Divisional Government Degree College, Nauhatta is a premier educational institution located in Rohtas district, Bihar. Established with a vision to provide quality higher education to students from rural and semi-urban areas, our college has been a beacon of knowledge and excellence for over four decades.',
        'content_type' => 'text'
    ],
    [
        'section_name' => 'mission',
        'content' => 'To provide accessible, affordable, and high-quality education that empowers students to achieve their academic and professional goals while contributing to the development of society.',
        'content_type' => 'text'
    ],
    [
        'section_name' => 'vision',
        'content' => 'To become a center of academic excellence that nurtures innovation, research, and holistic development of students, preparing them to be responsible global citizens.',
        'content_type' => 'text'
    ],
    [
        'section_name' => 'values',
        'content' => '<ul>
    <li><strong>Excellence:</strong> Striving for the highest standards in teaching and learning</li>
    <li><strong>Integrity:</strong> Upholding ethical principles and transparency</li>
    <li><strong>Innovation:</strong> Encouraging creativity and new ideas</li>
    <li><strong>Inclusivity:</strong> Providing equal opportunities for all students</li>
    <li><strong>Service:</strong> Contributing to community and society</li>
</ul>',
        'content_type' => 'html'
    ],
    [
        'section_name' => 'history',
        'content' => 'Established in 1980, SDGD College has been serving the educational needs of the region for over four decades. The college started with a small group of students and has grown significantly over the years, now offering undergraduate and postgraduate programs in various disciplines.',
        'content_type' => 'text'
    ],
    [
        'section_name' => 'principal_message',
        'content' => '"Education is the most powerful weapon which you can use to change the world. At SDGD College, we are committed to providing quality education that empowers our students to become responsible citizens and leaders of tomorrow." - Principal, SDGD College',
        'content_type' => 'text'
    ]
];

foreach ($default_content as $content) {
    $check_query = "SELECT id FROM about_page_content WHERE section_name = '" . $content['section_name'] . "'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO about_page_content (section_name, content, content_type, created_by) VALUES (?, ?, ?, 1)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'sss', $content['section_name'], $content['content'], $content['content_type']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "✅ Inserted default content for: " . $content['section_name'] . "<br>";
    }
}

echo "<h3>✅ About Page Setup Complete!</h3>";
echo "<p>The about page now has dynamic content management through the database.</p>";
echo "<h4>Next Steps:</h4>";
echo "<ol>";
echo "<li><strong>Update about.php:</strong> Modify the about.php file to fetch content from database</li>";
echo "<li><strong>Create Admin Interface:</strong> Create admin/edit_about.php to manage content</li>";
echo "<li><strong>Test the System:</strong> Visit about.php to see dynamic content</li>";
echo "</ol>";

echo "<p><a href='about.php' class='btn btn-primary'>Test About Page</a></p>";

mysqli_close($conn);
?>
