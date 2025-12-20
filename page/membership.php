<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// (1) Authorization: only customer can register membership
// ----------------------------------------------------------------------------

auth('customer');

$user = $_SESSION['user'];

// If already a member, no need to join again
if ($user->role === 'member') {
    redirect('profile.php');
}

// ----------------------------------------------------------------------------
// (2) POST: Register membership
// ----------------------------------------------------------------------------

if (is_post()) {

    // Validate: agreement checkbox
    if (!req('agree')) {
        $_err['agree'] = 'You must agree to the membership terms';
    }

    // DB operation
    if (!$_err) {

    // (1) Update user role + init reward points
     $_db->prepare("
        UPDATE users
        SET 
            role = 'member',
            is_member = 1,
            reward_points = 0,
            member_since = NOW()
        WHERE user_id = ?
    ")->execute([$user->user_id]);


        // (2) Update session user object
        $user->role = 'member';
        $user->reward_points = 0;
        $user->member_since = date('Y-m-d H:i:s');
        $_SESSION['user'] = $user;

        temp('info', 'Membership activated successfully 🎉');
        redirect('profile.php');
    }
}


$_title = 'Membership Registration';
include '../_head.php';
?>

<p>
    You are about to upgrade your account to a <b>Member</b>.
</p>

<ul>
    <li>Earn reward points for every purchase</li>
    <li>Redeem points for free products</li>
</ul>

<form method="post" class="form">

    <label>
        <input type="checkbox" name="agree" value="1">
        I agree to the membership terms and conditions
    </label>
    <?= err('agree') ?>

    <section>
        <button>Join Membership</button>
        <button type="button" onclick="location.href='profile.php'">
            Cancel
        </button>
    </section>
</form>

<?php
include '../_foot.php';
