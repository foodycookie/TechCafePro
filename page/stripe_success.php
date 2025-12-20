<?php
require '../_base.php';

// 1. AUTHENTICATION: Ensure only logged-in members access this
auth2('Member');

$order_id = get('order_id');

if ($order_id) {
    try {
        $_db->beginTransaction();

        // 2. UPDATE PAYMENT STATUS: Change from Pending to Completed
        $stm = $_db->prepare('
            UPDATE payments 
            SET status = "Completed", paid_at = NOW() 
            WHERE order_id = ? AND method = "Stripe"
        ');
        $stm->execute([$order_id]);

        // 3. UPDATE INVENTORY: Increment the "sold" count for products in this order
        // This is important to keep your sales data accurate
        $stm = $_db->prepare('SELECT product_id, unit FROM order_items WHERE order_id = ?');
        $stm->execute([$order_id]);
        $items = $stm->fetchAll();

        foreach ($items as $item) {
            $upd = $_db->prepare('UPDATE products SET sold = sold + ? WHERE product_id = ?');
            $upd->execute([$item->unit, $item->product_id]);
        }

        $_db->commit();
        
        // 4. CLEANUP: Clear the temporary cart session now that payment is confirmed
        // (Assuming get_chosen_cart_item_for_order is used to track the pending checkout)
        temp('info', 'Stripe Payment Successful!');
        redirect("after_payment.php?order_id=$order_id");

    } catch (Exception $e) {
        $_db->rollBack();
        die("Database error during status update: " . $e->getMessage());
    }
} else {
    redirect('cart.php');
}