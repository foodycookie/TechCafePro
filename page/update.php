<?php
require '../_base.php';
// ----------------------------------------------------------------------------

// if (is_get()) {
//     $id = req('id');

//     // TODO
//     $stm = $_db->prepare('SELECT * FROM student WHERE id = ?');
//     $stm->execute([$id]);
//     $s = $stm->fetch();

//     if (!$s) {
//         redirect('/');
//     }

//     $name       = $s->name;
//     $gender     = $s->gender;
//     $program_id = $s->program_id;
// }

// if (is_post()) {
//     // Input
//     $id         = req('id'); // <-- From URL
//     $name       = req('name');
//     $gender     = req('gender');
//     $program_id = req('program_id');

//     // Validate id <-- NO NEED
    
//     // Validate name
//     if ($name == '') {
//         $_err['name'] = 'Required';
//     }
//     else if (strlen($name) > 100) {
//         $_err['name'] = 'Maximum length 100';
//     }

//     // Validate gender
//     if ($gender == '') {
//         $_err['gender'] = 'Required';
//     }
//     else if (!array_key_exists($gender, $_genders)) {
//         $_err['name'] = 'Invalid value';
//     }

//     // Validate program_id
//     if ($program_id == '') {
//         $_err['program_id'] = 'Required';
//     }
//     else if (!array_key_exists($program_id, $_programs)) {
//         $_err['program_id'] = 'Invalid value';
//     }

//     // Output
//     if (!$_err) {
//         // TODO
//         $stm = $_db->prepare('UPDATE student
//                               SET name = ?');
//         $stm->execute([$name, $gender, $program_id, $id]);

//         temp('info', 'Record updated');
//         redirect('/');
//     }
// }

// ----------------------------------------------------------------------------
$_title = 'Update';
include '../_head.php';
?>

<form method="post" class="form">
    <!-- <label for="id">Id</label>
    <b><?= $id ?></b>
    <?= err('id') ?> -->

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