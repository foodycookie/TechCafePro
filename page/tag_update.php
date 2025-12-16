<?php
include '../_base.php';

$tag_id     = req('tag_id');

$stm = $_db->prepare('SELECT name FROM tags WHERE tag_id = ?');
$stm->execute([$tag_id]);
$old_tag = $stm->fetch();

if (is_get()) {
    $tag_id = req('tag_id');

    $stm = $_db->prepare('SELECT * FROM tags WHERE tag_id = ?');
    $stm->execute([$tag_id]);
    $p = $stm->fetch();

    if (!$p) {
        temp('info', 'No Record');
        redirect('/page/tag_crud.php');
    }

    extract((array)$p);
}

if (is_post()) {
    $tag_id = req('tag_id');
    $name = req('name');
    $category = req('category');

    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 50) {
        $_err['name'] = 'Maximum 50 characters';
    }
    else if ((strcasecmp($name, $old_tag->name) != 0) && (!is_unique($name, 'tags', 'name'))) {
        $_err['name'] = 'Duplicated';
    }

    if ($category == '') {
        $_err['category'] = 'Required';
    }

    if (!$_err) {
        $stm = $_db->prepare('
            UPDATE tags
            SET name = ?, category = ?
            WHERE tag_id = ?
        ');
        $stm->execute([$name, $category, $tag_id]);

        temp('info', 'Record updated');
        redirect('/page/tag_crud.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Admin | Tag Update';
include '../_head.php';

?>

<form method="post" class="form">
    <label for="tag_id">Id</label>
    <b><?= $tag_id ?></b>
    <br>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="50"') ?>
    <?= err('name') ?>

    <label for="category">Category</label>
    <?= html_select('category', array("Temperature"=>"Temperature", "Base"=>"Base", "Flavour"=>"Flavour")) ?>
    <?= err('category') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<p>
    <button data-get="/page/tag_crud.php">Back</button>
</p>

<?php
include '../_foot.php';
