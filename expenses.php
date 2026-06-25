<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
include 'connection.php';
$user_id = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM expenses WHERE id=$id AND user_id=$user_id");
    header("Location: expenses.php");
    exit();
}

$filter = "";
if (!empty($_GET['category'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['category']);
    $filter = "AND category='$cat'";
}

$expenses = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id=$user_id $filter ORDER BY travel_date DESC");
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as t FROM expenses WHERE user_id=$user_id $filter"))['t'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Expenses - Travel Expense Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --bg: #0a0e1a; --card: #111827; --accent: #f0a500; --accent2: #e05c2a; --text: #f0ede8; --muted: #8892a4; --border: #1f2937; }
        body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--text); min-height: 100vh; }
        nav { background: var(--card); border-bottom: 1px solid var(--border); padding: 15px 30px; display: flex; align-items: center; justify-content: space-between; }
        .nav-brand { font-family: 'Playfair Display', serif; color: var(--accent); font-size: 22px; }
        .nav-links a { color: var(--muted); text-decoration: none; margin-left: 25px; font-size: 14px; }
        .nav-links a:hover, .nav-links a.active { color: var(--accent); }
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .top-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; }
        h2 { font-family: 'Playfair Display', serif; font-size: 28px; }
        .btn-add { background: linear-gradient(135deg, var(--accent), var(--accent2)); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 500; }
        .filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .filter-btn { padding: 7px 16px; border-radius: 20px; border: 1px solid var(--border); background: transparent; color: var(--muted); font-family: 'DM Sans', sans-serif; font-size: 13px; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .filter-btn:hover, .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .total-bar { padding: 15px 25px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .total-bar span { color: var(--muted); font-size: 13px; }
        .total-bar strong { color: var(--accent); font-size: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th { color: var(--muted); font-size: 11px; letter-spacing: 1px; text-transform: uppercase; text-align: left; padding: 12px 20px; border-bottom: 1px solid var(--border); }
        td { padding: 14px 20px; font-size: 14px; border-bottom: 1px solid rgba(31,41,55,0.5); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; background: rgba(240,165,0,0.15); color: var(--accent); }
        .amount { color: var(--accent); font-weight: 500; }
        .actions a { color: var(--muted); text-decoration: none; font-size: 13px; margin-right: 12px; transition: color 0.2s; }
        .actions a:hover { color: var(--accent); }
        .actions a.del:hover { color: #ff7f5e; }
        .empty { text-align: center; padding: 50px; color: var(--muted); }
        .empty a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
<nav>
    <div class="nav-brand">✈️ TravelExpense</div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="expenses.php" class="active">All Expenses</a>
        <a href="add_expense.php">Add Expense</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>
<div class="container">
    <div class="top-bar">
        <h2>All Expenses</h2>
        <a href="add_expense.php" class="btn-add">+ Add Expense</a>
    </div>
    <div class="filters">
        <a href="expenses.php" class="filter-btn <?= empty($_GET['category']) ? 'active' : '' ?>">All</a>
        <?php foreach (['Food','Transport','Hotel','Activities','Shopping','Other'] as $cat): ?>
        <a href="expenses.php?category=<?= $cat ?>" class="filter-btn <?= ($_GET['category'] ?? '') == $cat ? 'active' : '' ?>"><?= $cat ?></a>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <div class="total-bar">
            <span><?= mysqli_num_rows($expenses) ?> expenses found</span>
            <strong>Total: ₹<?= number_format($total, 2) ?></strong>
        </div>
        <?php if (mysqli_num_rows($expenses) > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Destination</th>
                <th>Category</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
            <?php while ($e = mysqli_fetch_assoc($expenses)): ?>
            <tr>
                <td><?= htmlspecialchars($e['title']) ?><br><small style="color:var(--muted)"><?= htmlspecialchars($e['description'] ?? '') ?></small></td>
                <td><?= htmlspecialchars($e['destination']) ?></td>
                <td><span class="badge"><?= $e['category'] ?></span></td>
                <td><?= date('d M Y', strtotime($e['travel_date'])) ?></td>
                <td class="amount">₹<?= number_format($e['amount'], 2) ?></td>
                <td class="actions">
                    <a href="edit_expense.php?id=<?= $e['id'] ?>">✏️ Edit</a>
                    <a href="expenses.php?delete=<?= $e['id'] ?>" class="del" onclick="return confirm('Delete this expense?')">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <div class="empty">No expenses found. <a href="add_expense.php">Add your first one!</a></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
