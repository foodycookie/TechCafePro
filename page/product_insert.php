<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    //$product_id  = req('product_id');  auto generated
    $product_name  = req('product_name');
    $price         = req('price');
    $description   = req('description');
    $category_id   = req('category_id');
    $f             = get_file('photo');

    // Validate: name
    if ($product_name == '') {
        $_err['product_name'] = 'Required';
    }
    else if (strlen($product_name) > 50) {
        $_err['product_name'] = 'Maximum 50 characters';
    }
    else if (!is_unique($product_name, 'products', 'product_name')) {
        $_err['product_name'] = 'Duplicated';
    }

    // Validate: price
    if ($price == '') {
        $_err['price'] = 'Required';
    }
    else if (!is_money($price)) {
        $_err['price'] = 'Must be money';
    }
    else if ($price < 0.01 || $price > 99.99) {
        $_err['price'] = 'Must between 0.01 - 99.99';
    }

    // Validate: description
    if ($description == '') {
        $_err['description'] = 'Required';
    }

    // Validate: category
    if ($category_id == '') {
        $_err['category_id'] = 'Required';
    }

    // Validate: photo (file)
    if (!$f) {
        $_err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    }
    else if ($f->size > 2 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 2MB';
    }

    // DB operation
    if (!$_err) {
        // Save photo
        $photo = save_photo($f, '../images/menu_photos');

        $stm = $_db->prepare('
            INSERT INTO products (product_name, price, description, category_id, photo)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stm->execute([$product_name, $price, $description, $category_id, $photo]);

        temp('info', 'Record inserted');
        redirect('/page/product_crud.php');
    }
}

$cats = $_db->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();
// ----------------------------------------------------------------------------

$_title = 'Admin| Product Insert';
include '../_head.php';

?>
<form method="post" class="form" enctype="multipart/form-data" novalidate>

    <label for="product_name">Product Name</label>
    <?= html_text('product_name', 'maxlength="50"') ?>
    <?= err('product_name') ?>

    <label for="price">Price</label>
    <?= html_number('price', 0.01, 99.99, 0.01) ?>
    <?= err('price') ?>

    <label for="description">Description</label>
    <?= html_textarea('description', 'maxlength="500"') ?>
    <?= err('description') ?>

    <label for="category_id">Category</label>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php foreach ($cats as $c): ?>
                <option value="<?= $c-> category_id ?>"
                    <?= req('category_id') == $c-> category_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c-> category_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?= err('category_id') ?>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="../images/system/placeholder.jpg">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>
<p>
    <button data-get="/page/product_crud.php">Back</button>
</p>
<?php
include '../_foot.php';