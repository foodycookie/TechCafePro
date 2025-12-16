<?php
require '../_base.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Only customer/member can access
auth('customer', 'member');

$cart = get_cart();

/* ===============================
   HANDLE BULK ACTIONS
   =============================== */

// Update quantities (bulk)
if (is_post() && ($_POST['action'] ?? '') === 'update' && isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $unit) {
        $id   = (int)$id;
        $unit = (int)$unit;

        if ($unit > 0) {
            update_cart($id, $unit);
        } else {
            cart_remove($id);
        }
    }
    redirect('/page/cart.php');
}

// Remove selected items
if (is_post() && ($_POST['action'] ?? '') === 'remove' && !empty($_POST['selected'])) {
    foreach ($_POST['selected'] as $id) {
        cart_remove((int)$id);
    }
    redirect('/page/cart.php');
}

// Checkout selected items only
if (is_post() && ($_POST['action'] ?? '') === 'checkout' && !empty($_POST['selected'])) {
    $_SESSION['checkout_selected'] = array_map('intval', $_POST['selected']);
    redirect('/page/checkout.php');
}

/* ===============================
   FETCH PRODUCT DETAILS
   =============================== */

$ids = array_keys($cart);
$items = [];

if ($ids) {
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stm = $_db->prepare("
        SELECT p.*, c.category_name
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id IN ($placeholders)
        ORDER BY p.product_name
    ");
    $stm->execute($ids);
    $items = $stm->fetchAll();
}

$total = 0;
$_title = 'Shopping Cart';
include '../_head.php';
?>

<h2>Your Cart (<?= count($cart) ?> item<?= count($cart) > 1 ? 's' : '' ?>)</h2>

<?php if (!$items): ?>
    <p>Your cart is empty. <a href="/page/menu.php">Go shopping</a></p>
<?php else: ?>

<form method="post">

<table class="table">
    <thead>
        <tr>
            <th><input type="checkbox" onclick="toggleAll(this)"></th>
            <th>Photo</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($items as $p): 
            $qty = $cart[$p->product_id];
            $sub = $p->price * $qty;
            $total += $sub;
        ?>
        <tr>
            <td>
                <input type="checkbox" name="selected[]" value="<?= $p->product_id ?>">
            </td>

            <td>
                <img src="/images/placeholder/<?= $p->photo ?>" width="80">
            </td>

            <td><?= htmlspecialchars($p->product_name) ?></td>

            <td>RM <?= number_format($p->price, 2) ?></td>

            <td>
                <input
                    type="number"
                    name="qty[<?= $p->product_id ?>]"
                    value="<?= $qty ?>"
                    min="1"
                    style="width:70px"
                >
            </td>

            <td>RM <?= number_format($sub, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="5" style="text-align:right">Total</th>
            <th>RM <?= number_format($total, 2) ?></th>
        </tr>
    </tfoot>
</table>

<div style="margin:20px 0; text-align:right">
    <button type="submit" name="action" value="update">
        Update Quantity
    </button>

    <button type="submit" name="action" value="remove"
        onclick="return confirm('Remove selected items?')">
        Remove Selected
    </button>

    <button type="submit" name="action" value="checkout">
        Checkout Selected
    </button>

    <button type="button" onclick="location.href='/page/menu.php'">
        Continue Shopping
    </button>
</div>

</form>

<?php endif; ?>

<script>
function toggleAll(source) {
    document.querySelectorAll("input[name='selected[]']")
        .forEach(cb => cb.checked = source.checked);
}
</script>

<?php include '../_foot.php'; ?>
