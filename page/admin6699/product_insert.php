<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    // $product_id = req('product_id');
    $product_name  = req('product_name');
    $price         = req('price');
    $description   = req('description');
    $category_id   = req('category_id');
    $is_available  = req('is_available');
    $status        = req('status');
    $f             = get_file('photo');
    $tag_id        = req('tag_id', []);

    if (!is_array($tag_id)) {
        $tag_id = [$tag_id];
    }

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

    // Validate: is_available
    if ($is_available == '') {
        $_err['is_available'] = 'Required';
    }

    // Validate: status
    if ($status == '') {
        $_err['status'] = 'Required';
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

    // Validate: tags
    if (!is_array($tag_id)) {
        $_err['choose_tags'] = 'Not an array';
    }

    // DB operation
    if (!$_err) {
        // Save photo
        $photo = save_photo($f, '../../images/menu_photos');

        $stm = $_db->prepare('
            INSERT INTO products (product_name, price, description, category_id, is_available, status, photo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stm->execute([$product_name, $price, $description, $category_id, $is_available, $status, $photo]);

        $last_inserted_product_id = $_db->lastInsertId();

        foreach ($tag_id as $individual_tag_id) {
            $stm = $_db->prepare('
                INSERT INTO product_tags (product_id, tag_id)
                VALUES (?, ?)
            ');
            $stm->execute([$last_inserted_product_id, $individual_tag_id]);
        }

        temp('info', 'Record inserted');
        redirect('/page/admin6699/product_crud.php');
    }
}

$cats = $_db->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

$temperature_tags = $_db->query("SELECT * FROM tags WHERE category = 'Temperature'")->fetchAll();
$base_tags        = $_db->query("SELECT * FROM tags WHERE category = 'Base'")       ->fetchAll();
$flavour_tags     = $_db->query("SELECT * FROM tags WHERE category = 'Flavour'")    ->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Admin| Product Insert';
include '../../_head.php';
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

    <label for="is_available">Availability</label>
    <?= html_radios('is_available', array("1"=>"Available", "0"=>"Unavailable"), false) ?>
    <?= err('is_available') ?>

    <label for="status">Status</label>
    <?= html_radios('status', array("1"=>"Active", "0"=>"Inactive"), false) ?>
    <?= err('status') ?>

    <br>
    <button type="button" id="choose_tags" name="choose_tags" onclick="toggle_visibility('insert_product_tags')">Choose Tags</button>
    <?= err('choose_tags') ?>
    <div class="filter_popup" id="insert_product_tags" name="insert_product_tags">
        <h2>Filter Menu</h2>

        <?php if (!empty($temperature_tags)): ?>
            <h3>Temperature</h3>
            <?php foreach ($temperature_tags as $temperature_tag): ?>
                <?php $temperature_tag_array["$temperature_tag->tag_id"] = "$temperature_tag->name"; ?>
            <?php endforeach; ?>
            <?= html_checkboxes('tag_id', $temperature_tag_array, false); ?>
        <?php endif ?>

        <?php if (!empty($base_tags)): ?>
            <h3>Base</h3>
            <?php foreach ($base_tags as $base_tag): ?>
                <?php $base_tag_array["$base_tag->tag_id"] = "$base_tag->name"; ?>
            <?php endforeach; ?>
            <?= html_checkboxes('tag_id', $base_tag_array, false) ?>
        <?php endif ?>

        <?php if (!empty($flavour_tags)): ?>
            <h3>Flavour</h3>
            <?php foreach ($flavour_tags as $flavour_tag): ?>
                <?php $flavour_tag_array["$flavour_tag->tag_id"] = "$flavour_tag->name"; ?>
            <?php endforeach; ?>
            <?= html_checkboxes('tag_id', $flavour_tag_array, false) ?>
        <?php endif ?>

        <?php if ((count($temperature_tags) == 0) && (count($base_tags) == 0) && (count($flavour_tags) == 0)): ?>
            <h3>No Tag Available!</h3>
        <?php endif; ?>

        <br>
        <button type="button" onclick="toggle_visibility('insert_product_tags')">Close</button>
    </div>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="../../images/system/placeholder.jpg">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<p>
    <button data-get="/page/admin6699/product_crud.php">Back</button>
</p>

<?php
include '../../_foot.php';