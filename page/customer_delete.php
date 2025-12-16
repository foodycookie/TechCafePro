<?php
include '../_base.php';

if (is_post()) {


    // ==============================
    // DELETE PRODUCT(S) ONLY
    // ==============================
    if ($user_id = req('user_id', [])) {

        if (!is_array($user_id)) {
            $user_id = [$user_id];
        }

        $stm = $_db->prepare('SELECT profile_image_path FROM users WHERE user_id = ?');

        foreach ($user_id as $id) {

            if ($id == 1) continue;
            
            $stm->execute([$id]);
            $photo = $stm->fetchColumn();

            if ($photo && file_exists("../../images/user_photos/$photo")) {
                unlink("../../images/user_photos/$photo");
            }
        }

        $del = $_db->prepare('DELETE FROM users WHERE user_id = ?');
        $count = 0;

        foreach ($user_id as $id) {
            $count += $del->execute([$id]);
        }

        temp('info', "$count user(s) deleted!");
    }

    redirect('/page/customer_crud.php');
}
