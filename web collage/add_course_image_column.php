<?php
require_once 'config.php';

// Add course_image column to courses table
$alter_query = "ALTER TABLE courses ADD COLUMN course_image VARCHAR(255) AFTER seats_available";

if (mysqli_query($conn, $alter_query)) {
    echo "✅ Successfully added 'course_image' column to courses table!";
} else {
    echo "❌ Error adding column: " . mysqli_error($conn);
}

// Check if column exists
$check_query = "DESCRIBE courses";
$result = mysqli_query($conn, $check_query);

echo "<h3>Current courses table structure:</h3>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>
