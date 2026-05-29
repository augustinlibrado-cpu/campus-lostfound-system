<?php
// Home Page - Campus Lost and Found
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Lost & Found - Find Your Lost Items</title>
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
                <a href="index.php" class="active">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="items.php">All Items</a>
                <a href="report.php">Report Item</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" style="margin-top: 0; border-radius: 0;">
        <h1>🎯 Campus Lost and Found System</h1>
        <p>Helping students reconnect with their lost belongings quickly and easily. Search, report, and find items across campus.</p>
        <div class="hero-buttons">
            <a href="report.php" class="btn-primary">📝 Report Item</a>
            <a href="items.php" class="btn-secondary">🔍 View Lost Items</a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Features Section -->
        <section style="margin-bottom: 60px;">
            <h2 style="text-align: center; font-size: 2rem; color: var(--primary); margin-bottom: 40px;">Why Choose Our System?</h2>
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Easy Reporting</h3>
                    <p>Report lost or found items in minutes with a simple form. Include photos and details to help others identify your items.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔎</div>
                    <h3>Fast Searching</h3>
                    <p>Search through hundreds of items using multiple filters. Find your lost belongings quickly with category and status filters.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Secure Information</h3>
                    <p>Your personal contact information is protected. Share details only with verified students through our secure system.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Real-time Updates</h3>
                    <p>Get instant notifications when similar items are found. Track the status of your reported items in real-time.</p>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section style="background: linear-gradient(135deg, #1E3A5F 0%, #2d5a8c 100%); color: white; padding: 60px 40px; border-radius: 12px; text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2rem; margin-bottom: 40px;">How It Works</h2>
            <div class="features" style="margin: 0;">
                <div style="background: rgba(255,255,255,0.1); padding: 30px 20px; border-radius: 12px; backdrop-filter: blur(10px);">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">1️⃣</div>
                    <h3 style="color: white;">Report Your Item</h3>
                    <p>Fill out our simple form with item details, category, and photos</p>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 30px 20px; border-radius: 12px; backdrop-filter: blur(10px);">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">2️⃣</div>
                    <h3 style="color: white;">Search & Filter</h3>
                    <p>Browse through all items using filters and search keywords</p>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 30px 20px; border-radius: 12px; backdrop-filter: blur(10px);">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">3️⃣</div>
                    <h3 style="color: white;">Connect & Claim</h3>
                    <p>Contact the item holder and arrange to claim your belongings</p>
                </div>
            </div>
        </section>

        <!-- Statistics -->
        <section style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2rem; color: var(--primary); margin-bottom: 40px;">Our Impact</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px;">
                <div style="padding: 30px; background: var(--card); border-radius: 12px; box-shadow: var(--shadow-md);">
                    <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);">500+</div>
                    <p style="color: var(--muted); margin-top: 10px;">Items Recovered</p>
                </div>
                <div style="padding: 30px; background: var(--card); border-radius: 12px; box-shadow: var(--shadow-md);">
                    <div style="font-size: 2.5rem; font-weight: 700; color: var(--info);">1000+</div>
                    <p style="color: var(--muted); margin-top: 10px;">Happy Students</p>
                </div>
                <div style="padding: 30px; background: var(--card); border-radius: 12px; box-shadow: var(--shadow-md);">
                    <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);">24/7</div>
                    <p style="color: var(--muted); margin-top: 10px;">Available Access</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section style="background: var(--secondary); color: white; padding: 50px 40px; border-radius: 12px; text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2rem; margin-bottom: 20px;">Don't Wait! Report Your Item Now</h2>
            <p style="font-size: 1.1rem; margin-bottom: 30px; opacity: 0.95;">Join thousands of students using our platform to find their lost belongings safely and efficiently.</p>
            <a href="report.php" class="btn-primary" style="background: white; color: var(--secondary); font-weight: 700; display: inline-block;">Start Reporting Now →</a>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container" style="text-align: center;">
            <h3 style="margin-bottom: 20px; color: white;">Campus Lost & Found System</h3>
            <p style="color: rgba(255,255,255,0.9);">Building a community where lost items find their way home</p>
            
            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.2);">
                <p style="font-size: 0.9rem; opacity: 0.8;">📚 Course Project: Web Development with HTML, CSS, JavaScript, PHP & MySQL</p>
                <p style="font-size: 0.85rem; opacity: 0.7; margin-top: 10px;">© 2026 Campus Lost & Found System. All rights reserved.</p>
                <p style="font-size: 0.85rem; opacity: 0.7; margin-top: 8px;">Developed by: Student Web Development Team</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
