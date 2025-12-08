<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// (1) Sorting
$fields = [
    'product_id'     => 'Id',
    'pro_name'       => 'Product Name',
    'price'          => 'Price',
    'description'    => 'Description',
    'category_id'    => 'Category',
    'is_available'   => 'Available'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'product_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Filtering
$pro_name   = req('pro_name', '');
$category_id = req('category_id', '');

// (3) Paging
$page = req('page', 1);

require_once '../lib/SimplePager.php';

// ----------------------------------------------------------------------------
// Build SQL for SimplePager
$baseSQL = "FROM products WHERE pro_name LIKE ?";
$params = ["%$pro_name%"];

// Category filter
if ($category_id !== '') {
    $baseSQL .= " AND category_id = ?";
    $params[] = $category_id;
}

// Sorting applied after pagination
$sql = "SELECT product_id $baseSQL ORDER BY $sort $dir";

// Pager
$p = new SimplePager($sql, $params, 10, $page);

// Fetch full product rows AFTER pagination
$arr = [];
foreach ($p->result as $row) {
    $full = $_db->prepare("
        SELECT p.*, c.cat_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id = ?
    ");
    $full->execute([$row->product_id]);
    $arr[] = $full->fetch();
}

// Fetch all categories for dropdown
$cats = $_db->query("SELECT * FROM categories ORDER BY cat_name")->fetchAll();

// Insert new category
if (isset($_POST['new_cat'])) {
    $_db->prepare("INSERT INTO categories(cat_name) VALUES(?)")
        ->execute([$_POST['new_cat']]);
    redirect("/page/product_crud.php");
}

// ----------------------------------------------------------------------------

$_title = 'All product';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 125px;
    }
</style>

<form>
    <?= html_search('pro_name','placeholder="Search product..."') ?>

    <select name="category_id">
        <option value="">All Categories</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c->category_id ?>"
                <?= $category_id == $c->category_id ? 'selected' : '' ?>>
                <?= encode($c->cat_name) ?>
            </option>
        <?php endforeach ?>
    </select>

    <button>Search</button>
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <?= table_headers(
                $fields,
                $sort,
                $dir,
                "page={$p->page}&pro_name={$pro_name}&category_id={$category_id}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td><?= $m->product_id ?></td>
        <td><?= $m->pro_name ?></td>
        <td style="text-align: right;"><?= number_format($m->price, 2) ?></td>
        <td><?= $m->description ?></td>
        <td><?= $m->cat_name ?></td>
        <td><?= $m->is_available ?></td>
        <td>
            <button data-get="/page/product_update.php?product_id=<?= $m->product_id ?>">Update</button>
            <button data-post="/page/product_delete.php?product_id=<?= $m->product_id ?>" data-confirm>Delete</button>
            <img src="/photo/<?= $m->photo ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&pro_name=$pro_name&category_id=$category_id") ?>

<p>
    <button data-get="/page/product_insert.php">Insert</button>
    <button data-get="/page/admin_home.php">Back to Home</button>
</p>

<h2>Category</h2>
<form method="post">
    <input type="text" name="new_cat" placeholder="New Category">
    <button>Add</button>
</form>

<table>
<?php foreach ($cats as $c): ?>
<tr><td><?= $c->cat_name ?></td></tr>
<?php endforeach; ?>
</table>

<?php
include '../_foot.php';
?>
