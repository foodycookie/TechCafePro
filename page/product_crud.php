<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// (1) Sorting
$fields = [
    'product_id'     => 'Id',
    'product_name'   => 'Product Name',
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
$product_name   = req('product_name', '');
$category_id = req('category_id', '');

// (3) Paging
$page = req('page', 1);

require_once '../lib/SimplePager.php';

// ----------------------------------------------------------------------------
// Build SQL for SimplePager
$baseSQL = "FROM products WHERE product_name LIKE ?";
$params = ["%$product_name%"];

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
        SELECT p.*, c.category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id = ?
    ");
    $full->execute([$row->product_id]);
    $arr[] = $full->fetch();
}

// Fetch all categories for dropdown
$cats = $_db->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

// Insert new category
if (isset($_POST['new_cat'])) {
    $new_cat = trim($_POST['new_cat']);

    // Validation
    if ($new_cat === '') {
        temp('info', 'Category name is required');
    } 
    else if (strlen($new_cat) > 50) {
        temp('info', 'Category name too long (max 50 chars)');
    } 
    else {
        // Check uniqueness
        $exists = $_db->prepare("SELECT 1 FROM categories WHERE category_name = ?");
        $exists->execute([$new_cat]);
        if ($exists->fetch()) {
            temp('info', 'Category already exists');
        } else {
            // Insert
            $_db->prepare("INSERT INTO categories(category_name) VALUES(?)")
                ->execute([$new_cat]);
            temp('info', 'Category added');
            redirect("/page/product_crud.php"); // reload page
        }
    }
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
    <?= html_search('product_name','placeholder="Search product..."') ?>

    <select name="category_id">
        <option value="">All Categories</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c->category_id ?>"
                <?= $category_id == $c->category_id ? 'selected' : '' ?>>
                <?= encode($c->category_name) ?>
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
                "page={$p->page}&product_name={$product_name}&category_id={$category_id}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td><?= $m->product_id ?></td>
        <td><?= $m->product_name ?></td>
        <td style="text-align: right;"><?= number_format($m->price, 2) ?></td>
        <td><?= $m->description ?></td>
        <td><?= $m->category_name ?></td>
        <td><?= $m->is_available ?></td>
        <td>
            <button data-get="/page/product_update.php?product_id=<?= $m->product_id ?>">Update</button>
            <button data-post="/page/product_delete.php?product_id=<?= $m->product_id ?>" data-confirm>Delete</button>
            <img src="../images/placeholder/<?= $m->photo ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&product_name=$product_name&category_id=$category_id") ?>

<p>
    <button data-get="/page/product_insert.php">Insert</button>
    <button data-get="/page/admin_home.php">Back to Home</button>
</p>

<h2>Category</h2>
<form method="post">
    <input type="text" name="new_cat" placeholder="New Category"
       value="<?= encode(req('new_cat')) ?>">
    <button>Add</button>
</form>

<table>
<?php foreach ($cats as $c): ?>
<tr>
    <td><?= encode($c->category_name) ?></td>
    <td>
        <button data-post="/page/category_delete.php?category_id=<?= $c->category_id ?>" data-confirm>Delete</button>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php
include '../_foot.php';
?>
