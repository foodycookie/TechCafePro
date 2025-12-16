<?php
include '../_base.php';

/*
if (is_post()) {
    // ==============================
    // DELETE CATEGORY (with products)
    // ==============================
    if ($cat_id = req('category_id', [])) {
        if (!is_array($cat_id)) {
            $cat_id = [$cat_id];
        }

        // 1️⃣ Get all product photos under this category
        $stm = $_db->prepare("
            SELECT photo FROM products WHERE category_id = ?
        ");

        foreach ($cat_id as $id) {
            $stm->execute([$id]);

            foreach ($stm->fetchAll(PDO::FETCH_COLUMN) as $photo) {
                if ($photo && file_exists("../images/menu_photos/$photo")) {
                    unlink("../images/menu_photos/$photo");
                }
            }
        }

        // 2️⃣ Delete products under category
        $_stm = $_db->prepare("DELETE FROM products WHERE category_id = ?");

        foreach ($cat_id as $id) {
            $_stm->execute([$id]);
        }

        // 2️⃣ Delete products under category
        $_stm = $_db->prepare("DELETE FROM categories WHERE category_id = ?");
        $count = 0;

        foreach ($cat_id as $id) {
            $count +=  $_stm->execute([$id]);
        }

        temp('info', "$count category(s) and its product(s) deleted!");
        redirect('/page/category_crud.php');
    }
}
*/

?>