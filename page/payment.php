<?php
require '../_base.php';
require_once '../vendor/autoload.php';  // Stripe library

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// ----------------------------------------------------------------------------
// Authorization: only members can pay
auth2('Member');

// Get order_id (pass via URL)
$order_id = get('order_id');
if (!$order_id || !is_numeric($order_id)) {
    redirect('cart.php');
}

// Fetch order info
$stm = $_db->prepare('
    SELECT o.*, oi.product_id, oi.unit, oi.price, oi.subtotal, p.product_name
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE o.order_id = ? AND o.user_id = ?
');
$stm->execute([$order_id, $_user->user_id]);
$order = $stm->fetch();
if (!$order) {
    redirect('cart.php');
}

// Fetch all items for Stripe line_items
$stm = $_db->prepare('
    SELECT oi.*, p.product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
');
$stm->execute([$order_id]);
$items = $stm->fetchAll();

$total_in_cents = (int)round($order->total_amount * 100);  // Stripe uses cents

// ----------------------------------------------------------------------------
// Handle Cash on Delivery
if (is_post() && post('method') === 'cod') {
    try {
        $_db->beginTransaction();

        $stm = $_db->prepare('
            INSERT INTO payments (order_id, method, status, amount, paid_at) 
            VALUES (?, "Cash on Delivery", "Pending", ?, NOW())
        ');
        $stm->execute([$order_id, $order->total_amount]);

        $stm = $_db->prepare('UPDATE products SET sold = sold + ? WHERE product_id = ?');
        foreach ($items as $item) {
            $stm->execute([$item->unit, $item->product_id]);
        }

        $_db->commit();

        redirect("after_payment.php?order_id=$order_id");
    } catch (Exception $e) {
        $_db->rollBack();
        die("Payment error: " . $e->getMessage());
    }
}

// ----------------------------------------------------------------------------
// Handle Stripe Payment (create session)
if (is_post() && post('method') === 'stripe') {
    $line_items = [];
    foreach ($items as $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'myr',
                'unit_amount' => (int)round($item->price * 100),  // price per unit in cents
                'product_data' => [
                    'name' => $item->product_name,
                ],
            ],
            'quantity' => $item->unit,
        ];
    }

    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],  // Add 'fpx' for Malaysian bank if enabled in dashboard
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => base("page/after_payment.php?order_id=$order_id&session_id={CHECKOUT_SESSION_ID}"),
            'cancel_url' => base("page/payment.php?order_id=$order_id"),
            'metadata' => ['order_id' => $order_id],  // For webhook use
            'client_reference_id' => $order_id,     // Alternative identifier
        ]);

        // Redirect to Stripe Checkout
        header('Location: ' . $session->url);
        exit();
    } catch (Exception $e) {
        die("Stripe error: " . $e->getMessage());
    }
}

// ----------------------------------------------------------------------------
// Display page
$_title = 'Payment';
include '../_head.php';
?>

<div style="margin: 50px auto; max-width: 600px; text-align:center;">
    <h2>Payment Options</h2>
    <p><strong>Order ID:</strong> <?= $order_id ?></p>
    <p><strong>Total Amount:</strong> RM <?= number_format($order->total_amount, 2) ?></p>

    <div style="margin: 30px 0;">
        <form method="post" style="display:inline-block; margin:0 20px;">
            <input type="hidden" name="method" value="cod">
            <button type="submit" style="padding:15px 30px; font-size:18px; background:#333; color:#fff; border:none;">
                Cash on Delivery
            </button>
        </form>

        <form method="post" style="display:inline-block; margin:0 20px;">
            <input type="hidden" name="method" value="stripe">
            <button type="submit" style="padding:15px 30px; font-size:18px; background:#635bff; color:#fff; border:none;">
                Pay with Card (Stripe)
            </button>
        </form>
    </div>

    <p><button onclick="window.location.href='/page/cart.php'">Back to Cart</button></p>
</div>

<?php include '../_foot.php'; ?>