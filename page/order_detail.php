<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../page/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? '';

if (!$order_id) {
    die("Invalid order");
}

/* =========================
   FETCH ORDER (SECURE)
========================= */
$stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE order_id = ? AND user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found");
}

/* =========================
   FETCH ORDER ITEMS
========================= */
$stmt = $conn->prepare("
    SELECT p.name, oi.quantity, p.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Detail</title>
</head>
<body>

<h2>Order Detail</h2>

<p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
<p><strong>Date:</strong> <?= $order['created_at'] ?></p>
<p><strong>Total:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>

<table border="1" cellpadding="8">
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price (RM)</th>
        <th>Subtotal (RM)</th>
    </tr>

    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="order_history.php">Back to Order History</a>

</body>
</html>
