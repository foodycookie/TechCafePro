<?php
require '../_base.php';

print_r($_SESSION);
if (is_post()) {

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

    if (!$_err) {

        $stm = $_db->prepare('
            SELECT * FROM users
            WHERE name = ? AND role != "admin"
        ');
        $stm->execute([$name]);
        $uname = $stm->fetch();

        if (!$uname) {
            temp('info', 'Not Found Users!');
            redirect();
        }

        // checking the failed attempt
        $block_time = 1; // minutes
        if ($uname->failed_attempts >= 3 && $uname->last_failed_login) {
            $last = new DateTime($uname->last_failed_login);
            $now  = new DateTime();
            $diff = $now->getTimestamp() - $last->getTimestamp();

            if ($diff < $block_time * 60) {
                temp('info', 'Account temporarily blocked. Try again after 1 minute.');
                redirect();
            }
        }

        $stm = $_db -> prepare ('
            SELECT * FROM users
            WHERE name = ? AND password = SHA(?) AND role != "admin"
        ');
        $stm -> execute([$name, $password]);    
        $u = $stm -> fetch();

        if ($u) {
            if ($u->status == 0) {
                temp('info', 'Account Freeze');
            } else {
                $stm = $_db->prepare('
                    UPDATE users 
                    SET failed_attempts = 0, last_failed_login = NULL 
                    WHERE user_id = ?
                '); 
                $stm->execute([$u->user_id]);   

                temp('info', 'Login Successfully');
                login($u);
            }
        } 
        else {
             $stm = $_db->prepare('
                UPDATE users 
                SET failed_attempts = failed_attempts + 1, last_failed_login = NOW()
                WHERE user_id = ?
            ');
            $stm->execute([$uname->user_id]);

            temp('info', 'Login Failed! Please try again');
            redirect();
            }
    }
}

$_title = 'Login';
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
        <button type="button" onclick="window.location.href='/page/forget_password.php'">forget password</button>
    </section>
</form>

<?php
include '../_foot.php';