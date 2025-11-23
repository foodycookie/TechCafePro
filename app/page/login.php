<?php
require '../_base.php';

$_title = 'Login';
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