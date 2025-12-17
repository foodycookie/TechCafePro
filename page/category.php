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

// Get category ID from URL
$category_id = req('category_id');
$product_name = req('product_name');
$tag_id = req('tag_id', []);
$min_price = req('min_price');
$max_price = req('max_price');

if (!is_array($tag_id)) {
    $tag_id = [$tag_id];
}

if (!$category_id) {
    redirect('menu.php');
}

// Get category info
$stm = $_db->prepare("SELECT * FROM categories WHERE category_id = ? AND status = 1");
$stm->execute([$category_id]);
$cat = $stm->fetch();

if (!$cat) {
    redirect('menu.php');
}

$sql_query = "";
$tag_join_query = "";
$tag_group_by_query = "";
$tag_order_by_query = "";
$price_query = "";

if (count($tag_id) > 0) {
    // Prepare placeholders for each tag ID
    $placeholders = implode(',', array_fill(0, count($tag_id), '?'));

    // If there is tag(s), join product_tags with the tag(s) selected, other tags that are not selected are ignored
    $tag_join_query = "INNER JOIN product_tags 
                       ON product_tags.product_id = products.product_id
                       AND product_tags.tag_id IN ($placeholders)";

    // Combine multiple rows with the same product_id into one row
    $tag_group_by_query = "GROUP BY products.product_id";

    // Products with more matching tags appear higher
    $tag_order_by_query = "ORDER BY
                           COUNT(DISTINCT product_tags.tag_id) DESC,
                           products.sold DESC,
                           products.product_name ASC";

    $query_parameters = array_merge($tag_id, [$category_id, "%$product_name%"]);
}

else {
    // If no tag, just sort by sold and product_name
    $tag_order_by_query = "ORDER BY products.sold DESC,
                           products.product_name ASC";
    
    $query_parameters = [$category_id, "%$product_name%"];
}

if ($min_price === '' xor $max_price === '') {
    $_err['max_price'] = 'Please fill in both min and max price!';
} 

elseif ($min_price > $max_price) {
    $_err['max_price'] = 'Min price cannot be larger than max price!';
} 

elseif ($min_price !== '' && $max_price !== '') {
    $price_query = "AND products.price >= ? 
                    AND products.price <= ?";

    $query_parameters[] = $min_price;
    $query_parameters[] = $max_price;
}

$sql_query = "SELECT products.*, categories.category_name
              FROM products
              JOIN categories 
              ON products.category_id = categories.category_id
              $tag_join_query
              WHERE products.category_id = ?
              AND products.product_name LIKE ?
              AND products.status = 1
              $price_query
              $tag_group_by_query
              $tag_order_by_query";

$stm = $_db->prepare($sql_query);
$stm->execute($query_parameters);

$products = $stm->fetchAll();

$no_result = empty($products);

// ----------------------------------------------------------------------------

$temperature_tags = $_db->query("SELECT * FROM tags WHERE category = 'Temperature'")->fetchAll();
$base_tags        = $_db->query("SELECT * FROM tags WHERE category = 'Base'")       ->fetchAll();
$flavour_tags     = $_db->query("SELECT * FROM tags WHERE category = 'Flavour'")    ->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Menu | ' . $cat->category_name;
include '../_head.php';
?>

<div class="search">
    <div class="menu-search" style="height: 30px; padding-bottom: 25px;">
        <form method="get">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">
            <?= html_search('product_name', 'placeholder="Search product..."') ?>

            <label for="min_price">Min Price</label>
            <?= html_number('min_price', 0.01, 99.99, 0.01) ?>
            <?= err('min_price') ?>

            <label for="max_price">Max Price</label>
            <?= html_number('max_price', 0.01, 99.99, 0.01) ?>
            <?= err('max_price') ?>
            
            <button type="button" id="filter_popup_filter_button" name="filter_popup_filter_button" onclick="toggle_visibility('filter_popup')">Filter Menu</button>

            <button style="background-color: aqua;">Search and Filter Product</button>

            <button type="submit" name="submit" form="add_to_cart" style="background-color: gold; float: right">Add Selected Item(s) To Cart</button>

            <div class="filter_popup" id="filter_popup" name="filter_popup">
                <input type="hidden" name="category_id" value="<?= $category_id ?>">
                <h2>Filter Menu</h2>

                <?php if (count($temperature_tags) > 0): ?>
                    <h3>Temperature</h3>
                        <?php foreach ($temperature_tags as $temperature_tag): ?>
                            <?php $temperature_tag_array["$temperature_tag->tag_id"] = "$temperature_tag->name"; ?>
                        <?php endforeach; ?>
                        <?= html_checkboxes('tag_id', $temperature_tag_array, false); ?>
                <?php endif; ?>

                <?php if (count($base_tags) > 0): ?>
                    <h3>Base</h3>
                        <?php foreach ($base_tags as $base_tag): ?>
                            <?php $base_tag_array["$base_tag->tag_id"] = "$base_tag->name"; ?>
                        <?php endforeach; ?>
                        <?= html_checkboxes('tag_id', $base_tag_array, false) ?>
                <?php endif; ?>

                <?php if (count($flavour_tags) > 0): ?>
                    <h3>Flavour</h3>
                        <?php foreach ($flavour_tags as $flavour_tag): ?>
                            <?php $flavour_tag_array["$flavour_tag->tag_id"] = "$flavour_tag->name"; ?>
                        <?php endforeach; ?>
                        <?= html_checkboxes('tag_id', $flavour_tag_array, false) ?>
                <?php endif; ?>

                <?php if ((count($temperature_tags) == 0) && (count($base_tags) == 0) && (count($flavour_tags) == 0)): ?>
                    <h3>No Tag Available!</h3>
                <?php endif; ?>

                <br>
                <button type="button" onclick="toggle_visibility('filter_popup')">Close</button>
            </div>

        </form>
    </div>
</div>

<form method="post" id="add_to_cart">
    <?php if ($no_result): ?>
        <div class="alert alert-warning">
            <h2>Product not found, please search again.</h2>
        </div>
        <button type="button" onclick="location.href='category.php?category_id=<?= $category_id ?>'" style="margin-bottom: 40px;">
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
            <button type="button" onclick="location.href='/page/menu.php'" style="margin-bottom: 40px;">
                &laquo; Back to Menu
            </button>
        </div>
    <?php endif; ?>
</form>

<?php
include '../_foot.php';