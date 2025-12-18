<?php
require '../_base.php';

auth2('Member');

// Get order_id from URL (not from temp/session)
$order_id = req('order_id');
if (!$order_id || !is_numeric($order_id)) {
    redirect('cart.php');
}

// Fetch order with correct column name
$stm = $_db->prepare('SELECT * FROM orders WHERE order_id = ? AND user_id = ?');
$stm->execute([$order_id, $_user->user_id]);
$order = $stm->fetch();

if (!$order) {
    redirect('cart.php');
}

// Fetch order items
$stm = $_db->prepare('
    SELECT oi.*, p.product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
');
$stm->execute([$order_id]);
$items = $stm->fetchAll();

// Fetch payment info
$stm = $_db->prepare('SELECT * FROM payments WHERE order_id = ?');
$stm->execute([$order_id]);
$payment = $stm->fetch();

if (!empty(get_chosen_cart_item_for_order())) {
    foreach (get_chosen_cart_item_for_order() as $product_id => $quantity) {
        update_cart($product_id, 0);
    }
}

set_chosen_cart_item_for_order();
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>TechCafe</h1>
            <p>E-Receipt</p>
        </div>


        <div class="receipt-info">
            <h2>Thank You for Your Order!</h2>
            <p><strong>Order ID:</strong> #<?= htmlspecialchars($order->order_id) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($order->created_at) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment->method ?? 'Pending') ?></p>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Price (RM)</th>
                    <th>Subtotal (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item->product_name) ?></td>
                    <td><?= $item->unit ?></td>
                    <td><?= number_format($item->price, 2) ?></td>
                    <td><?= number_format($item->subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="receipt-total">
            <p>Total Items: <strong><?= $order->count ?></strong></p>
            <p>Total Amount: <strong>RM <?= number_format($order->total_amount, 2) ?></strong></p>
        </div>

        <div class="receipt-footer">
            <p>Thank you for your order ☕</p>
            <p>Please prepare cash upon delivery.</p>
        </div>
    </div>

    <p>
        <button onclick="window.location.href='/page/order_history.php'" style="text-decoration:none; cursor:pointer;">
            View Order History
        </button>
        <button onclick="window.location.href='/page/menu.php'" style="text-decoration:none; cursor:pointer;">
            Continue Shopping
        </button>
    </p>
</body>
</html>