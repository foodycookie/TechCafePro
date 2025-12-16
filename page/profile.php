<?php
include '../_base.php';

$user_id = $_SESSION['user']->user_id;

if (!$user_id) {
    redirect('login.php');
}

// ----------------------------------------------------------------------------
// GET: Fetch user + shipping address
// ----------------------------------------------------------------------------

if (is_get()) {

    $stm = $_db->prepare("
        SELECT 
            u.user_id,
            u.name,
            u.email,
            u.profile_image_path AS photo,
            sa.address,
            sa.city,
            sa.postal_code,
            sa.state,
            sa.country
        FROM users u
        LEFT JOIN shipping_addresses sa ON u.user_id = sa.user_id
        WHERE u.user_id = ?
    ");
    $stm->execute([$user_id]);
    $u = $stm->fetch();

    if (!$u) {
        redirect('login.php');
    }

    extract((array)$u);

    // Keep photo for POST
    $_SESSION['photo'] = $photo;
}

// ----------------------------------------------------------------------------
// POST: Update profile / Deactivate
// ----------------------------------------------------------------------------

if (is_post()) {

    /* =====================
       Deactivate account
    ===================== */
    if (req('deactivate_account')) {

        $_db->prepare("
            UPDATE users
            SET is_active = 0
            WHERE user_id = ?
        ")->execute([$user_id]);

        logout();
        redirect('login.php?deactivated=1');
    }

    /* =====================
       Get inputs
    ===================== */
    $email  = req('email');
    $name   = req('name');
    $photo  = $_SESSION['photo'];
    $f      = get_file('photo');

    $address     = req('address');
    $city        = req('city');
    $postal_code = req('postal_code');
    $state       = req('state');
    $country     = req('country');

    /* =====================
       Validate email
    ===================== */
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    /* =====================
       Validate name
    ===================== */
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    /* =====================
       Validate photo (optional)
    ===================== */
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    /* =====================
       DB operations
    ===================== */
    if (!$_err) {

        // (1) Save new photo
        if ($f) {
            if ($photo) {
                @unlink("../uploads/profile_pics/$photo");
            }
            $photo = save_photo($f, '../uploads/profile_pics');
        }

        // (2) Update user
        $_db->prepare("
            UPDATE users
            SET email = ?, name = ?, profile_image_path = ?
            WHERE user_id = ?
        ")->execute([$email, $name, $photo, $user_id]);

        // (3) Save shipping address (permanent)
        $shipping_id = 'SA' . substr($user_id, -5);

        $_db->prepare("
            REPLACE INTO shipping_addresses
            (shipping_address_id, address, city, postal_code, state, country, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ")->execute([
            $shipping_id,
            $address,
            $city,
            $postal_code,
            $state,
            $country,
            $user_id
        ]);

        temp('info', 'Profile updated');
        redirect('profile.php');
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
        <img src="../uploads/profile_pics/<?= $photo ?: 'default.png' ?>" width="120">
    </label>
    <?= err('photo') ?>

    <label>Address</label>
    <?= html_text('address', '', $address ?? '') ?>

    <?= html_text('city', 'placeholder="City"', $city ?? '') ?>
    <?= html_text('postal_code', 'placeholder="Postal Code"', $postal_code ?? '') ?>
    <?= html_text('state', 'placeholder="State"', $state ?? '') ?>
    <?= html_text('country', 'placeholder="Country"', $country ?? '') ?>

    <section>
        <button>Save</button>
        <button type="reset">Reset</button>
    </section>
</form>

<hr>

<form method="post"
      onsubmit="return confirm('Deactivate your account permanently?')">

    <button name="deactivate_account" class="danger">
        Deactivate Account
    </button>
</form>

<?php
include '../_foot.php';
