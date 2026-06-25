<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
include 'connection.php';
$user_id = $_SESSION['user_id'];
$id = intval($_GET['id'] ?? 0);
$expense = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM expenses WHERE id=$id AND user_id=$user_id"));
if (!$expense) { header("Location: expenses.php"); exit(); }
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $amount = floatval($_POST['amount']);
    $date = mysqli_real_escape_string($conn, $_POST['travel_date']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $q = "UPDATE expenses SET title='$title', category='$category', amount=$amount, travel_date='$date', destination='$destination', description='$description' WHERE id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $q)) { $success = "Expense updated!"; $expense = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM expenses WHERE id=$id")); }
    else { $error = "Update failed."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expense</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0e1a; --card: #111827; --accent: #f0a500; --accent2: #e05c2a; --text: #f0ede8; --muted: #8892a4; --border: #1f2937; }
        body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--text); min-height: 100vh; }
        nav { background: var(--card); border-bottom: 1px solid var(--border); padding: 15px 30px; display: flex; align-items: center; justify-content: space-between; }
        .nav-brand { font-family: 'Playfair Display', serif; color: var(--accent); font-size: 22px; }
        .nav-links a { color: var(--muted); text-decoration: none; margin-left: 25px; font-size: 14px; }
        .nav-links a:hover { color: var(--accent); }
        .container { max-width: 650px; margin: 40px auto; padding: 0 20px; }
        h2 { font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 25px; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 35px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 22px; }
        label { display: block; color: var(--muted); font-size: 12px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px; }
        input, select, textarea { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 13px 16px; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 15px; outline: none; }
        input:focus, select:focus, textarea:focus { border-color: var(--accent); }
        select option { background: var(--card); }
        textarea { resize: vertical; min-height: 90px; }
        .btn { background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; border-radius: 10px; padding: 14px 30px; color: white; font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 500; cursor: pointer; }
        .btn-back { background: var(--border); color: var(--muted); text-decoration: none; border-radius: 10px; padding: 14px 20px; font-size: 15px; margin-right: 12px; }
        .success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
<nav>
    <div class="nav-brand">✈️ TravelExpense</div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="expenses.php">All Expenses</a>
        <a href="add_expense.php">Add Expense</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>
<div class="container">
    <h2>Edit Expense</h2>
    <?php if ($success): ?><div class="success">✅ <?= $success ?></div><?php endif; ?>
    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label>Expense Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($expense['title']) ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <?php foreach (['Food','Transport','Hotel','Activities','Shopping','Other'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= $expense['category']==$cat?'selected':'' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" value="<?= $expense['amount'] ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Travel Date</label>
                    <input type="date" name="travel_date" value="<?= $expense['travel_date'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Destination</label>
                    <input type="text" name="destination" value="<?= htmlspecialchars($expense['destination']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($expense['description'] ?? '') ?></textarea>
            </div>
            <a href="expenses.php" class="btn-back">← Back</a>
            <button type="submit" class="btn">Update Expense →</button>
        </form>
    </div>
</div>
</body>
</html>
