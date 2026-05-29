<?php


include 'config.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Database Migration - Add MIME Type</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #27ae60; padding: 15px; background: #d5f4e6; border-radius: 4px; margin-bottom: 20px; }
        .error { color: #e74c3c; padding: 15px; background: #fadbd8; border-radius: 4px; margin-bottom: 20px; }
        .info { color: #3498db; padding: 15px; background: #d6eaf8; border-radius: 4px; margin-bottom: 20px; }
        h1 { color: #2c3e50; margin-top: 0; }
        p { color: #34495e; line-height: 1.6; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔄 Database Migration: Add MIME Type Support</h1>";

// Check if the mime_type column already exists
$checkColumn = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
               WHERE TABLE_NAME = 'items' AND COLUMN_NAME = 'mime_type' AND TABLE_SCHEMA = 'campus_lost_found'";
$result = mysqli_query($conn, $checkColumn);

if (mysqli_num_rows($result) > 0) {
    echo "<div class='info'>ℹ️ The 'mime_type' column already exists. No migration needed!</div>";
} else {
    // Add the mime_type column
    $sql = "ALTER TABLE items ADD COLUMN mime_type VARCHAR(50) AFTER image";
    
    if (mysqli_query($conn, $sql)) {
        echo "<div class='success'>✅ Migration successful!</div>";
        echo "<div class='info'>The 'mime_type' column has been added to the 'items' table.</div>";
        echo "<p><strong>Next Steps:</strong></p>";
        echo "<ul>";
        echo "<li>Delete this file (migration_add_mime_type.php)</li>";
        echo "<li>Test the image upload feature by creating a new item</li>";
        echo "<li>Verify that existing images still display correctly</li>";
        echo "<li>Enjoy your fixed image upload feature!</li>";
        echo "</ul>";
    } else {
        echo "<div class='error'>❌ Migration failed!</div>";
        echo "<p><strong>Error:</strong> " . mysqli_error($conn) . "</p>";
        echo "<p>Please ensure your database user has ALTER TABLE privileges.</p>";
    }
}

echo "    </div>
</body>
</html>";

mysqli_close($conn);
?>
