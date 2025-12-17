<?php
include '../_base.php';

function update_multiple() {
    global $_db;

    $product_id = req('product_id', []);
    if (!is_array($product_id)) {
        $product_id = [$product_id];
    }
    $selected_field_to_update = req('selected_field_to_update');

    if ($selected_field_to_update != '') {
        $count = 0;

        if ($selected_field_to_update == 'available') {
            $stm = $_db->prepare('
                UPDATE products
                SET is_available = 1
                WHERE product_id = ?
            ');
        }

        elseif ($selected_field_to_update == 'unavailable') {
            $stm = $_db->prepare('
                UPDATE products
                SET is_available = 0
                WHERE product_id = ?
            ');
        }
        
        elseif ($selected_field_to_update == 'active') {
            $stm = $_db->prepare('
                UPDATE products
                SET status = 1
                WHERE product_id = ?
            ');
        }

        elseif ($selected_field_to_update == 'inactive') {
            $stm = $_db->prepare('
                UPDATE products
                SET status = 0
                WHERE product_id = ?
            ');
        }

        foreach ($product_id as $pid) {
            $count += $stm->execute([$pid]);
        }

        temp('info', "$count record(s) updated to $selected_field_to_update!");
        redirect();
    }
}

function export_products_csv() {
    global $_db;

    // Check if there is any buffer open
    if (ob_get_level()) {
        // Stop the current output buffer without sending its content to the browser
        // Clear anything that has ben output, so the CSV file exported will not have extra script
        ob_end_clean();
    }

    // Create temp_file (delete when close)
    $temp_file = tmpFile();

    // Header
    fputcsv($temp_file, ['product_id','product_name','price','description','created_at','is_available','photo','sold','status','category_id','product_tags']);

    $products = $_db->query('SELECT * FROM products')->fetchAll();
    $product_tags = $_db->query('SELECT * FROM product_tags')->fetchAll();

    // 2D associative array:
    // $tags_lookup = [
    //     378 => [0 => 43, 1 => 44, 2 => 45],
    //     379 => [0 => 44],
    //     380 => []
    // ]
    // $tags_lookup[378] = [43, 44, 45]
    // $tags_lookup[378][0] = 43
    $tags_lookup = [];
    foreach ($product_tags as $product_tag) {
        $tags_lookup[$product_tag->product_id][] = $product_tag->tag_id;
    }

    foreach ($products as $product) {
        $product_tags_array = $tags_lookup[$product->product_id] ?? [];
        
        fputcsv($temp_file, [$product->product_id,
                             $product->product_name,
                             $product->price,
                             $product->description,
                             $product->created_at,
                             $product->is_available,
                             $product->photo,
                             $product->sold,
                             $product->status,
                             $product->category_id,
                             implode("|", $product_tags_array)]);
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
    $stm1 = $_db->prepare('INSERT INTO products (product_name, price, description, is_available, photo, sold, status, category_id)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stm2 = $_db->prepare('INSERT INTO product_tags (product_id, tag_id)
                           VALUES (?, ?)');
    $exist_product_tag_ids = $_db->query('SELECT tag_id FROM tags')->fetchAll(PDO::FETCH_COLUMN);
    $exist_category_ids    = $_db->query('SELECT category_id FROM categories')->fetchAll(PDO::FETCH_COLUMN);
    $success_count = 0;
    $failed_count = 0;
    $success_tag_count = 0;
    $failed_tag_count = 0;

    // $_FILES['userfile']['tmp_name (or any attribute)']
    //The temporary filename of the file in which the uploaded file was stored on the server
    $handle = fopen($_FILES['import']['tmp_name'], 'r');

    while($data = fgetcsv($handle)) {
        if ($header_row == true) {
            $header_row = false;
            continue;
        }

        if (count($data) != 11) {
            $failed_count++;
            continue;
        }

        $product_id = trim($data[0]);
        $product_name = trim($data[1]);
        $price = trim($data[2]);
        $description = trim($data[3]);
        $created_at = trim($data[4]);
        $is_available = trim($data[5]);
        $photo = trim($data[6]);
        $sold = trim($data[7]);
        $status = trim($data[8]);
        $category_id = trim($data[9]);
        $product_tag_ids = trim($data[10]);

        // Validation: product_name
        if (($product_name === '') || (strlen($product_name) > 50) || (!is_unique($product_name, 'products', 'product_name'))) {
            $failed_count++;
            continue;
        }

        // Validation: price
        if (($price === '') || (!is_money($price)) || (((float)$price < 0.01 || (float)$price > 99.99))) {
            $failed_count++;
            continue;
        }

        // Validation: description
        if (($description === '') || (strlen($description) > 500)) {
            $failed_count++;
            continue;
        }

        // Validation: is_available
        if (($is_available === '') || ($is_available !== '0' && $is_available !== '1')) {
            $failed_count++;
            continue;
        }

        // Validation: sold
        if (($sold === '') || (!ctype_digit($sold))) {
            $failed_count++;
            continue;
        }

        // Validation: status
        if (($status === '') || ($status !== '0' && $status !== '1')) {
            $failed_count++;
            continue;
        }

        // Validation: category_id
        if (($category_id === '') || (!ctype_digit($category_id)) || ((int)$category_id <= 0) || (!in_array($category_id, $exist_category_ids))) {
            $failed_count++;
            continue;
        }

        // TODO: // Validation: photo
        // if (() || () || ()) {
        //     $failed_count++;
        //     continue;
        // }

        // TODO: Process photo

        $success_count += $stm1->execute([$product_name, $price, $description, $is_available, $photo, $sold, $status, $category_id]);

        $last_inserted_product_id = $_db->lastInsertId();

        if ($product_tag_ids !== '') {
            $product_tag_ids = explode('|', $product_tag_ids);

            foreach ($product_tag_ids as $product_tag_id) {
                // Validation: product_tags
                $product_tag_id = trim($product_tag_id);

                // ctype_digit(): Check whether a string consists entirely of digits (0–9)
                // After (int):
                // "43"    = 43
                // "abc"   = 0
                // ""      = 0
                // "12abc" = 12
                // "0"     = 0
                // in_array: Check if a value exists in an array
                if ((ctype_digit($product_tag_id)) && ((int)$product_tag_id > 0) && (in_array($product_tag_id, $exist_product_tag_ids))) {
                    $success_tag_count += $stm2->execute([$last_inserted_product_id, $product_tag_id]);
                }

                else {
                    $failed_tag_count++;
                    continue;
                }
            }
        }
    }

    fclose($handle);
    temp('info', "$success_count record(s) inserted, $failed_count record(s) failed, $success_tag_count tag(s) inserted, $failed_tag_count tag(s) failed!");
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
    'is_available'   => 'Available',
    'status'         => 'Status'
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

if (isset($_POST['update_multiple'])) {
    update_multiple();
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
    <select name="selected_field_to_update">
        <option value="">Select Field</option>
        <option value="available">Update: To Available</option>
        <option value="unavailable">Update: To Unavailable</option>
        <option value="active">Update: To Active</option>
        <option value="inactive">Update: To Inactive</option>
    </select>

    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Multiple</button>
    <!-- <button formaction="product_delete.php" data-confirm>Delete Multiple</button> -->
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <th><input type="checkbox" onclick="toggleAll(this, 'product_id[]')"></th>
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
        <td><?= (int)$m->is_available === 1 ? 'Available' : 'Unavailable' ?></td>
        <td><?= (int)$m->status === 1 ? 'Active' : 'Inactive' ?></td>
        <td>
            <button data-get="/page/product_update.php?product_id=<?= $m->product_id ?>">Update</button>
            <!-- <button data-post="/page/product_delete.php?product_id=<?= $m->product_id ?>" id="delete" data-confirm>Delete</button> -->
            <img src="../images/menu_photos/<?= $m->photo ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&product_name=$product_name&category_id=$category_id") ?>

<p>
    <button data-get="/page/product_insert.php">Insert</button>
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
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

<?php
include '../_foot.php';
?>