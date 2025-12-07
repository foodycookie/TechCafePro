<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_get()) {  // step1 SQL select
    $product_id = req('product_id'); //P009

    $stm = $_db->prepare('SELECT * FROM products WHERE product_id = ?');
    $stm->execute([$product_id]);
    $p = $stm->fetch();

    if (!$p) {   // if no record fouund, go /page/product_crud.php
        temp('info', 'No Record');
        redirect('/page/product_crud.php');
    }

    extract((array)$p);

    $_SESSION['photo'] = $p->photo; 

}

if (is_post()) {   // step3 SQL update
    $product_id     = req('product_id');
    $pro_name       = req('pro_name');
    $price          = req('price');
    $description    = req('description');
    $category_id    = req('category_id');
    $f              = get_file('photo');  // for new uploaded file
    $photo          = $_SESSION['photo']; // current photo filename

    // Validate: name   
    if ($pro_name == '') {
        $_err['pro_name'] = 'Required';
    }
    else if (strlen($pro_name) > 50) {
        $_err['pro_name'] = 'Maximum 50 characters';
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

    // Validate: category
    if ($category_id == '') {
        $_err['category_id'] = 'Required';
    }

    // Validate: photo (file)
    // ** Only if a file is selected **
    if ($f) {  // user got selectnew file to upload
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > 2 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 2MB';
        }
    }

    // DB operation
    if (!$_err) {
        // when no error found
        // Delete photo + save photo
        // ** Only if a file is selected **
        //redirect(); 
        
        if ($f){
            unlink("../photo/$photo");
            $photo = save_photo($f, '../photo');
        }
        
        $stm = $_db->prepare('
            UPDATE products
            SET pro_name = ?, price = ?, description = ?, category_id = ?, photo = ?
            WHERE product_id = ?
        ');
        $stm->execute([$pro_name, $price, $description, $category_id, $photo, $product_id]);

        temp('info', 'Record updated');
        redirect('/page/product_crud.php');
    }
}

$cats = $_db->query("SELECT * FROM categories ORDER BY cat_name")->fetchAll();
// ----------------------------------------------------------------------------

$_title = 'Admin | Product Update';
include '../_head.php';

?>
<form method="post" class="form" enctype="multipart/form-data" novalidate>
    <label for="product_id">Id</label>
    <b><?= $product_id ?></b>
    <br>

    <label for="pro_name">Product Name</label>
    <?= html_text('pro_name', 'maxlength="50"') ?>
    <?= err('pro_name') ?>

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
            <option value="<?= $c->category_id ?>"
                <?= $category_id == $c->category_id ? 'selected' : '' ?>>
                <?= htmlspecialchars($c->cat_name) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?= err('category_id') ?>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="/photo/<?= $photo ?>">
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