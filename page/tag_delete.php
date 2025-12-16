<?php
include '../_base.php';

if (is_post()) {
    if ($tag_id = req('tag_id', [])) {

        if (!is_array($tag_id)) {
            $tag_id = [$tag_id];
        }

        $stm = $_db->prepare('DELETE FROM product_tags WHERE tag_id = ?');

        foreach ($tag_id as $id) {
            $stm->execute([$id]);
        }

        $stm = $_db->prepare('DELETE FROM tags WHERE tag_id = ?');
        $count = 0;

        foreach ($tag_id as $id) {
            $count += $stm->execute([$id]);
        }

        temp('info', "$count tag(s) deleted!");
    }

    redirect('/page/tag_crud.php');
}