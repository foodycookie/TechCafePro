<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../page/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =========================
   FILTER / SORT / PAGING
========================= */
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$sort = $_GET['sort'] ?? 'date_desc';

$orderBy = "created_at DESC";
if ($sort === 'date_asc') $orderBy = "created_at ASC";
if ($sort === 'amount_desc') $orderBy = "total_amount DESC";
if ($sort === 'amount_asc') $orderBy = "total_amount ASC";

$page  = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;

/* =========================
   BUILD SQL
========================= */
$where = "WHERE user_id = ?";
$params = [$user_id];

if ($from) {
    $where .= " AND created_at >= ?";
    $params[] = $from . " 00:00:00";
}
if ($to) {
    $where .= " AND created_at <= ?";
    $params[] = $to . " 23:59:59";
}

/* COUNT */
$countStmt = $conn->prepare("SELECT COUNT(*) FROM orders $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $limit);

/* FETCH */
$stmt = $conn->prepare("
    SELECT order_id, total_amount, created_at
    FROM orders
    $where
    ORDER BY $orderBy
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
</head>
<body>

<h2>My Order History</h2>

<form method="get">
    From:
    <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
    To:
    <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">

    Sort:
    <select name="sort">
        <option value="date_desc" <?= $sort=='date_desc'?'selected':'' ?>>Newest</option>
        <option value="date_asc" <?= $sort=='date_asc'?'selected':'' ?>>Oldest</option>
        <option value="amount_desc" <?= $sort=='amount_desc'?'selected':'' ?>>Amount High → Low</option>
        <option value="amount_asc" <?= $sort=='amount_asc'?'selected':'' ?>>Amount Low → High</option>
    </select>

    <button type="submit">Apply</button>
</form>

<br>

<table border="1" cellpadding="8">
    <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Total (RM)</th>
        <th>Action</th>
    </tr>

    <?php if (empty($orders)): ?>
        <tr><td colspan="4">No orders found</td></tr>
    <?php endif; ?>

    <?php foreach ($orders as $o): ?>
        <tr>
            <td><?= $o['order_id'] ?></td>
            <td><?= $o['created_at'] ?></td>
            <td><?= number_format($o['total_amount'], 2) ?></td>
            <td>
                <a href="order_detail.php?order_id=<?= $o['order_id'] ?>">View</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- PAGING -->
<div style="margin-top:15px;">
<?php for ($i=1; $i<=$totalPages; $i++): ?>
    <a href="?page=<?= $i ?>&from=<?= $from ?>&to=<?= $to ?>&sort=<?= $sort ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>

<br>
<a href="profile.php">Back to Profile</a>

</body>
</html>
