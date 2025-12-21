<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (isset($_POST['add_to_cart'])) {
    $product_id = req('product_id');
    $quantity = req('quantity');

    if ($quantity > 0) {
        add_cart($product_id, $quantity);
        temp('info', "Item added to cart!");
    }
    
    redirect();
}

if (is_get()) {
    $product_id = req('product_id');

    $stm = $_db->prepare('SELECT products.*, categories.category_name
                          FROM products
                          LEFT JOIN categories on categories.category_id = products.category_id
                          WHERE products.product_id = ?');
    $stm->execute([$product_id]);
    $p = $stm->fetch();

    if (!$p) {
        temp('info', 'No Record found!');
        redirect('/page/menu.php');
    }

    extract((array)$p);

    $_SESSION['photo'] = $p->photo;
}

// ----------------------------------------------------------------------------

$_title = 'Menu | Detail';
include '../_head.php';

?>

<div class="form">
    <label for="product_name">Name</label>
    <b><?= $product_name ?></b>
    <br>

    <label for="price">Price</label>
    <b>RM <?= number_format($price, 2) ?></b>
    <br>

    <label for="description">Description</label>
    <b><?= $description ?></b>
    <br>

    <label for="category_name">Category</label>
    <b><?= $category_name ?></b>
    <br>

    <label for="is_available">Available?</label>
    <b><?= (int)$is_available === 1 ? 'Yes' : 'No' ?></b>
    <br>

    <label for="photo">Photo</label>
    <img src="../images/menu_photos/<?= $photo ?>">
</div>

<form method="post">
    <div class="form">
        <?php if (auth2('member', 'customer')): ?>
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <input type='number' name='quantity' value='0' min='0' max='99' step='1'>
            <button type="submit" name="add_to_cart" >Add To Cart</button>
        <?php else: ?>
            <button type="button" onclick="location.href='/page/login.php'">
                Login to Order
        <?php endif ?>
        <button data-get="/page/menu.php">Back</button>
    </div>
</form>

<?php
include '../_foot.php';