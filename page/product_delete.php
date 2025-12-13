<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $product_id = req('product_id', []);
    if (!is_array($product_id)) $product_id = [$product_id];

    $stm = $_db->prepare('SELECT photo FROM products WHERE product_id = ?');

    // Delete photo
    foreach ($product_id as $v) {
        $stm->execute([$v]);
        $photo = $stm->fetchColumn();
        unlink("../images/menu_photos/$photo");  // remove from directory
    }

    $stm = $_db->prepare('DELETE FROM products WHERE product_id = ?');
    $count = 0;

    foreach ($product_id as $v) {
        $count += $stm->execute([$v]);
    }

    temp('info', "$count record(s) deleted!");

redirect('/page/product_crud.php');
}

// ----------------------------------------------------------------------------