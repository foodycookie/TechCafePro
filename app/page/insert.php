<?php
require '../_base.php';
// ----------------------------------------------------------------------------

if (is_post()) {
    // Input
    $id         = req('id');
    $name       = req('name');
    $gender     = req('gender');
    $program_id = req('program_id');
   
    // Validate id
    if ($id == '') {
        $_err['id'] = 'Required';
    }
    else if (!preg_match('/^\d{2}[A-Z]{3}\d{5}$/', $id)) {
        $_err['id'] = 'Invalid format';
    }
    // else {
    //     // TODO
    //     $stm = $_db->prepare('SELCT COUNT(*) FROM student WHERE id = ?');
    //     $stm->execute($id);

    //     if ($stm->fetchColumn() > 0) {
    //         $_err['id'] = 'Duplicated';
    //     }
    // }

    else if (!is_unique($id, 'student', 'id')) {
        $_err['id'] = 'Duplicated';
    }
    
    // Validate name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum length 100';
    }

    // Validate gender
    if ($gender == '') {
        $_err['gender'] = 'Required';
    }
    else if (!array_key_exists($gender, $_genders)) {
        $_err['name'] = 'Invalid value';
    }

    // Validate program_id
    if ($program_id == '') {
        $_err['program_id'] = 'Required';
    }
    else if (!array_key_exists($program_id, $_programs)) {
        $_err['program_id'] = 'Invalid value';
    }

    // Output
    if (!$_err) {
        // TODO
        $stm = $_db->prepare('INSERT INTO student
                                (id, name, gender, program_id)
                                VALUES(?, ?, ?, ?)');
        $stm->execute([$id, $name, $gender, $program_id]);
                                
        temp('info', 'Record inserted');
        redirect('/');
    }
}

// ----------------------------------------------------------------------------
$_title = 'Insert';
include '../_head.php';
?>

<form method="post" class="form">
    <label for="id">Id</label>
    <?= html_text('id', 'maxlength="10" data-upper') ?>
    <?= err('id') ?>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label>Gender</label>
    <?= html_radios('gender', $_genders) ?>
    <?= err('gender') ?>

    <label for="program_id">Program</label>
    <?= html_select('program_id', $_programs) ?>
    <?= err('program_id') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';