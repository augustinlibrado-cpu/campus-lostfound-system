<?php
// Dashboard Page - Campus Lost and Found
include 'config.php';

// Get statistics
$totalLost = mysqli_query($conn, "SELECT COUNT(*) as count FROM items WHERE item_type='Lost'")->fetch_assoc()['count'];
$totalFound = mysqli_query($conn, "SELECT COUNT(*) as count FROM items WHERE item_type='Found'")->fetch_assoc()['count'];
$totalClaimed = mysqli_query($conn, "SELECT COUNT(*) as count FROM items WHERE status='Claimed'")->fetch_assoc()['count'];
$totalPending = mysqli_query($conn, "SELECT COUNT(*) as count FROM items WHERE status='Pending'")->fetch_assoc()['count'];

// Get recent items
$recentItems = mysqli_query($conn, "SELECT * FROM items ORDER BY created_at DESC LIMIT 5");

// Get category statistics
$categoryStats = mysqli_query($conn, "SELECT category, COUNT(*) as count FROM items GROUP BY category");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Campus Lost & Found</title>
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
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="items.php">All Items</a>
                <a href="report.php">Report Item</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">📊 Dashboard</h1>
                <p class="page-subtitle">Overview of Campus Lost & Found System Activity</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <section class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-label">Total Lost Items</div>
                <div class="stat-number"><?php echo $totalLost; ?></div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Total Found Items</div>
                <div class="stat-number"><?php echo $totalFound; ?></div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Claimed Items</div>
                <div class="stat-number"><?php echo $totalClaimed; ?></div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Pending Items</div>
                <div class="stat-number"><?php echo $totalPending; ?></div>
            </div>
        </section>

        <!-- Category Statistics -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: var(--primary); font-size: 1.5rem; margin-bottom: 20px;">📈 Items by Category</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 16px;">
                <?php while ($row = mysqli_fetch_assoc($categoryStats)): ?>
                <div style="background: var(--card); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow-sm);">
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--secondary);"><?php echo $row['count']; ?></div>
                    <div style="color: var(--muted); font-size: 0.9rem; margin-top: 8px;"><?php echo $row['category']; ?></div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Recent Submissions Table -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: var(--primary); font-size: 1.5rem; margin-bottom: 20px;">📋 Recent Submissions</h2>
            
            <?php if (mysqli_num_rows($recentItems) > 0): ?>
                <div style="overflow-x: auto;">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($recentItems)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($item['item_type']); ?></td>
                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                <td><?php echo htmlspecialchars($item['location']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($item['status']); ?>">
                                        <?php echo $item['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($item['date_reported'])); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="edit.php?id=<?php echo $item['id']; ?>" style="display: inline-block; padding: 8px 12px; background: var(--primary); color: white; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">✏️ Edit</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    📭 No items found yet. <a href="report.php" style="font-weight: 600;">Report an item →</a>
                </div>
            <?php endif; ?>
        </section>

        <!-- Quick Actions -->
        <section style="background: var(--light); padding: 40px; border-radius: 12px; text-align: center;">
            <h2 style="color: var(--primary); font-size: 1.3rem; margin-bottom: 24px;">Quick Actions</h2>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="report.php" class="btn-primary">📝 Report New Item</a>
                <a href="items.php" class="btn-secondary">🔍 Browse All Items</a>
                <a href="items.php?filter=pending" class="btn-secondary">⏳ View Pending Items</a>
            </div>
        </section>
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
