<?php
// Edit Item Page - Campus Lost and Found
include 'config.php';

$success = '';
$error = '';
$item = null;

// Get item ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: items.php');
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM items WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: items.php');
    exit;
}

$item = mysqli_fetch_assoc($result);

// Handle Form Submission (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['item_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $itemType = $_POST['item_type'] ?? '';
    $dateReported = $_POST['date_reported'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    $contactInfo = $_POST['contact_info'] ?? '';
    $status = $_POST['status'] ?? 'Pending';

    // Validate required fields
    if (empty($itemName) || empty($category) || empty($itemType) || empty($dateReported) || 
        empty($location) || empty($description) || empty($contactInfo)) {
        $error = "❌ All fields are required. Please fill in all the information.";
    } else {
        // Handle image upload (if provided)
        $imageBinary = $item['image']; // Keep existing image by default
        $mimeType = $item['mime_type']; // Keep existing MIME type by default
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
            $fileSize = $_FILES['item_image']['size'];
            $fileType = $_FILES['item_image']['type'];

            // Validate file
            if ($fileSize > 5 * 1024 * 1024) {
                $error = "❌ File size must be less than 5MB.";
            } elseif (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                $error = "❌ Only image files (JPEG, PNG, GIF, WebP) are allowed.";
            } else {
                $imageBinary = file_get_contents($_FILES['item_image']['tmp_name']);
                $mimeType = $fileType;
            }
        }

        // If no errors, update database
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE items SET item_name=?, category=?, item_type=?, date_reported=?, location=?, description=?, contact_info=?, image=?, mime_type=?, status=? WHERE id=?");
            
            if ($stmt) {
                // Bind parameters with correct types
                $stmt->bind_param("ssssssssssi", $itemName, $category, $itemType, $dateReported, $location, $description, $contactInfo, $imageBinary, $mimeType, $status, $id);
                
                if ($stmt->execute()) {
                    $success = "✅ Item updated successfully!";
                    // Refresh item data
                    $queryRefresh = "SELECT * FROM items WHERE id = $id";
                    $resultRefresh = mysqli_query($conn, $queryRefresh);
                    $item = mysqli_fetch_assoc($resultRefresh);
                } else {
                    $error = "❌ Error updating item: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "❌ Database error: " . $conn->error;
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
    <title>Edit Item - Campus Lost & Found</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Navigation -->
    <header>
        <div class="header-content">
            <a href="index.php" class="logo">
                <div class="logo-icon">📍</div>
                <span>Campus L&F</span>
            </a>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
            <nav>
                <a href="index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="items.php">All Items</a>
                <a href="report.php">Report Item</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container" style="padding: 40px 24px;">
        <div class="page-header">
            <div>
                <h1 class="page-title">✏️ Edit Item</h1>
                <p class="page-subtitle">Update item details and status</p>
            </div>
            <a href="items.php" style="color: var(--primary); font-weight: 600;">← Back to Items</a>
        </div>

        <!-- Form Container -->
        <div class="form-container" style="max-width: 700px;">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                <!-- Item Name -->
                <div class="form-group required">
                    <label for="itemName">Item Name</label>
                    <input type="text" id="itemName" name="item_name" required 
                           value="<?php echo htmlspecialchars($item['item_name']); ?>">
                </div>

                <!-- Category -->
                <div class="form-group required">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Electronics" <?php echo $item['category'] === 'Electronics' ? 'selected' : ''; ?>>📱 Electronics</option>
                        <option value="School Supplies" <?php echo $item['category'] === 'School Supplies' ? 'selected' : ''; ?>>📚 School Supplies</option>
                        <option value="Clothing" <?php echo $item['category'] === 'Clothing' ? 'selected' : ''; ?>>👕 Clothing</option>
                        <option value="Books" <?php echo $item['category'] === 'Books' ? 'selected' : ''; ?>>📖 Books</option>
                        <option value="ID Cards" <?php echo $item['category'] === 'ID Cards' ? 'selected' : ''; ?>>🆔 ID Cards</option>
                        <option value="Accessories" <?php echo $item['category'] === 'Accessories' ? 'selected' : ''; ?>>⌚ Accessories</option>
                        <option value="Others" <?php echo $item['category'] === 'Others' ? 'selected' : ''; ?>>❓ Others</option>
                    </select>
                </div>

                <!-- Item Type -->
                <div class="form-group required">
                    <label for="itemType">Item Type</label>
                    <select id="itemType" name="item_type" required>
                        <option value="Lost" <?php echo $item['item_type'] === 'Lost' ? 'selected' : ''; ?>>😞 Lost</option>
                        <option value="Found" <?php echo $item['item_type'] === 'Found' ? 'selected' : ''; ?>>😊 Found</option>
                    </select>
                </div>

                <!-- Date Lost/Found -->
                <div class="form-group required">
                    <label for="dateReported">Date Lost/Found</label>
                    <input type="date" id="dateReported" name="date_reported" required
                           value="<?php echo htmlspecialchars($item['date_reported']); ?>">
                </div>

                <!-- Location -->
                <div class="form-group required">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required 
                           value="<?php echo htmlspecialchars($item['location']); ?>">
                </div>

                <!-- Description -->
                <div class="form-group required">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>

                <!-- Contact Information -->
                <div class="form-group required">
                    <label for="contactInfo">Contact Information</label>
                    <input type="text" id="contactInfo" name="contact_info" required 
                           value="<?php echo htmlspecialchars($item['contact_info']); ?>">
                </div>

                <!-- Status -->
                <div class="form-group required">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Pending" <?php echo $item['status'] === 'Pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                        <option value="Claimed" <?php echo $item['status'] === 'Claimed' ? 'selected' : ''; ?>>✅ Claimed</option>
                        <option value="Returned" <?php echo $item['status'] === 'Returned' ? 'selected' : ''; ?>>🎉 Returned</option>
                    </select>
                </div>

                <!-- Current Image Display -->
                <?php if ($item['image']): ?>
                    <div class="form-group">
                        <label>Current Image</label>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" 
                             alt="Current Item Image" class="image-preview" 
                             style="display: block; margin-bottom: 16px;">
                    </div>
                <?php endif; ?>

                <!-- Image Upload -->
                <div class="form-group">
                    <label for="itemImage2">Update Image (Optional)</label>
                    <div class="form-group file-upload" id="dropZone2">
                        <input type="file" id="itemImage2" name="item_image" accept="image/*">
                        <div class="file-upload-label">
                            📸 Click or drag and drop a new image<br>
                            <span style="font-size: 0.85rem; color: var(--muted);">Max file size: 5MB</span>
                        </div>
                    </div>
                    <img id="imagePreview2" class="image-preview" style="display: none;">
                </div>

                <!-- Buttons -->
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">💾 Save Changes</button>
                    <a href="items.php" class="btn-cancel" style="display: inline-block; padding: 14px 40px; text-decoration: none; text-align: center;">❌ Cancel</a>
                </div>
            </form>

            <!-- Item Info -->
            <div style="background: var(--light); padding: 16px; border-radius: 8px; margin-top: 24px; border-left: 4px solid var(--info);">
                <p style="font-size: 0.9rem; color: var(--muted); margin: 0;">
                    <strong>ℹ️ Item ID:</strong> <?php echo $item['id']; ?><br>
                    <strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($item['created_at'])); ?><br>
                    <strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($item['updated_at'])); ?>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container" style="text-align: center;">
            <p>© 2026 Campus Lost & Found System. All rights reserved.</p>
            <p style="font-size: 0.85rem; opacity: 0.8; margin-top: 8px;">Developed by: Student Web Development Team</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>