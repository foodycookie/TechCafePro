<?php
include '../_base.php';

$fields = [
    'tag_id' => 'Id',
    'name' => 'Name',
    'category' => 'Category'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'tag_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$category = req('category', '');

$name = req('name', '');

$page = req('page', 1);

require_once '../lib/SimplePager.php';

// ----------------------------------------------------------------------------

$categories = ["Temperature", "Base", "Flavour"]; 

$baseSQL = "FROM tags WHERE name LIKE ?";
$params = ["%$name%"];

if ($category !== '') {
    $baseSQL .= " AND category = ?";
    $params[] = $category;
}

$sql = "SELECT tag_id $baseSQL ORDER BY $sort $dir";

$p = new SimplePager($sql, $params, 10, $page);

$arr = [];
foreach ($p->result as $row) {
    $full = $_db->prepare("
        SELECT *
        FROM tags 
        WHERE tag_id = ?
    ");
    $full->execute([$row->tag_id]);
    $arr[] = $full->fetch();
}

// ----------------------------------------------------------------------------

$_title = 'All tags';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 125px;
    }
</style>

<form>
    <?= html_search('name','placeholder="Search tag..."') ?>

    <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c ?>"
                <?= $category == $c ? 'selected' : '' ?>>
                <?= encode($c) ?>
            </option>
        <?php endforeach ?>
    </select>

    <button>Search</button>
</form>

<form method="POST" id="modify_multiple">
    <button formaction="tag_delete.php" data-confirm>Delete Multiple</button>
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <th><input type="checkbox" onclick="toggleAll(this, 'tag_id[]')"></th>
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
                   name="tag_id[]"
                   value="<?= $m->tag_id ?>"
                   form="modify_multiple">
        </td>
        <td><?= $m->tag_id ?></td>
        <td><?= $m->name ?></td>
        <td><?= $m->category ?></td>
        <td>
            <button data-get="/page/tag_update.php?tag_id=<?= $m->tag_id ?>">Update</button>
            <button data-post="/page/tag_delete.php?tag_id=<?= $m->tag_id ?>" id="delete" data-confirm>Delete</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&name=$name") ?>

<p>
    <button data-get="/page/tag_insert.php">Insert</button>
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
</p>

<?php
include '../_foot.php';
?>