<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Load categories
$cats = $_db->query("SELECT * FROM categories ORDER BY cat_name")->fetchAll();

// Search keyword
$search = req('name', '');

// Query products (sorted by category, then sold DESC, then name ASC)
$stm = $_db->prepare('SELECT p.*, c.cat_name
                      FROM products p 
                      JOIN categories c ON p.category_id = c.category_id
                      WHERE p.pro_name LIKE ?
                      ORDER BY c.cat_name, p.sold DESC, p.pro_name ASC');

$stm->execute(["%$search%"]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------
// GROUP PRODUCTS PROPERLY
// ----------------------------------------------------------------------------

$grouped = [];

foreach ($arr as $m) {

    // Ensure array exists
    if (!isset($grouped[$m->category_id])) {
        $grouped[$m->category_id] = [
            'cat_name' => $m->cat_name,
            'items'    => []
        ];
    }

    // Add product into array
    $grouped[$m->category_id]['items'][] = $m;
}

// ----------------------------------------------------------------------------

$_title = 'Menu | Home';
include '../_head.php';
?>

<div class="search">
    <div class="menu-search" style="height: 30px; float:left;">
        <form method="get">
            <?= html_search('name', 'placeholder="Search menu..."') ?>
            <button>Search</button>
        </form>
    </div>

    <div class="category-bar" style="float: right;">
        <?php foreach ($cats as $c): ?>
            <a href="#cat-<?= $c->category_id ?>" class="cat-link">
                <?= encode($c->cat_name) ?>
            </a>
        <?php endforeach ?>
    </div>
</div>

<div class="layout">
    <main class="menu-grid">

        <?php foreach ($grouped as $cat_id => $data): ?>
            <?php $cat_name = $data['cat_name']; ?>
            <?php $items    = $data['items']; ?>

            <h2 id="cat-<?= $cat_id ?>" style="font-size: 35px; clear:both;">
                <?= encode($cat_name) ?>
            </h2>

            <div class="category-section">

                <?php 
                    // Get top 5 products from this category
                    $top5 = array_slice($items, 0, 5);
                ?>

                <?php foreach ($top5 as $p): ?>
                    <div class="menu-card <?= $p->available ? '' : 'unavailable' ?>">
                        
                        <?php if (!$p->is_available): ?>
                            <div class="badge-unavailable">Unavailable</div>
                        <?php endif; ?>

                        <img src="/photo/<?= $p->photo ?>" 
                             alt="<?= encode($p->pro_name) ?>" width="180">

                        <h3><?= encode($p->pro_name) ?></h3>
                        <p class="price">RM <?= number_format($p->price, 2) ?></p>

                        <?php if ($p->is_available): ?>
                            <?php if (auth()): ?>
                                <button data-post="/page/cart.php?id=<?= $p->product_id ?>">
                                    Add to Cart
                                </button>
                            <?php else: ?>
                                <button onclick="location.href='/page/login.php'">
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
                <a href="/page/category.php?cat_id=<?= $cat_id ?>" 
                   style="font-weight:bold; font-size:18px;">
                   More &raquo;
                </a>
            </div>

        <?php endforeach; ?>
        
    </main>
</div>

<?php
include '../_foot.php';
