<?php
require '../_base.php';

if (is_post()) {
    $_err = [];

    // Input
    $name       = req('name');
    $password   = req('password');

    // Validate name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum length 100';
    }

    // Validate password
    if ($password == '') {
        $_err['password'] = 'Required';
    } else if (strlen($password) > 100) {
        $_err['password'] = 'Maximum length 100';
    }

    // Output
    // if (!$_err) {
    //     // TODO
    //     $stm = $_db->prepare('INSERT INTO student
    //                             (id, name, gender, program_id)
    //                             VALUES(?, ?, ?, ?)');
    //     $stm->execute([$id, $name, $gender, $program_id]);
                                
    //     temp('info', 'Record inserted');
    //     redirect('/');
    // }

    if (!$_err) {
        temp('info', 'Register successful (no data will be saved lol');
        redirect('/page/login.php');
        exit;

        $_err['general'] = temp('info', 'Invalid name or password');
    }
}

$_title = 'Register';
include '../_head.php';

?>
   <form method="post" class="form">
    <?php if (!empty($_err['general'])): ?>
        <p style="color:red;"><?= $_err['general'] ?></p>
    <?php endif; ?>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="password">Password</label>
    <?= html_password('password', 'maxlength="100" autocomplete="off"') ?>
    <?= err('password') ?>

        <section>
            <button type="submit">Submit</button>
            <button type="button" onclick="window.location.href='/page/home.php'">Cancel</button>
        </section>
</form>

<?php
include '../_foot.php';