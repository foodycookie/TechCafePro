<?php
include '../../_base.php';

auth('admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    $category_name = req('category_name');
    $status = req('status');

    if ($category_name == '') {
        $_err['category_name'] = 'Required';
    }
    else if (strlen($category_name) > 50) {
        $_err['category_name'] = 'Maximum 50 characters';
    }
    else if (!is_unique($category_name, 'categories', 'category_name')) {
        $_err['category_name'] = 'Duplicated';
    }

    if ($status == '') {
        $_err['status'] = 'Required';
    }

    if (!$_err) {
        $stm = $_db->prepare('
            INSERT INTO categories (category_name, status)
            VALUES (?, ?)
        ');
        $stm->execute([$category_name]);

        temp('info', 'Record inserted');
        redirect('/page/admin6699/category_crud.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Admin| Category Insert';
include '../../_head.php';
?>

<form method="post" class="form">
    <label for="category_name">Name</label>
    <?= html_text('category_name', 'maxlength="50"') ?>
    <?= err('category_name') ?>

    <label for="status">Status</label>
    <?= html_radios('status', array("1"=>"Active", "0"=>"Inactive"), false) ?>
    <?= err('status') ?>
    
    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<p>
    <button data-get="/page/admin6699/category_crud.php">Back</button>
</p>

<?php
include '../../_foot.php';