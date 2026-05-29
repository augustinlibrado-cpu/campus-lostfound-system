<?php
// Report Item Page - Campus Lost and Found
include 'config.php';

$success = '';
$error = '';

// Handle Form Submission
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
        // Handle image upload
        $imageBinary = null;
        $mimeType = null;
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

        // If no errors, insert into database
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO items (item_name, category, item_type, date_reported, location, description, contact_info, image, mime_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt) {
                // Bind parameters with correct types - use 's' for image to allow NULL
                $stmt->bind_param("ssssssssss", $itemName, $category, $itemType, $dateReported, $location, $description, $contactInfo, $imageBinary, $mimeType, $status);
                
                if ($stmt->execute()) {
                    $success = "✅ Item reported successfully! Thank you for helping the campus community.";
                    // Clear form
                    $_POST = array();
                } else {
                    $error = "❌ Error reporting item: " . $stmt->error;
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
    <title>Report Item - Campus Lost & Found</title>
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
                <a href="report.php" class="active">Report Item</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container" style="padding: 40px 24px;">
        <div class="page-header">
            <div>
                <h1 class="page-title">📝 Report an Item</h1>
                <p class="page-subtitle">Help the campus community by reporting lost or found items</p>
            </div>
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

            <form id="reportForm" method="POST" enctype="multipart/form-data">
                <!-- Item Name -->
                <div class="form-group required">
                    <label for="itemName">Item Name</label>
                    <input type="text" id="itemName" name="item_name" required 
                           placeholder="e.g., Apple AirPods Pro, Blue Wallet, Student ID"
                           value="<?php echo htmlspecialchars($_POST['item_name'] ?? ''); ?>">
                </div>

                <!-- Category -->
                <div class="form-group required">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Electronics" <?php echo ($_POST['category'] ?? '') === 'Electronics' ? 'selected' : ''; ?>>📱 Electronics (Phones, Laptops, Earbuds)</option>
                        <option value="School Supplies" <?php echo ($_POST['category'] ?? '') === 'School Supplies' ? 'selected' : ''; ?>>📚 School Supplies (Books, Pens, Notebooks)</option>
                        <option value="Clothing" <?php echo ($_POST['category'] ?? '') === 'Clothing' ? 'selected' : ''; ?>>👕 Clothing (Jackets, Shirts, Shoes)</option>
                        <option value="Books" <?php echo ($_POST['category'] ?? '') === 'Books' ? 'selected' : ''; ?>>📖 Books (Textbooks, Novels)</option>
                        <option value="ID Cards" <?php echo ($_POST['category'] ?? '') === 'ID Cards' ? 'selected' : ''; ?>>🆔 ID Cards (Student ID, Licenses)</option>
                        <option value="Accessories" <?php echo ($_POST['category'] ?? '') === 'Accessories' ? 'selected' : ''; ?>>⌚ Accessories (Watches, Bags, Wallets)</option>
                        <option value="Others" <?php echo ($_POST['category'] ?? '') === 'Others' ? 'selected' : ''; ?>>❓ Others</option>
                    </select>
                </div>

                <!-- Item Type -->
                <div class="form-group required">
                    <label for="itemType">Item Type</label>
                    <select id="itemType" name="item_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Lost" <?php echo ($_POST['item_type'] ?? '') === 'Lost' ? 'selected' : ''; ?>>😞 Lost</option>
                        <option value="Found" <?php echo ($_POST['item_type'] ?? '') === 'Found' ? 'selected' : ''; ?>>😊 Found</option>
                    </select>
                </div>

                <!-- Date Lost/Found -->
                <div class="form-group required">
                    <label for="dateReported">Date Lost/Found</label>
                    <input type="date" id="dateReported" name="date_reported" required
                           value="<?php echo htmlspecialchars($_POST['date_reported'] ?? date('Y-m-d')); ?>">
                </div>

                <!-- Location -->
                <div class="form-group required">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required 
                           placeholder="e.g., Library Building - 2nd Floor, Cafeteria, Parking Lot"
                           value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                </div>

                <!-- Description -->
                <div class="form-group required">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required 
                              placeholder="Provide detailed information about the item. Include color, size, brand, distinctive features, etc."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <!-- Contact Information -->
                <div class="form-group required">
                    <label for="contactInfo">Contact Information</label>
                    <input type="text" id="contactInfo" name="contact_info" required 
                           placeholder="Email | Phone Number (e.g., john@student.edu | +1234567890)"
                           value="<?php echo htmlspecialchars($_POST['contact_info'] ?? ''); ?>">
                </div>

                <!-- Status -->
                <div class="form-group required">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Pending" <?php echo ($_POST['status'] ?? 'Pending') === 'Pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                        <option value="Claimed" <?php echo ($_POST['status'] ?? '') === 'Claimed' ? 'selected' : ''; ?>>✅ Claimed</option>
                        <option value="Returned" <?php echo ($_POST['status'] ?? '') === 'Returned' ? 'selected' : ''; ?>>🎉 Returned</option>
                    </select>
                </div>

                <!-- Image Upload -->
                <div class="form-group">
                    <label for="itemImage">Upload Image (Optional)</label>
                    <div class="form-group file-upload" id="dropZone">
                        <input type="file" id="itemImage" name="item_image" accept="image/*">
                        <div class="file-upload-label">
                            📸 Click or drag and drop an image here<br>
                            <span style="font-size: 0.85rem; color: var(--muted);">Max file size: 5MB</span>
                        </div>
                    </div>
                    <img id="imagePreview" class="image-preview" style="display: none;">
                </div>

                <!-- Buttons -->
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">📤 Submit Report</button>
                    <button type="button" class="btn-clear" onclick="resetForm('reportForm')">🔄 Clear Form</button>
                </div>
            </form>

            <!-- Help Section -->
            <div style="background: var(--light); padding: 20px; border-radius: 8px; margin-top: 30px; border-left: 4px solid var(--primary);">
                <h3 style="color: var(--primary); font-size: 0.95rem; margin-bottom: 12px;">💡 Tips for Successful Reporting:</h3>
                <ul style="color: var(--muted); font-size: 0.9rem; line-height: 1.8; padding-left: 20px;">
                    <li>Be as detailed as possible in your description</li>
                    <li>Include identifying marks, colors, and distinctive features</li>
                    <li>Upload a clear photo if available</li>
                    <li>Provide accurate location information</li>
                    <li>Ensure your contact information is correct</li>
                    <li>Check your email regularly for responses</li>
                </ul>
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
