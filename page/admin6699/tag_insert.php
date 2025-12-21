<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    $name = req('name');
    $category = req('category');

    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 50) {
        $_err['name'] = 'Maximum 50 characters';
    }
    else if (!is_unique($name, 'tags', 'name')) {
        $_err['name'] = 'Duplicated';
    }

    if ($category == '') {
        $_err['category'] = 'Required';
    }

    if (!$_err) {
        $stm = $_db->prepare('
            INSERT INTO tags (name, category)
            VALUES (?, ?)
        ');
        $stm->execute([$name, $category]);

        temp('info', 'Record inserted');
        redirect('/page/admin6699/tag_crud.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Admin| Tag Insert';
include '../../_head.php';
?>

<form method="post" class="form">
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
    <button data-get="/page/admin6699/tag_crud.php">Back</button>
</p>

<?php
include '../../_foot.php';