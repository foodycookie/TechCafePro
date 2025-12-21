<?php
require '../_base.php';

auth('member');

// ----------------------------------------------------------------------------

$user = $_SESSION['user'];

// Redeem rule: 1 product = price × 10 points
function required_points($price) {
    if ((int)($price * 10) == 0) {
        return 1;
    }

    return (int)($price * 10);
}

// POST: Redeem product
if (is_post()) {

    $product_id = req('product_id');

    // Get product
    $stm = $_db->prepare("
        SELECT product_id, product_name, price
        FROM products
        WHERE product_id = ?
    ");
    $stm->execute([$product_id]);
    $product = $stm->fetch();

    if (!$product) {
        temp('info', 'Invalid product');
        redirect('/page/reward_redeem.php');
    }

    $need = required_points($product->price);

    if ($user->reward_points < $need) {
        temp('info', 'Not enough reward points');
        redirect('/page/reward_redeem.php');
    }

    // DB Transaction
    $_db->beginTransaction();

    try {

        // (1) Deduct reward points
        $_db->prepare("
            UPDATE users
            SET reward_points = reward_points - ?
            WHERE user_id = ?
        ")->execute([$need, $user->user_id]);

        // (2) Create FREE order (RM0)
        $_db->prepare("
            INSERT INTO orders (user_id, count, total_amount, created_at)
            VALUES (?, 1, 0, NOW())
        ")->execute([$user->user_id]);

        $order_id = $_db->lastInsertId();

        // (3) Insert order item (free)
        $_db->prepare("
            INSERT INTO order_items (order_id, product_id, unit, price, subtotal)
            VALUES (?, ?, 1, 0, 0)
        ")->execute([$order_id, $product->product_id]);

        $_db->prepare("
            INSERT INTO payments (amount, method, status, paid_at, order_id)
            VALUES (0, ?, ?, NOW(), ?)
        ")->execute(["Redeemed Reward", "Completed", $order_id]);

        $_db->commit();

        // Sync session
        $user->reward_points -= $need;
        $_SESSION['user'] = $user;

        temp('info', 'Product redeemed successfully 🎉');
        redirect('/page/order_detail.php?order_id=' . $order_id);

    } catch (Exception $e) {
        $_db->rollBack();
        temp('info', 'Redeem failed');
        redirect('/page/reward_redeem.php');
    }
}

// (4) Load redeemable products
$products = $_db->query("
    SELECT product_id, product_name, price
    FROM products
    ORDER BY product_name
")->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Member | Reward Redeem';
include '../_head.php';
?>

<h1>🎁 Redeem Reward Points</h1>

<p>
    <b>Your Reward Points:</b>
    <?= (int)$user->reward_points ?> pts
</p>

<p style="font-size:14px;color:#666">
    Redeem rule: <b>RM1 = 10 points</b>
</p>

<hr>

<table class="table">
    <tr>
        <th>Product</th>
        <th>Price (RM)</th>
        <th>Required Points</th>
        <th></th>
    </tr>

    <?php foreach ($products as $p): ?>
    <?php $need = required_points($p->price); ?>
    <tr>
        <td><?= encode($p->product_name) ?></td>
        <td><?= number_format($p->price, 2) ?></td>
        <td><?= $need ?> pts</td>
        <td>
            <?php if ($user->reward_points >= $need): ?>
                <form method="post" style="display:inline">
                    <input type="hidden" name="product_id" value="<?= $p->product_id ?>">
                    <button>Redeem</button>
                </form>
            <?php else: ?>
                <button disabled>Not enough points</button>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<p>
    <button onclick="location.href='/page/profile.php'">
        &laquo; Back to Profile
    </button>
</p>

<?php
include '../_foot.php';