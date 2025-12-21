<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

function update_multiple() {
    global $_db;

    $category_id = req('category_id', []);
    if (!is_array($category_id)) {
        $category_id = [$category_id];
    }
    $selected_field_to_update = req('selected_field_to_update');

    if ($selected_field_to_update != '') {
        $count = 0;

        if ($selected_field_to_update == 'active') {
            $stm = $_db->prepare('
                UPDATE categories
                SET status = 1
                WHERE category_id = ?
            ');
        }

        elseif ($selected_field_to_update == 'inactive') {
            $stm = $_db->prepare('
                UPDATE categories
                SET status = 0
                WHERE category_id = ?
            ');
        }

        foreach ($category_id as $cid) {
            $count += $stm->execute([$cid]);
        }

        temp('info', "$count record(s) updated to $selected_field_to_update!");
        redirect();
    }
}

// ----------------------------------------------------------------------------

// (1) Sorting
$fields = [
    'category_id'   => 'Id',
    'category_name' => 'Name',
    'status'        => 'Status'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'category_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Filtering
$category_name = req('category_name', '');

// (3) Paging
$page = req('page', 1);

require_once '../../lib/SimplePager.php';

// ----------------------------------------------------------------------------

// Build SQL for SimplePager
$baseSQL = "FROM categories WHERE category_name LIKE ?";
$params = ["%$category_name%"];

$sql = "SELECT category_id $baseSQL ORDER BY $sort $dir";

// Sorting applied after pagination
$p = new SimplePager($sql, $params, 10, $page);

// Fetch full product rows AFTER pagination
$arr = [];
foreach ($p->result as $row) {
    $full = $_db->prepare("
        SELECT *
        FROM categories 
        WHERE category_id = ?
    ");
    $full->execute([$row->category_id]);
    $arr[] = $full->fetch();
}

if (isset($_POST['update_multiple'])) {
    update_multiple();
}

// ----------------------------------------------------------------------------

$_title = 'Admin | All Categories';
include '../../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 125px;
    }
</style>

<form>
    <?= html_search('category_name','placeholder="Search category..."') ?>
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
        <th><input type="checkbox" onclick="toggleAll(this, 'category_id[]')"></th>
        <?= table_headers(
                $fields,
                $sort,
                $dir,
                "page={$p->page}&category_name={$category_name}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td>
            <input type="checkbox"
                   name="category_id[]"
                   value="<?= $m->category_id ?>"
                   form="modify_multiple">
        </td>
        <td><?= $m->category_id ?></td>
        <td><?= $m->category_name ?></td>
        <td><?= (int)$m->status === 1 ? 'Active' : 'Inactive' ?></td>
        
        <td>
            <button data-get="/page/admin6699/category_update.php?category_id=<?= $m->category_id ?>">Update</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&category_name=$category_name") ?>

<p>
    <button data-get="/page/admin6699/category_insert.php">Insert</button>
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
</p>

<?php
include '../../_foot.php';