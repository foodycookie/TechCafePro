<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Load categories
$cats = $_db->query("SELECT *
                     FROM categories
                     WHERE EXISTS (
                        SELECT product_id 
                        FROM products 
                        WHERE products.category_id = categories.category_id)
                     AND categories.is_active = 1
                     ORDER BY category_name")
                     ->fetchAll();

// Search keyword
$product_name = req('product_name', '');

// Query products (sorted by category, then sold DESC, then name ASC)
$stm = $_db->prepare('SELECT p.*, c.category_name
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.category_id
                      WHERE p.product_name LIKE ?
                      AND p.is_active = 1
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
    </div>
</div>

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
                                <button data-post="/page/cart.php?id=<?= $p->product_id ?>">
                                    Add to Cart
                                </button>
                            <?php else: ?>
                                <button onclick="location.href='login.php'">
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
                <button onclick="location.href='/page/category.php?category_id=<?= $category_id ?>'">
                    More &raquo;
                </button>
            </div>
        <?php endforeach; ?>
    </main>
</div>

<?php
include '../_foot.php';
