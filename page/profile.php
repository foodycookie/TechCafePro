<?php
include '../_base.php';

auth('customer', 'member');

// ----------------------------------------------------------------------------

$user_id = $_SESSION['user']->user_id;

// Fetch user
if (is_get()) {
    $stm = $_db->prepare("
        SELECT 
            u.user_id,
            u.name,
            u.email,
            u.profile_image_path AS photo
        FROM users u
        WHERE u.user_id = ?
    ");
    $stm->execute([$user_id]);
    $u = $stm->fetch();

    if (!$u) {
        redirect('/page/login.php');
    }

    extract((array)$u);

    // Keep photo for POST
    $_SESSION['photo'] = $photo;
}

// Deactivate account
if (isset($_POST['deactivate_account'])) {
    $_db->prepare("
        UPDATE users
        SET status = 0
        WHERE user_id = ?
    ")->execute([$user_id]);

    session_unset();
    temp('info', 'Account deactivated');
    redirect('/page/home.php');
}

if (is_post()) {
    $email  = req('email');
    $name   = req('name');
    $photo  = $_SESSION['photo'];
    $f      = get_file('photo');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: photo (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    if (!$_err) {
        // (1) Save new photo
        if ($f) {
            if ($photo) {
                @unlink("../images/user_photos/$photo");
            }
            $photo = save_photo($f, '../images/user_photos');
        }

        // (2) Update user
        $_db->prepare("
            UPDATE users
            SET email = ?, name = ?, profile_image_path = ?
            WHERE user_id = ?
        ")->execute([$email, $name, $photo, $user_id]);

        temp('info', 'Profile updated');
        redirect('/page/profile.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Profile';
include '../_head.php';
?>

<form method="post" class="form" enctype="multipart/form-data">
    <label>Email</label>
    <?= html_text('email', 'maxlength="100"', $email ?? '') ?>
    <?= err('email') ?>

    <label>Name</label>
    <?= html_text('name', 'maxlength="100"', $name ?? '') ?>
    <?= err('name') ?>

    <label>Photo</label>
    <label class="upload">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="../images/user_photos/<?= $photo ?: '../images/user_photos/placeholder.png' ?>" width="120">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Save</button>
        <button type="reset">Reset</button>
    </section>
</form>

<hr>

<p>
    <button data-get="/page/order_history.php">
        📦 Order History
    </button>
    <button data-get="/page/change_password.php">
        🫰 Change Password
    </button>
</p>

<button onclick="location.href='/page/logout.php'">Logout</button>

<form method="post" onsubmit="return confirm('Deactivate your account permanently?')">
    <button name="deactivate_account" class="danger">
        Deactivate Account
    </button>
</form>

<hr>

<section style="margin-top:30px; padding:15px; border:1px solid #ddd;">
    <h3>🎉 Membership</h3>

    <?php if ($_SESSION['user']->role === 'customer'): ?>
        <p>Status: <b>Customer</b></p>
        <p>Upgrade to member and earn reward points.</p>

        <button onclick="location.href='/page/membership.php'">
            Join Membership
        </button>

    <?php else: ?>
        <p><b>Status:</b> Member</p>

        <p>
            <b>Reward Points:</b>
            <?= (int)($_SESSION['user']->reward_points ?? 0) ?> pts
        </p>

        <p>
            <b>Member Since:</b>
            <?= date('d-m-Y', strtotime($_SESSION['user']->member_since)) ?>
        </p>

        <p style="font-size:13px; color:#666;">
            Earn 1 point for every RM1 spent.
        </p>

    <?php if ($_SESSION['user']->role === 'member'): ?>
    <section style="margin-top:20px">
        <button onclick="location.href='/page/reward_redeem.php'">
            🎁 Redeem Rewards
        </button>
    </section>
    <?php endif; ?>

    <?php endif; ?>
</section>

<?php
include '../_foot.php';