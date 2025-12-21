<?php
include '../_base.php';

auth('customer', 'member');

// ----------------------------------------------------------------------------

// Get shopping cart from session
$cart = get_chosen_cart_item_for_order();
if (!$cart) {
    temp('info', "Your cart is empty. Please add items to cart before checkout.");
    redirect('/page/cart.php');
}

// Skip invalid entries at the start if any
$cart = array_filter($cart, fn($unit) => $unit > 0); // remove empty or 0 units

try {
    $_db->beginTransaction();

    // Insert new order
    $stm = $_db->prepare('INSERT INTO orders (user_id, created_at) VALUES (?, NOW())');
    $stm->execute([$_user->user_id]);
    $order_id = $_db->lastInsertId(); // new order ID

    // Insert each cart item into order_items
    $stm = $_db->prepare('INSERT INTO order_items (order_id, product_id, price, unit, subtotal) VALUES (?, ?, ?, ?, ?)');
    foreach ($cart as $product_id => $unit) {
        // Get product info
        $p_stm = $_db->prepare('SELECT price FROM products WHERE product_id = ?');
        $p_stm->execute([$product_id]);
        $product = $p_stm->fetch();
        if (!$product) {
            // Skip invalid product IDs instead of throwing
            continue;
        }

        $price = $product->price;
        $subtotal = $price * $unit;

        $stm->execute([$order_id, $product_id, $price, $unit, $subtotal]); // Insert order item
    }

    // Update order totals
    $stm = $_db->prepare('
        UPDATE orders
        SET count = (SELECT SUM(unit) FROM order_items WHERE order_id = ?),
            total_amount = (SELECT SUM(subtotal) FROM order_items WHERE order_id = ?)
        WHERE order_id = ?
    ');
    $stm->execute([$order_id, $order_id, $order_id]);

    // Commit transaction
    $_db->commit();

    redirect("/page/payment.php?order_id=$order_id");

} catch (Exception $e) {
    $_db->rollBack();
    echo "<p>Error processing order: " . $e->getMessage() . "</p>";
}

// ----------------------------------------------------------------------------

$_title = 'Order | Checkout';
include '../_head.php';
?>

<h1>Checkout Page</h1>

<?php
include '../_foot.php';
?>
