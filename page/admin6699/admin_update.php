<?php
include '../../_base.php';

// ----------------------------------------------------------------------------

if (is_get()) {  // step1 SQL select
    $user_id = req('user_id'); //P009

    $stm = $_db->prepare('SELECT * FROM users WHERE user_id = ?');
    $stm->execute([$user_id]);
    $u = $stm->fetch();

    if (!$u) {   // if no record found, go /page/user_crud.php
        temp('info', 'No Record');
        redirect('/page/admin6699/admin_crud.php');
    }

    $name   = $u->name;
    $email  = $u->email;
    $status = $u->status;
    $role   = $u->role;
    $photo  = $u->profile_image_path ?: 'placeholder.jpg';

    $_SESSION['photo'] = $photo;
}
  
var_dump($name);
    echo 'hah';
if (is_post()) {   // step3 SQL update
    $user_id = req('user_id');
    $name   = req('name');  
    $email  = req('email'); 
    $status = req('status');
    $f      = get_file('photo');
    $photo  = $_SESSION['photo'] ?? 'placeholder.jpg';

    // Validate 
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 50) {
        $_err['name'] = 'Maximum 50 characters';
    }
    else if (!is_unique($name, 'users', 'name')) {
        $_err['name'] = 'Duplicated';
    }

    if (!$email) {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_unique($email, 'users', 'email')) {
        $_err['email'] = 'Duplicated';
    }

    if ($status == '') {
        $_err['status'] = 'Required';
    }

    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > 2 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 2MB';
        }
    }

    // DB operation
    if (!$_err) {
        if ($f){
            unlink("../../images/user_photos/$photo");
            $photo = save_photo($f, '../../images/user_photos');
        }
        $stm = $_db->prepare('
            UPDATE users
            SET name = ?, email = ?, status = ?, profile_image_path = ?
            WHERE user_id = ?
        ');
        $stm->execute([$name, $email, $status, $photo, $user_id]);

        temp('info', 'Record updated');
        redirect('/page/admin6699/admin_crud.php');
    }
    $_SESSION['photo'] = $photo;
}

// ----------------------------------------------------------------------------

$_title = 'Admin | user Update';
include '../../_head.php';

?>
<form method="post" class="form" enctype="multipart/form-data" novalidate> 
    <label for="user_id">Id</label> 
    <b><?= $user_id ?></b> 
    <br> 

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="50"') ?>
    <?= err('name') ?>

    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="role">Role</label>
    <b><?= htmlspecialchars($role) ?></b>
    <?= html_hidden('role', $role) ?>
    <?= err('role') ?>

    <label for="status">Status</label>
    <?= html_radios('status', ["1" => "Available", "0" => "Unavailable"], $status) ?>
    <?= err('status') ?>

    <label for="photo">Photo</label> 
    <label class="upload" tabindex="0"> 
        <?= html_file('photo', 'image/*', 'hidden') ?> 
        <img src="../images/user_photos/<?= htmlspecialchars($photo) ?>" alt="Photo">
    </label> 
    <?= err('photo') ?> 

    <section> 
        <button>Submit</button> 
        <button type="reset">Reset</button> 
    </section> 
</form>

<p>
    <button data-get="/page/admin6699/admin_crud.php">Back</button>
</p>
<?php
include '../../_foot.php';
