<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

$order_id = req('order_id');

if (!$order_id) {
    redirect('/page/admin6699/order_crud.php');
}

$stm = $_db->prepare("
    SELECT 
        o.order_id,
        o.created_at,
        o.total_amount,
        p.status
    FROM orders o
    LEFT JOIN payments p ON p.order_id = o.order_id
    WHERE o.order_id = ?
");
$stm->execute([$order_id]);
$order = $stm->fetch();

if (!$order) {
    redirect('/page/admin6699/order_crud.php');
}

// Fetch order items
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

$_title = 'Admin | Order Details';
include '../../_head.php';
?>

<h2>Order Detail</h2>

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

<p>
    <button onclick="location.href='/page/admin6699/order_crud.php'">
        &laquo; Back to Order CRUD
    </button>
</p>

<?php
include '../../_foot.php';