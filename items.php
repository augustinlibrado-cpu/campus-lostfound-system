<?php
// Items List Page - Campus Lost and Found
include 'config.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM items WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $success = "✅ Item deleted successfully.";
    } else {
        $error = "❌ Error deleting record.";
    }
}

// Fetch all items
$query = "SELECT * FROM items ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Items - Campus Lost & Found</title>
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
                <a href="items.php" class="active">All Items</a>
                <a href="report.php">Report Item</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container" style="padding: 40px 24px;">
        <div class="page-header">
            <div>
                <h1 class="page-title">📋 All Items</h1>
                <p class="page-subtitle">Search and browse all lost and found items on campus</p>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="🔍 Search items by name, description, or location...">
            </div>
            <select id="categoryFilter" class="filter-dropdown">
                <option value="">📁 All Categories</option>
                <option value="Electronics">📱 Electronics</option>
                <option value="School Supplies">📚 School Supplies</option>
                <option value="Clothing">👕 Clothing</option>
                <option value="Books">📖 Books</option>
                <option value="ID Cards">🆔 ID Cards</option>
                <option value="Accessories">⌚ Accessories</option>
                <option value="Others">❓ Others</option>
            </select>
            <select id="statusFilter" class="status-filter">
                <option value="">📊 All Status</option>
                <option value="Pending">⏳ Pending</option>
                <option value="Claimed">✅ Claimed</option>
                <option value="Returned">🎉 Returned</option>
            </select>
            <select id="sortDropdown" class="sort-dropdown">
                <option value="newest">⬇️ Newest First</option>
                <option value="oldest">⬆️ Oldest First</option>
            </select>
        </div>

        <!-- View Toggle -->
        <div class="view-toggle">
            <button data-view="grid" class="active" onclick="toggleView('grid')">📊 Grid View</button>
            <button data-view="table" onclick="toggleView('table')">📋 Table View</button>
        </div>

        <!-- Items Grid View -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="items-grid">
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                    <div class="item-card" data-category="<?php echo htmlspecialchars($item['category']); ?>" 
                         data-status="<?php echo htmlspecialchars($item['status']); ?>" 
                         data-date="<?php echo htmlspecialchars($item['date_reported']); ?>">
                        
                        <!-- Item Image -->
                        <?php if ($item['image']): ?>
                            <?php 
                                $mimeType = $item['mime_type'] ?? 'image/jpeg';
                                $imageData = base64_encode($item['image']);
                            ?>
                            <img src="data:<?php echo htmlspecialchars($mimeType); ?>;base64,<?php echo $imageData; ?>" alt="Item Image" class="item-image">
                        <?php else: ?>
                            <div class="item-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0A2F5F, #2D8659); color: white; font-size: 3rem;">
                                📦
                            </div>
                        <?php endif; ?>

                        <!-- Item Content -->
                        <div class="item-content">
                            <div class="item-header">
                                <div class="item-name"><?php echo htmlspecialchars($item['item_name']); ?></div>
                                <span class="status-badge <?php echo strtolower($item['status']); ?>">
                                    <?php echo $item['status']; ?>
                                </span>
                            </div>

                            <div class="item-meta">
                                <span>🏷️ <?php echo htmlspecialchars($item['category']); ?></span>
                                <span><?php echo ucfirst($item['item_type']); ?></span>
                                <span>📅 <?php echo date('M d, Y', strtotime($item['date_reported'])); ?></span>
                            </div>

                            <div class="item-description">
                                <?php echo htmlspecialchars(substr($item['description'], 0, 120)) . (strlen($item['description']) > 120 ? '...' : ''); ?>
                            </div>

                            <div class="item-meta">
                                <span>📍 <?php echo htmlspecialchars($item['location']); ?></span>
                            </div>

                            <div class="item-contact">
                                <strong>Contact:</strong> <?php echo htmlspecialchars($item['contact_info']); ?>
                            </div>

                            <div class="item-actions">
                                <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn-edit">✏️ Edit</a>
                                <button class="btn-delete" onclick="confirmDelete(<?php echo $item['id']; ?>)">🗑️ Delete</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; 
                mysqli_data_seek($result, 0); // Reset pointer for table view
                ?>
            </div>

            <!-- Items Table View -->
            <div style="overflow-x: auto;">
                <table class="items-table" style="display: none;">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = mysqli_fetch_assoc($result)): ?>
                        <tr data-category="<?php echo htmlspecialchars($item['category']); ?>" 
                            data-status="<?php echo htmlspecialchars($item['status']); ?>" 
                            data-date="<?php echo htmlspecialchars($item['date_reported']); ?>">
                            <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo ucfirst($item['item_type']); ?></td>
                            <td><?php echo htmlspecialchars($item['location']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($item['status']); ?>">
                                    <?php echo $item['status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($item['date_reported'])); ?></td>
                            <td><?php echo htmlspecialchars(substr($item['contact_info'], 0, 25)) . (strlen($item['contact_info']) > 25 ? '...' : ''); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn-edit">✏️ Edit</a>
                                    <button class="btn-delete" onclick="confirmDelete(<?php echo $item['id']; ?>)">🗑️ Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <div class="alert alert-info" style="text-align: center;">
                📭 No items found yet. <a href="report.php" style="font-weight: 600;">Report an item now →</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <h2>🗑️ Confirm Delete</h2>
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
            <div class="modal-buttons">
                <button class="btn-confirm" onclick="proceedDelete()">Yes, Delete</button>
                <button class="btn-cancel-modal" onclick="cancelDelete()">Cancel</button>
            </div>
        </div>
    </div>

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
