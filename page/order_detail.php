<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// (1) Authorization (member / customer)
// ----------------------------------------------------------------------------

$user_id = $_SESSION['user']->user_id ?? null;

if (!$user_id) {
    redirect('login.php');
}

// ----------------------------------------------------------------------------
// (2) Get order id
// ----------------------------------------------------------------------------

$order_id = req('order_id');

if (!$order_id) {
    redirect('order_history.php');
}

// ----------------------------------------------------------------------------
// (3) Fetch order (belong to user)
// ----------------------------------------------------------------------------

$stm = $_db->prepare("
    SELECT 
        o.order_id,
        o.created_at,
        o.total_amount,
        d.status
    FROM orders o
    LEFT JOIN deliveries d 
        ON o.order_id = d.order_id
    WHERE o.order_id = ?
      AND o.user_id = ?
");
$stm->execute([$order_id, $user_id]);
$order = $stm->fetch();

if (!$order) {
    redirect('order_history.php');
}

// ----------------------------------------------------------------------------
// (4) Fetch order items
// ----------------------------------------------------------------------------

$stm = $_db->prepare("
    SELECT 
        p.product_name,
        p.price,
        oi.unit
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stm->execute([$order_id]);
$items = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Order | Detail';
include '../_head.php';
?>

<h2>Order Detail</h2>

<!-- ================= ORDER SUMMARY ================= -->

<table class="table">
    <tr>
        <th>Order ID</th>
        <td><?= encode($order->order_id) ?></td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td><?= date('d-m-Y H:i', strtotime($order->created_at)) ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td><?= encode($order->status ?? 'Processing') ?></td>
    </tr>
    <tr>
        <th>Total Amount</th>
        <td>RM <?= number_format($order->total_amount, 2) ?></td>
    </tr>
</table>

<!-- ================= ORDER ITEMS ================= -->

<h3>Items</h3>

<table class="table">
    <tr>
        <th>Product</th>
        <th class="right">Price (RM)</th>
        <th class="right">Unit</th>
        <th class="right">Subtotal (RM)</th>
    </tr>

    <?php foreach ($items as $i): ?>
    <tr>
        <td><?= encode($i->product_name) ?></td>
        <td class="right"><?= number_format($i->price, 2) ?></td>
        <td class="right"><?= $i->unit ?></td>
        <td class="right">
            <?= number_format($i->price * $i->unit, 2) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- ================= ACTIONS ================= -->

<p>
    <button onclick="location.href='order_history.php'">
        &laquo; Back to Order History
    </button>
</p>

<?php
include '../_foot.php';
