<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

function update_multiple() {
    global $_db;

    $user_id = req('user_id', []);
    if (!is_array($user_id)) {
        $user_id = [$user_id];
    }
    $selected_field_to_update = req('selected_field_to_update');

    if ($selected_field_to_update != '') {
        $count = 0;

        if ($selected_field_to_update == 'active') {
            $stm = $_db->prepare('
                UPDATE users
                SET status = 1
                WHERE user_id = ? AND (role = "customer" OR role = "member")
            ');
        }

        elseif ($selected_field_to_update == 'inactive') {
            $stm = $_db->prepare('
                UPDATE users
                SET status = 0
                WHERE user_id = ? AND (role = "customer" OR role = "member")
            ');
        }

        foreach ($user_id as $uid) {
            $count += $stm->execute([$uid]);
        }

        temp('info', "$count record(s) updated to $selected_field_to_update!");
        redirect();
    }
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

// (3) Paging
$page = req('page', 1);

require_once '../../lib/SimplePager.php';

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
    update_multiple();
}

// ----------------------------------------------------------------------------

$_title = 'Admin | All Customers';
include '../../_head.php';
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
    <select name="selected_field_to_update">
        <option value="">Select Field</option>
        <option value="active">Update: Status to Active</option>
        <option value="inactive">Update: Status to Inactive</option>
    </select>

    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Selected</button>
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <th><input type="checkbox" onclick="toggleAll(this, 'user_id[]')"></th>
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
        <td><?= (int)$m->status === 1 ? 'Active' : 'Inactive' ?></td>
        <td>
            <button data-get="/page/admin6699/customer_update.php?user_id=<?= $m->user_id ?>">Update</button>
            <img src="../../images/user_photos/<?= $m->profile_image_path ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&name=$name") ?>

<p>
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
</p>

<?php
include '../../_foot.php';