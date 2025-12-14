<?php
include '../_base.php';

if (is_post()) {

    // ==============================
    // DELETE CATEGORY (with products)
    // ==============================
    if ($cat_id = req('category_id')) {

        // 1️⃣ Get all product photos under this category
        $stm = $_db->prepare("
            SELECT photo FROM products WHERE category_id = ?
        ");
        $stm->execute([$cat_id]);

        foreach ($stm->fetchAll(PDO::FETCH_COLUMN) as $photo) {
            if ($photo && file_exists("../images/menu_photos/$photo")) {
                unlink("../images/menu_photos/$photo");
            }
        }

        // 2️⃣ Delete products under category
        $_db->prepare("DELETE FROM products WHERE category_id = ?")
            ->execute([$cat_id]);

        // 3️⃣ Delete category
        $_db->prepare("DELETE FROM categories WHERE category_id = ?")
            ->execute([$cat_id]);

        temp('info', 'Category and its products deleted!');
    }

    // ==============================
    // DELETE PRODUCT(S) ONLY
    // ==============================
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

        $del = $_db->prepare('DELETE FROM products WHERE product_id = ?');
        $count = 0;

        foreach ($product_id as $id) {
            $count += $del->execute([$id]);
        }

        temp('info', "$count product(s) deleted!");
    }

    redirect('/page/product_crud.php');
}
