<?php
require '../_base.php';

$_title = 'Category';
include '../_head.php';
?>

<style>
    .button-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
    }

    .button-container button {
        width: 600px;
        height: 400px;
        font-size: 1.5rem;
        cursor: pointer;
    }

    @media (min-width: 1220px) { 
        .button-container button {
            flex: 0 0 calc(50% - 10px);
        }
    }
</style>


<div class="button-container">
    <?php foreach ($_category as $c => $p): ?>
        <button data-get="/page/category/<?= $p ?>.php"><?= $c ?></button>
    <?php endforeach; ?>
</div>
<?php
include '../_foot.php';