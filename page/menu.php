<?php
include '../_base.php';

if (isset($_POST['submit'])) {
    $product_id = req('selected', []);
    $quantity = req('quantity', []);
    $count = 0;

    if (!is_array($product_id)) {
        $product_id = [$product_id];
    }

    if (!is_array($quantity)) {
        $quantity = [$quantity];
    }

    foreach ($product_id as $pid) {
        if ($quantity[$pid] > 0) {
            add_cart($pid, $quantity[$pid]);
            $count++;
        }
    }

    if ($count > 0) {
        temp('info', "$count item(s) added to cart!");
    }

    redirect();
}

// ----------------------------------------------------------------------------

// Load categories
$cats = $_db->query("SELECT *
                     FROM categories
                     WHERE EXISTS (
                        SELECT product_id 
                        FROM products 
                        WHERE products.category_id = categories.category_id)
                     AND categories.status = 1
                     ORDER BY category_name")
                     ->fetchAll();

// Search keyword
$product_name = req('product_name', '');

// Query products (sorted by category, then sold DESC, then name ASC)
$stm = $_db->prepare('SELECT p.*, c.category_name
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.category_id
                      WHERE p.product_name LIKE ?
                      AND p.status = 1
                      AND c.status = 1
                      ORDER BY c.category_name, p.sold DESC, p.product_name ASC');

$stm->execute(["%$product_name%"]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------
// GROUP PRODUCTS PROPERLY
// ----------------------------------------------------------------------------

$grouped = [];

foreach ($cats as $c) {
    $grouped[$c->category_id] = [
        'category_name' => $c->category_name,
        'items' => []
    ];
}

// Fill items
foreach ($arr as $m) {
    $grouped[$m->category_id]['items'][] = $m;
}


// ----------------------------------------------------------------------------

$_title = 'Menu | Favourite';
include '../_head.php';
?>

<div class="search">
    <div class="category-bar" style="float: right;">
        <?php foreach ($cats as $c): ?>
            <a href="#cat-<?= $c->category_id ?>" class="cat-link">
                <?= encode($c->category_name) ?>
            </a>
        <?php endforeach ?>
        <button type="submit" name="submit" form="add_to_cart" class="cat-link" style="background-color: gold;">Add Selected Item(s) To Cart</button>
    </div>
</div>

<form method="post" id="add_to_cart">
    <div class="layout">
        <main class="menu-grid">
            <?php foreach ($grouped as $category_id => $data): ?>
                <?php $category_name = $data['category_name']; ?>
                <?php $items    = $data['items']; ?>
                <h2 id="cat-<?= $category_id ?>" style="font-size: 35px; clear:both;">
                    <?= encode($category_name) ?>
                </h2>
                <div class="category-section">
                    <?php 
                        // Get top 5 products from this category
                        $top5 = array_slice($items, 0, 5);
                    ?>
                    <?php foreach ($top5 as $p): ?>  <!--top 5-->
                        <div class="menu-card <?= $p->is_available ? '' : 'unavailable' ?>">
                            <img src="../images/menu_photos/<?= $p->photo ?>" 
                                alt="<?= encode($p->product_name) ?>" width="180">
                            <h3><?= encode($p->product_name) ?></h3>
                            <p class="price">RM <?= number_format($p->price, 2) ?></p>
                            <?php if (!$p->is_available): ?>
                                <div class="badge-unavailable">Unavailable</div>
                            <?php else: ?>
                                <?php if (in_array($role,['member','customer'])): ?> <!--must change follow teammate-->
                                    <input type="hidden" name="selected[]" value="<?= $p->product_id ?>">
                                    <input type='number' name='quantity[<?= $p->product_id ?>]' value='0'
                                        min='0' max='99' step='1'>
                                <?php else: ?>
                                    <button type="button" onclick="location.href='login.php'">
                                        Login to Order
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="clear:both;"></div>
                <!-- More button -->
                <div style="text-align:right; margin-bottom:40px;">
                    <button type="button" onclick="location.href='/page/category.php?category_id=<?= $category_id ?>'">
                        More &raquo;
                    </button>
                </div>
            <?php endforeach; ?>
        </main>
    </div>
</form>

<?php
include '../_foot.php';
