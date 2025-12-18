<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (isset($_POST['clear_button'])) {
    set_cart();
    temp('info', "Cart cleared!");
    redirect();
}

if (isset($_POST['delete_button'])) {
    $product_id = req('delete_button');

    update_cart($product_id, 0);

    temp('info', "Item deleted from cart!");
    redirect();
}

if (isset($_POST['delete_multiple_button'])) {
    $count = 0;

    $selected = req('selected', []);
    if (!is_array($selected)) {
        $selected = [$selected];
    }

    foreach ($selected as $product_id => $v) {
        update_cart($product_id, 0);
        $count++;
    }

    temp('info', "$count item(s) deleted from cart!");
    redirect();
}

if (isset($_POST['update_multiple_button'])) {
    $selected = req('selected', []);
    if (!is_array($selected)) {
        $selected = [$selected];
    }

    $quantity = req('quantity', []);
    if (!is_array($quantity)) {
        $quantity = [$quantity];
    }

    $count = 0;

    foreach ($selected as $product_id => $v) {
        update_cart($product_id, $quantity[$product_id]);
        $count++;
    }

    temp('info', "$count item(s) quantity updated!");
    redirect();
}

if (isset($_POST['checkout_button'])) {
    $selected = req('selected', []);
    if (!is_array($selected)) {
        $selected = [$selected];
    }

    $quantity = req('quantity', []);
    if (!is_array($quantity)) {
        $quantity = [$quantity];
    }

    set_chosen_cart_item_for_order();

    foreach ($selected as $product_id => $v) {
        if ($quantity[$product_id] > 0) {
            update_cart($product_id, $quantity[$product_id]);
            update_chosen_cart_item_for_order($product_id, $quantity[$product_id]);
        }
    }

    redirect("/page/checkout.php");
}

// ----------------------------------------------------------------------------

$_title = 'Order | Shopping Cart';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 100px;
    }
</style>

<h2>Your Cart (<?= count($cart) ?> item<?= count($cart) > 1 ? 's' : '' ?>)</h2>

<?php if (empty($cart)): ?>
    <p>Your cart is empty. <a href="/page/menu.php">Go shopping</a></p>
<?php else: ?>

<form method="post">
    <table class="table">
        <tr>
            <th><input type="checkbox" onclick="toggleAllForNameStartedWith(this, 'selected')"></th>
            <th>Name</th>
            <th>Price (RM)</th>
            <th>Quantity</th>
            <th>Subtotal (RM)</th>
        </tr>
        <?php
            $count = 0;
            $total = 0; 
            
            $stm = $_db->prepare('SELECT * FROM products WHERE product_id = ?');
            $cart = get_cart();
            
            foreach ($cart as $product_id => $quantity):
                $stm->execute([$product_id]);
                $p = $stm->fetch();
                
                $subtotal = $p->price * $quantity;
                $count += $quantity;
                $total += $subtotal; 
        ?>
            <tr>
                <td>
                    <input type="checkbox" name="selected[<?= $p->product_id ?>]">
                </td>
                <td><?= $p->product_name ?></td>
                <td class="right"><?= $p->price ?></td>
                <td>
                    <!-- <input type='hidden' name='product_id_for_update_multiple[]' value="<?= $p->product_id ?>"> -->
                    <input type='number' name='quantity[<?= $p->product_id ?>]' value="<?= $quantity ?>"
                           min='0' max='99' step='1'>
                </td>
                <td class="right">
                    <?= sprintf('%.2f', $subtotal) ?>
                </td>
                <td>
                    <button type="submit" name="delete_button" value="<?= $p->product_id ?>" data-confirm>Delete</button>
                    <img src="../images/menu_photos/<?= $p->photo ?>" class="popup">
                </td>
            </tr>
        <?php endforeach ?>
            <tr>
                <th colspan="3"></th>
                <th class="right"><?= $count ?></th>
                <th class="right"><?= sprintf('%.2f', $total) ?></th>
            </tr>
    </table>

    <p>
        <?php if ($cart): ?>
            <button type="submit" name="update_multiple_button">Update Selected Item(s) Quantity</button>
            <br><br>
            <button type="submit" name="clear_button" data-confirm>Clear</button>
            <button type="submit" name="delete_multiple_button" data-confirm>Delete Selected Item(s)</button>
            <br><br>
            <?php if (in_array($role,['member','customer'])): ?>
                <button type="submit" name="checkout_button" style="background-color: goldenrod;">Checkout Selected Item(s) with Current Quantity</button>
            <?php else: ?>
                Please <a href="/login.php">login</a> as member to checkout
            <?php endif ?>
        <?php endif ?>
    </p>
</form>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<?php endif; ?>

<?php
include '../_foot.php';