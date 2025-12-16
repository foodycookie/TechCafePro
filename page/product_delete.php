<?php
include '../_base.php';

/*
if (is_post()) {
    if ($product_id = req('product_id', [])) {

        if (!is_array($product_id)) {
            $product_id = [$product_id];
        }

        $stm = $_db->prepare('SELECT photo FROM products WHERE product_id = ?');

        foreach ($product_id as $id) {
            $stm->execute([$id]);
            $photo = $stm->fetchColumn();

            if ($photo && file_exists("../images/menu_photos/$photo")) {
                unlink("../images/menu_photos/$photo");
            }
        }

        $stm = $_db->prepare('DELETE FROM product_tags WHERE product_id = ?');

        foreach ($product_id as $id) {
            $stm->execute([$id]);
        }

        $stm = $_db->prepare('DELETE FROM products WHERE product_id = ?');
        $count = 0;

        foreach ($product_id as $id) {
            $count += $stm->execute([$id]);
        }

        temp('info', "$count product(s) deleted!");
    }

    redirect('/page/product_crud.php');
}
*/

?>