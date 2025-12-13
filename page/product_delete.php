<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $product_id = req('product_id'); 

    // Delete photo
    $stm = $_db->prepare('SELECT photo FROM products WHERE product_id = ?');
    $stm->execute([$product_id]);
    $photo = $stm->fetchColumn();
    unlink("../images/menu_photos/$photo");  // remove from directory
    //redirect('index.php'); 

    $stm = $_db->prepare('DELETE FROM products WHERE product_id = ?');
    $stm->execute([$product_id]);
    temp('info', 'Record deleted');
}

redirect('/page/product_crud.php');

// ----------------------------------------------------------------------------
