<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'connection.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    
    $query = "SELECT * FROM users WHERE (username='$username' OR email='$username') AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Expense Manager - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0e1a;
            --card: #111827;
            --accent: #f0a500;
            --accent2: #e05c2a;
            --text: #f0ede8;
            --muted: #8892a4;
            --border: #1f2937;
        }
        body {
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(240,165,0,0.08) 0%, transparent 70%);
            top: -100px; right: -100px;
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(224,92,42,0.06) 0%, transparent 70%);
            bottom: -50px; left: -50px;
            border-radius: 50%;
        }
        .container {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 50px 45px;
            width: 420px;
            position: relative;
            z-index: 1;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }
        .logo {
            text-align: center;
            margin-bottom: 35px;
        }
        .logo-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .logo h1 {
            font-family: 'Playfair Display', serif;
            color: var(--accent);
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .logo p {
            color: var(--muted);
            font-size: 13px;
            margin-top: 5px;
            font-weight: 300;
        }
        .error {
            background: rgba(224,92,42,0.15);
            border: 1px solid rgba(224,92,42,0.3);
            color: #ff7f5e;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            transition: border-color 0.2s;
            outline: none;
        }
        input:focus { border-color: var(--accent); }
        .btn {
            width: 100%;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border: none;
            border-radius: 10px;
            padding: 14px;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            letter-spacing: 0.5px;
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .register-link {
            text-align: center;
            margin-top: 25px;
            color: var(--muted);
            font-size: 13px;
        }
        .register-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }
        .demo {
            background: rgba(240,165,0,0.08);
            border: 1px solid rgba(240,165,0,0.2);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 25px;
            font-size: 12px;
            color: var(--muted);
            text-align: center;
        }
        .demo span { color: var(--accent); font-weight: 500; }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <div class="logo-icon">✈️</div>
        <h1>Travel Expense</h1>
        <p>Track your journey, manage your money</p>
    </div>
    <?php if ($error): ?>
        <div class="error">⚠️ <?= $error ?></div>
    <?php endif; ?>
    <div class="demo">Demo: username <span>admin</span> / password <span>admin123</span></div>
    <form method="POST">
        <div class="form-group">
            <label>Username or Email</label>
            <input type="text" name="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn">Sign In →</button>
    </form>
    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>
</body>
</html>
