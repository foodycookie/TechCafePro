<?php
include '../_base.php';

$category_id     = req('category_id');

$stm = $_db->prepare('SELECT category_name FROM categories WHERE category_id = ?');
$stm->execute([$category_id]);
$old_category = $stm->fetch();

if (is_get()) {
    $category_id = req('category_id');

    $stm = $_db->prepare('SELECT * FROM categories WHERE category_id = ?');
    $stm->execute([$category_id]);
    $p = $stm->fetch();

    if (!$p) {
        temp('info', 'No Record');
        redirect('/page/category_crud.php');
    }

    extract((array)$p);
}

if (is_post()) {
    $category_id = req('category_id');
    $category_name = req('category_name');
    $is_active = req('is_active');

    if ($category_name == '') {
        $_err['category_name'] = 'Required';
    }
    else if (strlen($category_name) > 50) {
        $_err['category_name'] = 'Maximum 50 characters';
    }
    else if ((strcasecmp($category_name, $old_category->category_name) != 0) && (!is_unique($category_name, 'categories', 'category_name'))) {
        $_err['category_name'] = 'Duplicated';
    }

    if ($is_active == '') {
        $_err['is_active'] = 'Required';
    }

    if (!$_err) {
        $stm = $_db->prepare('
            UPDATE categories
            SET category_name = ?, is_active = ?
            WHERE category_id = ?
        ');
        $stm->execute([$category_name, $is_active, $category_id]);

        temp('info', 'Record updated');
        redirect('/page/category_crud.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Admin | Category Update';
include '../_head.php';

?>

<form method="post" class="form">
    <label for="category_id">Id</label>
    <b><?= $category_id ?></b>
    <br>

    <label for="category_name">Name</label>
    <?= html_text('category_name', 'maxlength="50"') ?>
    <?= err('category_name') ?>

    <label for="is_active">Active</label>
    <?= html_radios('is_active', array("1"=>"Active", "0"=>"Inactive"), false) ?>
    <?= err('is_active') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<p>
    <button data-get="/page/category_crud.php">Back</button>
</p>

<?php
include '../_foot.php';