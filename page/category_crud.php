<?php
include '../_base.php';

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
            $active = 0;

            $stm = $_db->prepare('SELECT is_active
                                  FROM categories
                                  WHERE category_id = ?');

            foreach ($category_id as $cid) {
                $stm->execute([$cid]);
                $data = $stm->fetch();

                if ($data->is_active == 0) {
                    $active = 1;
                    break;
                }
                else {
                    $active = 0;
                }
            }

            $stm = $_db->prepare('
                UPDATE categories
                SET is_active = ?
                WHERE category_id = ?
            ');

            foreach ($category_id as $cid) {
                $count += $stm->execute([$active, $cid]);
            }
        }

        temp('info', "$count record(s) $selected_field_to_update updated!");
        redirect();
    }
}

$fields = [
    'category_id' => 'Id',
    'category_name' => 'Name',
    'is_active' => 'Active'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'category_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$category_name = req('category_name', '');

$page = req('page', 1);

require_once '../lib/SimplePager.php';

// ----------------------------------------------------------------------------
$baseSQL = "FROM categories WHERE category_name LIKE ?";
$params = ["%$category_name%"];

$sql = "SELECT category_id $baseSQL ORDER BY $sort $dir";

$p = new SimplePager($sql, $params, 10, $page);

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

$_title = 'All categories';
include '../_head.php';
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
        <option value="active">Active</option>
    </select>

    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Multiple</button>
    <!-- <button formaction="category_delete.php" data-confirm>Delete Multiple</button> -->
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
        <td><?= $m->is_active ?></td>
        <td>
            <button data-get="/page/category_update.php?category_id=<?= $m->category_id ?>">Update</button>
            <!-- <button data-post="/page/category_delete.php?category_id=<?= $m->category_id ?>" data-confirm>Delete</button> -->
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&category_name=$category_name") ?>

<p>
    <button data-get="/page/category_insert.php">Insert</button>
    <button data-get="/page/admin_home.php">Back to Home</button>
</p>

<?php
include '../_foot.php';
?>