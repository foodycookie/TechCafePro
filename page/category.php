<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Get category ID from URL
$category_id = req('category_id');
$product_name = req('product_name');


if (!$category_id) {
    redirect('menu.php');
}

// Get category info
$stm = $_db->prepare("SELECT * FROM categories WHERE category_id = ?");
$stm->execute([$category_id]);
$cat = $stm->fetch();

if (!$cat) {
    redirect('menu.php');
}

// Get ALL products under this category
$stm = $_db->prepare('
    SELECT p.*, c.category_name
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    WHERE p.category_id = ?
      AND p.product_name LIKE ?
    ORDER BY p.sold DESC, p.product_name ASC
');
$stm->execute([
    $category_id,
    "%$product_name%"
]);
$products = $stm->fetchAll();
$no_result = $product_name && empty($products);

// ----------------------------------------------------------------------------

$_title = 'Menu | ' . $cat->category_name;
include '../_head.php';
?>

<div class="search">
    <div class="menu-search" style="height: 30px; padding-bottom: 25px;">
        <form method="get">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">
            <?= html_search('product_name', 'placeholder="Search product..."') ?>
            <button>Search</button>
        </form>
    </div>
</div>

<?php if ($no_result): ?>
    <div class="alert alert-warning">
        <h2>Product not found, please search again.</h2>
    </div>
    <button onclick="location.href='category.php?category_id=<?= $category_id ?>'" style="margin-bottom: 40px;">
        Back
    </button>

<?php else: ?>
    <div class="menu-grid">
        <div class="category-section">
            <?php foreach ($products as $p): ?>
                <div class="menu-card <?= $p->is_available ? '' : 'unavailable' ?>">

                    <img src="../images/menu_photos/<?= $p->photo ?>"
                        alt="<?= encode($p->product_name) ?>" width="180">

                    <h3><?= encode($p->product_name) ?></h3>
                    <p class="price">RM <?= number_format($p->price, 2) ?></p>

                    <?php if (!$p->is_available): ?>
                        <div class="badge-unavailable">Unavailable</div>
                    <?php endif; ?>

                    <?php if ($p->is_available): ?>
                        <?php if (in_array($role,['member','customer'])): ?>
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
        <button onclick="location.href='/page/menu.php'" style="margin-bottom: 40px;">
            &laquo; Back to Menu
        </button>
    </div>
<?php endif; ?>



<?php
include '../_foot.php';
