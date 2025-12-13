<?php
include '../_base.php';

function update_availability() {
    global $_db;

    $product_id = req('product_id', []);
    if (!is_array($product_id)) $product_id = [$product_id];

    $stm = $_db->prepare('
        SELECT is_available
        FROM products
        WHERE product_id = ?
    ');

    foreach ($product_id as $v) {
        $stm->execute([$v]);
        $data = $stm->fetch();

        if ($data->is_available == 0) {
            $availability = 1;
            break;
        }
        else {
            $availability = 0;
        }
    }

    $stm = $_db->prepare('
        UPDATE products
        SET is_available = ?
        WHERE product_id = ?
    ');
    $count = 0;

    foreach ($product_id as $v) {
        $count += $stm->execute([$availability, $v]);
    }

    temp('info', "$count record(s) updated!");
    redirect();
}

function export_products_csv() {
    global $_db;

    $temp_file = tmpFile();
    fwrite($temp_file, "product_id,product_name,price,description,created_at,is_available,photo,sold,category_id\n");

    $stm = $_db->prepare('SELECT * FROM products');
    $stm->execute();
    $products = $stm->fetchAll();

    foreach ($products as $product) {
        // product_id,product_name,price,description,created_at,is_available,photo,sold,category_id
        fwrite($temp_file, "$product->product_id,$product->product_name,$product->price,$product->description,$product->created_at,$product->is_available,$product->photo,$product->sold,$product->category_id\n");
    }

    export($temp_file, "products.csv");
}

function import_products_csv() {
    if ($_FILES['import']['type'] != 'text/csv') {
        temp('info', 'Not a CSV file!');
        redirect();
    }

    global $_db;

    $header_row = true;
    $stm = $_db->prepare('
        INSERT INTO products (product_name, price, description, is_available, photo, category_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $count = 0;

    // $_FILES['userfile']['tmp_name']
    //The temporary filename of the file in which the uploaded file was stored on the server
    $handle = fopen($_FILES['import']['tmp_name'], 'r');

    // product_id,product_name,price,description,created_at,is_available,photo,sold,category_id
    while($data = fgetcsv($handle)) {
        if ($header_row == true) {
            $header_row = false;
            continue;
        }

        // IM NOT DOING THE VALIDATION :(((
        // if (count($data) != 9) {
        //     continue;
        // }
        
        $count += $stm->execute([$data[1], $data[2], $data[3], $data[5], $data[6], $data[8]]);            
    }

    fclose($handle);
    temp('info', '$n record(s) inserted!');
    redirect();
}

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

if (isset($_POST['update_multiple'])) {
    update_availability();
}

if (isset($_POST['export'])) {
    export_products_csv();
}

if (isset($_POST['import_submit'])) {
    import_products_csv();
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

<form method="POST" id="modify_multiple">
    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Multiple Availability</button>
    <button formaction="product_delete.php" data-confirm>Delete Multiple</button>
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
                "page={$p->page}&product_name={$product_name}&category_id={$category_id}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td>
            <input type="checkbox"
                   name="product_id[]"
                   value="<?= $m->product_id ?>"
                   form="modify_multiple">
        </td>
        <td><?= $m->product_id ?></td>
        <td><?= $m->product_name ?></td>
        <td style="text-align: right;"><?= number_format($m->price, 2) ?></td>
        <td><?= $m->description ?></td>
        <td><?= $m->category_name ?></td>
        <td><?= $m->is_available ?></td>
        <td>
            <button data-get="/page/product_update.php?product_id=<?= $m->product_id ?>">Update</button>
            <button data-post="/page/product_delete.php?product_id=<?= $m->product_id ?>" id="delete" data-confirm>Delete</button>
            <img src="../images/menu_photos/<?= $m->photo ?>" class="popup">
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

<!-- Export -->
<form method="POST">
    <button type="submit" id="export" name="export">Export Table to CSV File</button>
</form>
<script>
    //Select button by id
    const export_button = document.getElementById('export');
    //Add on click listener for button
    export_button.addEventListener('click', function() {
        //Select button by id, and then change it's value
        document.getElementById('export').innerText = "File Exported!"
    })
</script>

<!-- Batch insertion -->
<form method="post" enctype="multipart/form-data">
    <label for="import">Insert CSV File</label>
    <?= html_file('import', '.csv') ?>
    <?= err('import') ?>
    <section>
        <button type="submit" id="import_submit" name="import_submit">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

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
        <button data-post="/page/product_delete.php?category_id=<?= $c->category_id ?>" data-confirm>Delete</button>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php
include '../_foot.php';
?>