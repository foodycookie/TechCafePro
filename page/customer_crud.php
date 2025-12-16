<?php
include '../_base.php';

function update_availability() {
    global $_db;

    $user_id = req('user_id', []);
    if (!is_array($user_id)) $user_id = [$user_id];

    $stm = $_db->prepare('
        SELECT status
        FROM users
        WHERE user_id = ? AND (role = "customer" OR role = "member")
    ');

    foreach ($user_id as $v) {
        $stm->execute([$v]);
        $data = $stm->fetch();

        if ($data->status == 0) {
            $availability = 1;
            break;
        }
        else {
            $availability = 0;
        }
    }

    $stm = $_db->prepare('
        UPDATE users
        SET status = ?
        WHERE user_id = ? AND (role = "customer" OR role = "member")
    ');
    $count = 0;

    foreach ($user_id as $v) {
        $count += $stm->execute([$availability, $v]);
    }

    temp('info', "$count record(s) updated!");
    redirect("./customer_crud.php");
}
// ----------------------------------------------------------------------------
// (1) Sorting
$fields = [
    'user_id'        => 'Id',
    'name'           => 'Name',
    'email'          => 'Email',
    'role'           => 'Role',
    'status'         => 'Status',
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'user_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Filtering
$name   = req('name', '');
$user_id = req('user_id', '');

// (3) Paging
$page = req('page', 1);

require_once '../lib/SimplePager.php';

// ----------------------------------------------------------------------------
// Build SQL for SimplePager
$baseSQL = "FROM users WHERE (role = 'customer' OR role = 'member') AND name LIKE ?";
$params = ["%$name%"];

// Sorting applied after pagination
$sql = "SELECT user_id $baseSQL ORDER BY $sort $dir";

// Pager
$p = new SimplePager($sql, $params, 10, $page);

// Fetch full product rows AFTER pagination
$arr = [];
foreach ($p->result as $row) {
    $full = $_db->prepare("
        SELECT u.*
        FROM users u 
        WHERE u.user_id = ?
    ");
    $full->execute([$row->user_id]);
    $arr[] = $full->fetch();
}

if (isset($_POST['update_multiple'])) {
    update_availability();
}

// ----------------------------------------------------------------------------

$_title = 'All customer';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 125px;
    }
</style>

<form>
    <?= html_search('name','placeholder="Search user..."') ?>

    <button>Search</button>
</form>
<form method="POST" id="modify_multiple">
    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Multiple Availability</button>
    <button formaction="customer_delete.php" data-confirm>Delete Multiple</button>
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <th></th>
        <?= table_headers(
                $fields,
                $sort,
                $dir,
                "page={$p->page}&name={$name}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td>
            <input type="checkbox"
                   name="user_id[]"
                   value="<?= $m->user_id ?>"
                   form="modify_multiple">
        </td>
        <td><?= $m->user_id ?></td>
        <td><?= $m->name ?></td>
        <td><?= $m->email ?></td>
        <td><?= $m->role ?></td>
        <td><?= $m->status ?></td>
        <td>
            <button data-get="/page/customer_update.php?user_id=<?= $m->user_id ?>">Update</button>
            <button data-post="/page/customer_delete.php?user_id=<?= $m->user_id ?>" id="delete" data-confirm>Delete</button>
            <img src="../../images/user_photos/<?= $m->photo ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&name=$name&user_id=$user_id") ?>

<p>
    <!-- <button data-get="/customer_insert.php">Insert</button> -->
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
</p>


<?php
include '../_foot.php';
?>
