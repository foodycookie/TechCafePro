<?php
include '../_base.php';

auth('customer', 'member'); // only logged-in users

// ----------------------------------------------------------------------------
// Get logged-in user email (from session or DB)
$user_id = $_SESSION['user']->user_id ?? 0;

$stmt = $_db->prepare("SELECT email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$logged_in_email = $stmt->fetchColumn() ?: 'unknown@user.com';

// Fixed admin email
$admin_email = 'techcafepro@gmail.com';

// ----------------------------------------------------------------------------
if (is_post()) {

    $subject = req('subject');
    $body    = req('body');

    // Validation
    if ($subject == '') {
        $_err['subject'] = 'Required';
    } elseif (strlen($subject) > 100) {
        $_err['subject'] = 'Maximum 100 characters';
    }

    if ($body == '') {
        $_err['body'] = 'Required';
    } elseif (strlen($body) > 500) {
        $_err['body'] = 'Maximum 500 characters';
    }

    // Send email
    if (!$_err) {
        $m = get_mail();

        $m->addAddress($admin_email);          // admin receives
        $m->addReplyTo($logged_in_email);      // reply goes to customer
        $m->Subject = '[Customer Message] ' . $subject;

        $m->Body =
            "Customer Email: $logged_in_email\n\n" .
            "Message:\n" .
            "--------------------\n" .
            $body;

        $m->send();

        temp('info', 'Message sent successfully!');
        redirect();
    }
}

// ----------------------------------------------------------------------------
$_title = 'Contact Tech Cafe';
include '../_head.php';
?>

<!-- EMBEDDED STYLE (does NOT touch group CSS) -->
<style>
    .form input[type=email] {
        background: #eee;
        border: 1px solid #aaa;
    }
    #body {
        width: 500px;
        height: 200px;
        resize: none;
    }
</style>

<form class="form" method="post">

    <label>To</label>
    <input type="email" value="<?= $admin_email ?>" readonly>
    <span></span>

    <label>Subject</label>
    <?= html_text('subject', 'maxlength="100"') ?>
    <?= err('subject') ?>

    <label>Message</label>
    <?= html_textarea('body', 'maxlength="500"') ?>
    <?= err('body') ?>

    <section>
        <button>Send</button>
        <button type="reset">Reset</button>
        <button data-get="/page/home.php">Back</button> 
    </section>

</form>

<?php
include '../_foot.php';
?>
