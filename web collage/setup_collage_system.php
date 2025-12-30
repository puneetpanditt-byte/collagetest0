<?php
require_once 'config.php';

// Create collage_items table for dynamic content
$create_table = "CREATE TABLE IF NOT EXISTS collage_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255),
    type ENUM('photo', 'video') DEFAULT 'photo',
    category VARCHAR(100) DEFAULT 'general',
    caption_text TEXT,
    tags VARCHAR(500),
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $create_table)) {
    echo "✅ collage_items table created successfully!<br>";
} else {
    echo "ℹ️ collage_items table already exists<br>";
}

// Insert sample collage items with engaging prompts
$sample_items = [
    [
        'title' => 'Golden Hour Portrait',
        'description' => 'A stunning portrait captured during the golden hour, showcasing natural light and emotion.',
        'file_path' => 'sample-portrait-1.jpg',
        'thumbnail' => 'sample-portrait-1-thumb.jpg',
        'type' => 'photo',
        'category' => 'portraits',
        'caption_text' => 'Capturing the soul behind the eyes. A study in expression and personality.',
        'tags' => 'portrait, golden hour, emotion, photography',
        'views' => 245,
        'likes' => 18
    ],
    [
        'title' => 'Mountain Landscape',
        'description' => 'Breathtaking mountain landscape with dramatic clouds and natural beauty.',
        'file_path' => 'sample-landscape-1.jpg',
        'thumbnail' => 'sample-landscape-1-thumb.jpg',
        'type' => 'photo',
        'category' => 'landscapes',
        'caption_text' => 'Where the earth meets the sky. A panoramic view of Himalayan peaks.',
        'tags' => 'landscape, mountains, nature, photography',
        'views' => 189,
        'likes' => 24
    ],
    [
        'title' => 'City Life in Motion',
        'description' => 'Dynamic street photography capturing the rhythm of urban life.',
        'file_path' => 'sample-street-1.mp4',
        'thumbnail' => 'sample-street-1-thumb.jpg',
        'type' => 'video',
        'category' => 'street',
        'caption_text' => 'Motion in harmony. A short film exploring the rhythm of city life.',
        'tags' => 'street, urban, video, motion',
        'views' => 367,
        'likes' => 32
    ],
    [
        'title' => 'Abstract Color Explosion',
        'description' => 'Vibrant abstract art with bold colors and dynamic composition.',
        'file_path' => 'sample-abstract-1.jpg',
        'thumbnail' => 'sample-abstract-1-thumb.jpg',
        'type' => 'photo',
        'category' => 'abstract',
        'caption_text' => 'Energy, captured. One second of pure movement and color.',
        'tags' => 'abstract, color, art, creative',
        'views' => 156,
        'likes' => 21
    ],
    [
        'title' => 'Behind the Lens',
        'description' => 'A behind-the-scenes look at our latest photoshoot setup.',
        'file_path' => 'sample-behind-1.jpg',
        'thumbnail' => 'sample-behind-1-thumb.jpg',
        'type' => 'photo',
        'category' => 'behind-scenes',
        'caption_text' => 'The magic behind the lens. A look at the setup for our latest shoot.',
        'tags' => 'behind scenes, setup, photography, studio',
        'views' => 98,
        'likes' => 12
    ],
    [
        'title' => 'Action Frozen in Time',
        'description' => 'High-speed photography capturing a moment of pure action.',
        'file_path' => 'sample-action-1.jpg',
        'thumbnail' => 'sample-action-1-thumb.jpg',
        'type' => 'photo',
        'category' => 'action',
        'caption_text' => 'Energy, captured. One second of pure movement.',
        'tags' => 'action, sports, motion, high-speed',
        'views' => 423,
        'likes' => 45
    ]
];

foreach ($sample_items as $item) {
    $check_query = "SELECT id FROM collage_items WHERE title = '" . $item['title'] . "'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO collage_items (title, description, file_path, thumbnail, type, category, caption_text, tags, views, likes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'sssssssii', 
            $item['title'], 
            $item['description'], 
            $item['file_path'], 
            $item['thumbnail'], 
            $item['type'], 
            $item['category'], 
            $item['caption_text'], 
            $item['tags'], 
            $item['views'], 
            $item['likes']
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "✅ Added sample item: " . $item['title'] . "<br>";
    }
}

echo "<h3>✅ Collage System Setup Complete!</h3>";
echo "<p>The web collage system is now ready with dynamic content management.</p>";
echo "<h4>Features Created:</h4>";
echo "<ul>";
echo "<li><strong>Dynamic Gallery:</strong> Photos and videos with prompts</li>";
echo "<li><strong>Category System:</strong> Filter by portraits, landscapes, street, abstract</li>";
echo "<li><strong>Engaging Captions:</strong> Professional prompts for each item</li>";
echo "<li><strong>Interactive Features:</strong> Like, view, and filter functionality</li>";
echo "<li><strong>Sample Content:</strong> 6 diverse items with different categories</li>";
echo "</ul>";

echo "<h4>Next Steps:</h4>";
echo "<ol>";
echo "<li><strong>Test Enhanced Index:</strong> Visit index-enhanced.php to see the new design</li>";
echo "<li><strong>Create Admin Upload:</strong> Build admin/upload.php for adding new items</li>";
echo "<li><strong>Add More Content:</strong> Populate with your own photos and videos</li>";
echo "<li><strong>Customize Prompts:</strong> Edit captions to match your style</li>";
echo "</ol>";

echo "<p><a href='index-enhanced.php' class='btn btn-primary'>Test Enhanced Homepage</a></p>";
echo "<p><a href='admin/upload.php' class='btn btn-secondary'>Admin Upload Panel</a></p>";

mysqli_close($conn);
?>
