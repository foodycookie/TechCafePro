<?php
include '../_base.php';

// --- Button Logic Handlers ---

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
    if (!is_array($selected)) $selected = [$selected];

    foreach ($selected as $product_id => $v) {
        update_cart($product_id, 0);
        $count++;
    }
    temp('info', "$count item(s) deleted from cart!");
    redirect();
}

if (isset($_POST['update_multiple_button'])) {
    $selected = req('selected', []);
    if (!is_array($selected)) $selected = [$selected];

    $quantity = req('quantity', []);
    if (!is_array($quantity)) $quantity = [$quantity];

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
    if (!is_array($selected)) $selected = [$selected];

    $quantity = req('quantity', []);
    if (!is_array($quantity)) $quantity = [$quantity];

    set_chosen_cart_item_for_order();

    foreach ($selected as $product_id => $v) {
        if ($quantity[$product_id] > 0) {
            update_cart($product_id, $quantity[$product_id]);
            update_chosen_cart_item_for_order($product_id, $quantity[$product_id]);
        }
    }
    redirect("/page/checkout.php");
}

// --- Page Display ---

$_title = 'Shopping Cart'; 
include '../_head.php';

$cart = get_cart();
?>

<?php if (empty($cart)): ?>
    <div class="cart-container">
        <p>Your cart is empty. <a href="/page/menu.php">Go shopping</a></p>
    </div>
<?php else: ?>

<div class="cart-wrapper-container">
    <form method="post" id="cart-form">
        <div class="cart-container">
            <div class="cart-items card-rounded">
                <h1>Your Cart (<?= count($cart) ?>)</h1>
                
                <div class="cart-header">
                    <span class="col-check"><input type="checkbox" onclick="toggleAllForNameStartedWith(this, 'selected')"></span>
                    <span class="col-product">PRODUCT</span>
                    <span class="col-qty">QUANTITY</span>
                    <span class="col-total">SUBTOTAL</span>
                </div>

                <?php
                    $display_total_units = 0;
                    $display_subtotal = 0; 
                    $stm = $_db->prepare('SELECT * FROM products WHERE product_id = ?');
                    
                    foreach ($cart as $product_id => $qty):
                        $stm->execute([$product_id]);
                        $p = $stm->fetch();
                        if (!$p) continue;

                        $item_subtotal = $p->price * $qty;
                        $display_total_units += $qty;
                        $display_subtotal += $item_subtotal; 
                ?>
                    <div class="product-row">
                        <div class="col-check">
                            <input type="checkbox" name="selected[<?= $p->product_id ?>]">
                        </div>
                        
                        <div class="col-product">
                            <img src="../images/menu_photos/<?= $p->photo ?>" class="product-img">
                            <div class="product-info">
                                <small>PRODUCT ID: <?= $p->product_id ?></small>
                                <h4><?= $p->product_name ?></h4>
                                <div class="product-meta">Unit Price: RM <?= sprintf('%.2f', $p->price) ?></div>
                            </div>
                        </div>
                        
                        <div class="col-qty">
                            <div class="qty-control">
                                <input type='number' name='quantity[<?= $p->product_id ?>]' value="<?= $qty ?>" min='0' max='99'>
                            </div>
                        </div>
                        
                        <div class="col-total">
                            RM <?= sprintf('%.2f', $item_subtotal) ?>
                        </div>
                    </div>
                <?php endforeach ?>
                
                <div style="margin-top: 20px;">
                    <a href="/page/menu.php" class="continue-link">← Continue shopping</a>
                </div>
            </div>

            <div class="order-summary card-rounded">
                <h2>Order Summary</h2>
                
                <div class="summary-row">
                    <span>Total Units</span>
                    <span><?= $display_total_units ?></span>
                </div>
                
                <div class="summary-row subtotal">
                    <span>Subtotal</span>
                    <strong>RM <?= number_format($display_subtotal, 2) ?></strong>
                </div>
                
                <?php if (isset($role) && in_array($role, ['member', 'customer'])): ?>
                    <button type="submit" name="checkout_button" class="checkout-btn">CHECKOUT SELECTED</button>
                <?php else: ?>
                    <p class="login-msg">Please <a href="/login.php">login</a> to checkout</p>
                <?php endif ?>

                <div class="summary-actions">
                    <button type="submit" name="update_multiple_button" class="action-btn update">UPDATE QUANTITIES</button>
                    <div class="action-group">
                        <button type="submit" name="delete_multiple_button" class="action-btn delete" data-confirm>DELETE SELECTED</button>
                        <button type="submit" name="clear_button" class="action-btn clear" data-confirm>CLEAR CART</button>
                    </div>
                </div>
                
                <p class="tax-info">Tax included. Shipping calculated at checkout.</p>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>

<script>
    // Handles any select dropdown changes if they exist
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';
?>