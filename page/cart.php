<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (isset($_POST['clear_button'])) {
    set_cart();
    redirect();
}

if (isset($_POST['update_button'])) {
    $product_id = req('product_id_to_be_updated', []);
    $quantity = req('quantity_to_be_updated', []);

    if (!is_array($product_id)) {
        $product_id = [$product_id];
    }

    if (!is_array($quantity)) {
        $quantity = [$quantity];
    }

    for ($i=0; $i < count($product_id); $i++) { 
        update_cart($product_id[$i], $quantity[$i]);
    }
        
    redirect();
}

if (isset($_POST['delete_button'])) {
    $product_id = req('delete_button');

    update_cart($product_id, 0);

    redirect();
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

<form method="post">
    <table class="table">
        <tr>
            <th>Id</th>
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
                <td><?= $p->product_id ?></td>
                <td><?= $p->product_name ?></td>
                <td class="right"><?= $p->price ?></td>
                <td>
                    <input type="hidden"name="product_id_to_be_updated[]" value="<?= $p->product_id ?>">
                    <input type='number' name='quantity_to_be_updated[]' value="<?= $quantity ?>"
                           min='0' max='99' step='1'>
                </td>
                <td class="right">
                    <?= sprintf('%.2f', $subtotal) ?>
                </td>
                <td>
                    <button type="submit" name="delete_button" value="<?= $p->product_id ?>">Delete</button>
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
            <button type="submit" name="clear_button">Clear</button>
            <button type="submit" name="update_button">Update Amount</button>
            <?php if (in_array($role,['member','customer'])): ?>
                <button data-post="checkout.php">Checkout</button>
            <?php else: ?>
                Please <a href="/login.php">login</a> as member to checkout
            <?php endif ?>
        <?php endif ?>
    </p>
</form>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';