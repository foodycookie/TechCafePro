<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) { // handle update cart
    $id = req('id'); // get product id from request
    $unit = req('unit'); // get unit from request
    update_cart($id, $unit); // update cart in session
    redirect(); // redirect to avoid resubmission
} // is saved in session which is in google server so unless user close browser or clear cookies, cart will persist

$arr = $_db->query('SELECT * FROM products'); // pagination

// ----------------------------------------------------------------------------

$_title = 'Product | List';
include '../_head.php';
?>

<style>
    #products {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product {
        border: 1px solid #333;
        width: 200px;
        height: 200px;
        position: relative;
    }

    .product img {
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .product form,
    .product div {
        position: absolute;
        background: #0009;
        color: #fff;
        padding: 5px;
        text-align: center;
    }

    .product form {
        inset: 0 0 auto auto;
    }

    .product div {
        inset: auto 0 0 0;
    }
</style>

<div id="products">
    <?php foreach ($arr as $p): ?>
        <?php
            $cart = get_cart(); // retreive cart from session
            $id = $p->id; // product id
            $unit = $cart[$p->id] ?? 0; // get unit from cart, default to 0
        ?>
        <div class="product">
            <form method="post">
                <?= $unit ? '✅' : '' ?>
                <?= html_hidden('id', $p->product_id) ?> <!-- hidden product id -->
                <?= html_select('unit', $_units, '') ?>  <!-- select unit 1-10, list box -->
            </form>
                
            <img src="/products/<?= $p->photo ?>"
                 data-get="/product/detail.php?id=<?= $p->id ?>">

            <div><?= $p->name ?> | RM <?= $p->price ?></div>
        </div>
    <?php endforeach ?>
</div>

<script>
    $('select[name="unit"]').on('change', function() {
        this.form.submit(); // submit the form when unit is changed
    });
    // document.querySelectorAll('.product img').forEach(img => {
    //     img.onclick = () => {
    //         const url = img.getAttribute('data-get');
    //         window.location.href = url;
</script>

<?php
include '../_foot.php';