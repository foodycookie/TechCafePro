<?php
include '../_base.php';

auth('customer', 'member');

// ----------------------------------------------------------------------------

$user_id = $_SESSION['user']->user_id ?? null;

// Data filter inputs
$from = req('from');
$to   = req('to');

// Return orders belong to the user (descending)
// orders table:
// - order_id
// - created_at
// - total_amount
// - user_id
// order_items table:
// - order_id
// - quantity
$sql = "
    SELECT 
        o.order_id        AS id,
        o.created_at      AS datetime,
        SUM(oi.unit)  AS count,
        o.total_amount    AS total
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = ?
";

$params = [$user_id];

if ($from) {
    $sql .= " AND o.created_at >= ?";
    $params[] = $from . ' 00:00:00';
}

if ($to) {
    $sql .= " AND o.created_at <= ?";
    $params[] = $to . ' 23:59:59';
}

$sql .= "
    GROUP BY o.order_id, o.created_at, o.total_amount
    ORDER BY o.created_at DESC
";

$stm = $_db->prepare($sql);
$stm->execute($params);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Order | History';
include '../_head.php';
?>

<style>
.right { text-align: right; }
.table td, .table th { vertical-align: middle; }
</style>

<form method="get" class="form" style="margin-bottom:20px">
    <label>From</label>
    <?= html_date('from') ?>

    <label>To</label>
    <?= html_date('to') ?>

    <button>Filter</button>
</form>

<p><?= count($arr) ?> record(s)</p>

<?php if (!$arr): ?>
    <p class="alert">
        You have no orders<?= ($from || $to) ? ' in this period' : '' ?>.
    </p>
<?php endif; ?>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Datetime</th>
        <th>Count</th>
        <th>Total (RM)</th>
        <th></th>
    </tr>

    <?php foreach ($arr as $o): ?>
    <tr>
        <td><?= encode($o->id) ?></td>
        <td><?= date('d-m-Y H:i', strtotime($o->datetime)) ?></td>
        <td class="right"><?= $o->count ?></td>
        <td class="right"><?= number_format($o->total, 2) ?></td>
        <td>
            <button data-get="/page/order_detail.php?order_id=<?= $o->id ?>">
                Detail
            </button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';