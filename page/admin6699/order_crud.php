<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

function update_multiple() {
    global $_db;

    $payment_id = req('payment_id', []);
    if (!is_array($payment_id)) {
        $payment_id = [$payment_id];
    }
    $selected_field_to_update = req('selected_field_to_update');

    if ($selected_field_to_update != '') {
        $count = 0;

        if ($selected_field_to_update == 'pending') {
            $stm = $_db->prepare('
                UPDATE payments
                SET status = "Pending"
                WHERE payment_id = ?
            ');
        }

        elseif ($selected_field_to_update == 'completed') {
            $stm = $_db->prepare('
                UPDATE payments
                SET status = "Completed"
                WHERE payment_id = ?
            ');
        }

        elseif ($selected_field_to_update == 'cancelled') {
            $stm = $_db->prepare('
                UPDATE payments
                SET status = "Cancelled"
                WHERE payment_id = ?
            ');
        }

        foreach ($payment_id as $pid) {
            $count += $stm->execute([$pid]);
        }

        temp('info', "$count record(s) updated to $selected_field_to_update!");
        redirect();
    }
}

// ----------------------------------------------------------------------------

// (1) Sorting
$fields = [
    'payment_id'  => 'Id',
    'amount'      => 'Amount Paid',
    'method'      => 'Pay Method',
    'status'      => 'Status',
    'paid_at'     => 'Paid At',
    'order_id'    => 'Order ID',
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'payment_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once '../../lib/SimplePager.php';

// ----------------------------------------------------------------------------

// Build SQL for SimplePager
$baseSQL = "FROM payments";
$params = [];

// Sorting applied after pagination
$sql = "SELECT payment_id $baseSQL ORDER BY $sort $dir";

// Pager
$p = new SimplePager($sql, $params, 10, $page);

// Fetch full product rows AFTER pagination
$arr = [];
foreach ($p->result as $row) {
    $full = $_db->prepare("
        SELECT p.*
        FROM payments p 
        WHERE p.payment_id = ?
    ");
    $full->execute([$row->payment_id]);
    $arr[] = $full->fetch();
}

if (isset($_POST['update_multiple'])) {
    update_multiple();
}

// ----------------------------------------------------------------------------

$_title = 'Admin | All Paid Orders';
include '../../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 125px;
    }
</style>

<form method="POST" id="modify_multiple">
    <select name="selected_field_to_update">
        <option value="">Select Field</option>
        <option value="pending">Update: Status to Pending</option>
        <option value="completed">Update: Status to Completed</option>
        <option value="cancelled">Update: Status to Cancelled</option>
    </select>

    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Selected</button>
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <th><input type="checkbox" onclick="toggleAll(this, 'payment_id[]')"></th>
        <?= table_headers(
                $fields,
                $sort,
                $dir,
                "page={$p->page}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td>
            <input type="checkbox"
                   name="payment_id[]"
                   value="<?= $m->payment_id ?>"
                   form="modify_multiple">
        </td>
        <td><?= $m->payment_id ?></td>
        <td><?= number_format($m->amount, 2) ?></td>
        <td><?= $m->method ?></td>
        <td><?= $m->status ?></td>
        <td><?= $m->paid_at ?></td>
        <td><?= $m->order_id ?></td>
        <td>
            <button data-get="/page/admin6699/order_detail_admin.php?order_id=<?= $m->order_id ?>">Detail</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir") ?>

<p>
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
</p>

<?php
include '../../_foot.php';