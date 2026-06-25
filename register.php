<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
include 'connection.php';
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username or email already exists!";
    } else {
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $success = "Account created! You can now login.";
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Travel Expense Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0e1a; --card: #111827; --accent: #f0a500; --accent2: #e05c2a; --text: #f0ede8; --muted: #8892a4; --border: #1f2937; }
        body { background: var(--bg); font-family: 'DM Sans', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 50px 45px; width: 420px; box-shadow: 0 25px 60px rgba(0,0,0,0.5); }
        .logo { text-align: center; margin-bottom: 35px; }
        .logo h1 { font-family: 'Playfair Display', serif; color: var(--accent); font-size: 26px; }
        .logo p { color: var(--muted); font-size: 13px; margin-top: 5px; }
        .error { background: rgba(224,92,42,0.15); border: 1px solid rgba(224,92,42,0.3); color: #ff7f5e; padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
        .success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; color: var(--muted); font-size: 12px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px; }
        input { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 13px 16px; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 15px; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: var(--accent); }
        .btn { width: 100%; background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; border-radius: 10px; padding: 14px; color: white; font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 500; cursor: pointer; margin-top: 10px; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.9; }
        .login-link { text-align: center; margin-top: 25px; color: var(--muted); font-size: 13px; }
        .login-link a { color: var(--accent); text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <div style="font-size:40px;margin-bottom:10px">✈️</div>
        <h1>Create Account</h1>
        <p>Start tracking your travel expenses</p>
    </div>
    <?php if ($error): ?><div class="error">⚠️ <?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success">✅ <?= $success ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Your email address" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password" required>
        </div>
        <button type="submit" class="btn">Create Account →</button>
    </form>
    <div class="login-link">Already have an account? <a href="index.php">Login here</a></div>
</div>
</body>
</html>
