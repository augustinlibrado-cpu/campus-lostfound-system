<?php
include 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $age     = trim($_POST['age']);
    $course  = trim($_POST['course']);
    $address = trim($_POST['address']);
    $email   = trim($_POST['email']);

    // Validation
    if (empty($name))    $errors[] = "Name is required.";
    if (empty($age))     $errors[] = "Age is required.";
    elseif (!is_numeric($age) || $age < 1 || $age > 120) $errors[] = "Age must be a valid number.";
    if (empty($course))  $errors[] = "Course is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($email))   $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email address.";

    if (empty($errors)) {
        $name    = mysqli_real_escape_string($conn, $name);
        $age     = intval($age);
        $course  = mysqli_real_escape_string($conn, $course);
        $address = mysqli_real_escape_string($conn, $address);
        $email   = mysqli_real_escape_string($conn, $email);

        $sql = "INSERT INTO students (name, age, course, address, email) VALUES ('$name', $age, '$course', '$address', '$email')";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?added=1");
            exit();
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student — StudentDB</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0a0a0f; --surface: #12121a; --card: #1a1a26;
            --border: #2a2a3d; --accent: #7c6aff; --accent2: #ff6a9a;
            --accent3: #6affda; --text: #e8e8f0; --muted: #7070a0;
            --danger: #ff4d6d; --success: #00c896;
        }
        body {
            background: var(--bg); color: var(--text);
            font-family: 'DM Sans', sans-serif; min-height: 100vh;
            background-image: radial-gradient(ellipse at 30% 10%, rgba(124,106,255,0.1) 0%, transparent 55%),
                              radial-gradient(ellipse at 70% 90%, rgba(106,255,218,0.07) 0%, transparent 55%);
        }
        header {
            background: rgba(18,18,26,0.9); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border); padding: 0 40px;
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between; height: 70px;
        }
        .logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.3rem; }
        .logo span { color: var(--accent); }
        nav a { color: var(--muted); text-decoration: none; font-size: 0.85rem; font-weight: 500; margin-left: 28px; transition: color 0.2s; }
        nav a:hover { color: var(--text); }
        nav a.active { color: var(--accent); }

        main { max-width: 680px; margin: 0 auto; padding: 48px 24px; }

        .breadcrumb { display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 0.8rem; margin-bottom: 28px; }
        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--text); }

        .page-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 2rem; margin-bottom: 8px; }
        .page-subtitle { color: var(--muted); font-size: 0.9rem; margin-bottom: 36px; }

        .alert { border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 0.86rem; animation: slideDown 0.3s ease; }
        .alert-error { background: rgba(255,77,109,0.1); border: 1px solid rgba(255,77,109,0.3); color: var(--danger); }
        .alert ul { padding-left: 18px; }
        .alert li { margin-bottom: 4px; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

        .form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; }
        .form-group.full { grid-column: 1 / -1; }

        label {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
        }
        label .req { color: var(--accent2); margin-left: 3px; }

        input, select, textarea {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        input::placeholder, textarea::placeholder { color: var(--muted); }
        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(124,106,255,0.15);
        }
        input.error-field { border-color: var(--danger); }
        select option { background: var(--surface); }
        textarea { resize: vertical; min-height: 80px; }

        .divider { height: 1px; background: var(--border); margin: 28px 0; grid-column: 1 / -1; }

        .form-footer { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; }
        .btn-submit {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--accent); color: #fff;
            padding: 13px 28px; border-radius: 10px;
            font-size: 0.9rem; font-weight: 600;
            border: none; cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
        }
        .btn-submit:hover { background: #6b5ae0; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(124,106,255,0.35); }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--surface); color: var(--text);
            padding: 13px 24px; border-radius: 10px;
            font-size: 0.9rem; font-weight: 600;
            text-decoration: none; border: 1px solid var(--border);
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
        }
        .btn-back:hover { background: var(--card); }
    </style>
</head>
<body>
<header>
    <div class="logo">Student<span>DB</span></div>
    <nav>
        <a href="index.php">All Records</a>
        <a href="create.php" class="active">Add Student</a>
    </nav>
</header>
<main>
    <div class="breadcrumb">
        <a href="index.php">All Records</a>
        <span>›</span>
        <span>Add New Student</span>
    </div>
    <div class="page-title">Add New Student</div>
    <div class="page-subtitle">Fill in the form below to register a new student.</div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="create.php" novalidate>
            <div class="form-grid">
                <div class="form-group full">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" placeholder="e.g. Juan Dela Cruz" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Age <span class="req">*</span></label>
                    <input type="number" name="age" placeholder="e.g. 20" min="1" max="120" value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Course <span class="req">*</span></label>
                    <select name="course" required>
                        <option value="">Select a course...</option>
                        <?php
                        $courses = ['BSIT','BSCS','BSECE','BSN','BSED','BSA','BSBA','BSCE','BSARCH','BSME','Others'];
                        foreach ($courses as $c):
                            $sel = (isset($_POST['course']) && $_POST['course'] === $c) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $c; ?>" <?php echo $sel; ?>><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full">
                    <label>Address <span class="req">*</span></label>
                    <textarea name="address" placeholder="e.g. 123 Rizal St., Quezon City"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>
                <div class="form-group full">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" name="email" placeholder="e.g. juan@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
            </div>
            <div class="form-footer">
                <a href="index.php" class="btn-back">Cancel</a>
                <button type="submit" class="btn-submit">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    Add Student
                </button>
            </div>
        </form>
    </div>
</main>
</body>
</html>