<?php
require '../_base.php';

$_title = 'Page | Menu';
include '../_head.php';
?>

<nav>
    <?php foreach ($_category as $c => $p): ?>
        <a href="/page/category/<?= $p ?>.php"><?= $c ?></a>
    <?php endforeach; ?>
</nav>

<?php foreach ($_category as $c => $p): ?>
        <h2 style="text-align: center;"><?= $c ?></h2>
        <div class = "button-item" >
        <?php foreach (${"_$p"} as $name => $pointer): ?>
            <button><?= $name ?></button>
        <?php endforeach; ?>
        </div>
<?php endforeach; ?>

<style>
    .button-item {
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    .button-item button {
        width: 200px;
        height: 200px;
        font-size: 1.5rem;
        cursor: pointer;
    }

</style>

<?php
include '../_foot.php';