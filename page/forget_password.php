<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_exists($email, 'users', 'email')) {
        $_err['email'] = 'Not exists';
    }

    // Send reset token (if valid)
    if (!$_err) {
        // (1) Select user
        $stm = $_db->prepare('SELECT * FROM users WHERE email = ?');
        $stm->execute([$email]);
        $u = $stm->fetch();
        
        // (2) Generate token id
        $id = sha1(uniqid() . rand());  

        // (3) Delete old and insert new token
        $stm = $_db->prepare('
            DELETE FROM tokens WHERE user_id = ?;

            INSERT INTO tokens (token_id, expire, user_id)
            VALUES (?, ADDTIME(NOW(), "00:05"), ?);
        ');
        $stm->execute([$u->user_id, $id, $u->user_id]);

        // (4) Generate token url
        $url = base("page/token.php?id=$id");

        // (5) Send email
        $m = get_mail();
        $m->addAddress($u->email, $u->name);
        $m->addEmbeddedImage("../images/user_photos/$u->profile_image_path", 'photo');
        $m->isHTML(true);
        $m->Subject = 'Reset Password';
        $m->Body = "
            <img src='cid:photo'
                 style='width: 200px; height: 200px;
                        border: 1px solid #333'>
            <p>Dear $u->name,<p>
            <h1 style='color: red'>Reset Password</h1>
            <p>
                Please click <a href='$url'>here</a>
                to reset your password.
            </p>
            <p>From, 🍵 TechCafePro Admin</p>
        ";
        $m->send();

        temp('info', 'Email sent');
        redirect('./home.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Forgot Password';
include '../_head.php';
?>

<form method="post" class="form">
    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';