<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];

// Stats
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM expenses WHERE user_id=$user_id"))['total'] ?? 0;
$count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM expenses WHERE user_id=$user_id"))['cnt'];
$this_month = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM expenses WHERE user_id=$user_id AND MONTH(travel_date)=MONTH(NOW()) AND YEAR(travel_date)=YEAR(NOW())"))['total'] ?? 0;

// Category breakdown
$cat_result = mysqli_query($conn, "SELECT category, SUM(amount) as total FROM expenses WHERE user_id=$user_id GROUP BY category ORDER BY total DESC");

// Recent expenses
$recent = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id=$user_id ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Travel Expense Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0e1a; --card: #111827; --accent: #f0a500; --accent2: #e05c2a; --text: #f0ede8; --muted: #8892a4; --border: #1f2937; }
        body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--text); min-height: 100vh; }
        nav { background: var(--card); border-bottom: 1px solid var(--border); padding: 15px 30px; display: flex; align-items: center; justify-content: space-between; }
        .nav-brand { font-family: 'Playfair Display', serif; color: var(--accent); font-size: 22px; }
        .nav-links a { color: var(--muted); text-decoration: none; margin-left: 25px; font-size: 14px; transition: color 0.2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--accent); }
        .nav-user { color: var(--muted); font-size: 13px; }
        .nav-user span { color: var(--accent); font-weight: 500; }
        .container { max-width: 1100px; margin: 0 auto; padding: 30px 20px; }
        h2 { font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 25px; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 25px; }
        .stat-label { color: var(--muted); font-size: 12px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px; }
        .stat-value { font-size: 32px; font-weight: 500; color: var(--accent); }
        .stat-sub { color: var(--muted); font-size: 12px; margin-top: 5px; }
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 25px; }
        .card h3 { font-size: 16px; font-weight: 500; margin-bottom: 20px; color: var(--muted); letter-spacing: 0.5px; }
        .cat-item { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .cat-name { font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .cat-bar-wrap { flex: 1; margin: 0 12px; height: 6px; background: var(--border); border-radius: 3px; }
        .cat-bar { height: 100%; background: linear-gradient(90deg, var(--accent), var(--accent2)); border-radius: 3px; }
        .cat-amount { font-size: 13px; color: var(--accent); font-weight: 500; white-space: nowrap; }
        table { width: 100%; border-collapse: collapse; }
        th { color: var(--muted); font-size: 11px; letter-spacing: 1px; text-transform: uppercase; text-align: left; padding: 8px 12px; border-bottom: 1px solid var(--border); }
        td { padding: 12px; font-size: 14px; border-bottom: 1px solid rgba(31,41,55,0.5); }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; background: rgba(240,165,0,0.15); color: var(--accent); }
        .empty { color: var(--muted); text-align: center; padding: 30px; font-size: 14px; }
        .btn-add { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--accent), var(--accent2)); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 25px; }
    </style>
</head>
<body>
<nav>
    <div class="nav-brand">✈️ TravelExpense</div>
    <div class="nav-links">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="expenses.php">All Expenses</a>
        <a href="add_expense.php">Add Expense</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="nav-user">Welcome, <span><?= $_SESSION['username'] ?></span></div>
</nav>
<div class="container">
    <h2>Dashboard</h2>
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value">₹<?= number_format($total, 2) ?></div>
            <div class="stat-sub">All time</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">₹<?= number_format($this_month, 2) ?></div>
            <div class="stat-sub"><?= date('F Y') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Trips</div>
            <div class="stat-value"><?= $count ?></div>
            <div class="stat-sub">Expenses recorded</div>
        </div>
    </div>

    <a href="add_expense.php" class="btn-add">+ Add New Expense</a>

    <div class="grid2">
        <div class="card">
            <h3>Spending by Category</h3>
            <?php if (mysqli_num_rows($cat_result) > 0):
                $max = null;
                $cats = [];
                while ($c = mysqli_fetch_assoc($cat_result)) { $cats[] = $c; if (!$max) $max = $c['total']; }
                $icons = ['Food'=>'🍽️','Transport'=>'🚗','Hotel'=>'🏨','Activities'=>'🎯','Shopping'=>'🛍️','Other'=>'📦'];
                foreach ($cats as $c): ?>
                <div class="cat-item">
                    <div class="cat-name"><?= $icons[$c['category']] ?? '📌' ?> <?= $c['category'] ?></div>
                    <div class="cat-bar-wrap"><div class="cat-bar" style="width:<?= ($c['total']/$max)*100 ?>%"></div></div>
                    <div class="cat-amount">₹<?= number_format($c['total'],2) ?></div>
                </div>
            <?php endforeach; else: ?>
                <div class="empty">No expenses yet</div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Recent Expenses</h3>
            <?php if (mysqli_num_rows($recent) > 0): ?>
            <table>
                <tr><th>Title</th><th>Category</th><th>Amount</th></tr>
                <?php while ($e = mysqli_fetch_assoc($recent)): ?>
                <tr>
                    <td><?= htmlspecialchars($e['title']) ?><br><small style="color:var(--muted)"><?= $e['destination'] ?></small></td>
                    <td><span class="badge"><?= $e['category'] ?></span></td>
                    <td style="color:var(--accent)">₹<?= number_format($e['amount'],2) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
                <div class="empty">No expenses yet. <a href="add_expense.php" style="color:var(--accent)">Add one!</a></div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
