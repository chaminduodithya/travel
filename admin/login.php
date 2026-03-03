<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

// Check login — use REQUEST_METHOD not isset($_POST['login'])
// because a disabled button won't send its name in POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Hardcoded credentials (change as needed)
    $admin_user = 'admin';
    $admin_pass = 'admin123';

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid username or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — travel.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="login-page">

    <div class="login-container">
        <div class="login-brand">
            <a href="../home.php" class="brand-logo">travel.</a>
            <span class="brand-sub">Admin Portal</span>
        </div>

        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to your admin dashboard</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="login-form" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            required
                            autocomplete="username"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-pw" id="togglePw" title="Show/Hide password">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login" id="loginBtn">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                <a href="../home.php"><i class="fas fa-home"></i> Back to Website</a>
            </div>
        </div>

        <div class="login-hint">
            <i class="fas fa-info-circle"></i>
            Default credentials: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </div>

    <div class="login-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePw').addEventListener('click', function () {
            const pw   = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pw.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        // Add loading state on submit (don't disable — disabled inputs aren't submitted)
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = '<span>Signing in…</span><i class="fas fa-spinner fa-spin"></i>';
            // Note: do NOT set btn.disabled = true here, or the form name won't be sent
        });
    </script>
</body>
</html>
