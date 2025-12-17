<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/system/logo.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <header>
        <h1><a href="/">TECH CAFE PRO</a></h1>
        <?php if ( $_user): ?>
            <div>
                <?= $_user -> name ?><br>
                <?= $_user -> role ?>
                <img src="../images/user_photos/<?= $_user -> profile_image_path ?>">
            </div>
        <?php endif ?>
    </header>

    <?php if ($msg = temp('info')): ?>
    <script>alert('<?php echo $msg; ?>');</script>

<?php endif; ?>
    <nav>
        <?php 
        $role = $_SESSION['user'] -> role ?? null;
        

        if (in_array($role,['member','customer'])): ?>
            <?php //echo $_SESSION['user']; ?>
            <a href="/page/home.php">Home</a>
            <a href="/page/menu.php">Menu</a> 
            <div class="right">
                <a href="/page/cart.php">Cart
                    <?php
                        $cart = get_cart();
                        $count = count($cart);
                        if ($count) echo "($count)";
                    ?>
                </a>
                <a href="/page/profile.php">Profile</a>
            </div>
        <?php elseif (in_array($role, ["admin"])): ?>
            <?php //echo $_SESSION['user']; ?>
            <a href="/page/admin6699/admin_home.php">Home</a>
            <!-- <a href="/page/menu.php">Menu</a>      Admin also want to see menu? -->
            <!-- <a href="/page/order_crud.php">All orders</a> -->
            <!-- <a href="/page/product_crud.php">All products</a> -->
            <!-- <a href="/page/admin6699/admin_crud.php">All admin</a> -->
            <!-- <a href="/page/tag_crud.php">All tags</a> use for check immediately -->
            <!-- <a href="/page/insert.php">Insert</a>
            <a href="/page/update.php">Update</a>
            <a href="/page/delete.php">Delete</a> -->
            <div class="right">
                <a href="/page/profile.php">Profile</a>
            </div>
        <?php else: ?>
            <?php //echo $_SESSION['user_id']; ?>
            <!-- <a href="/page/product_crud.php">All products</a> use for check immediately -->
            <!-- <a href="/page/tag_crud.php">All tags</a> use for check immediately -->
            <!-- <a href="/page/category_crud.php">All categories</a> use for check immediately -->
            <a href="/page/home.php">Home</a>
            <a href="/page/menu.php">Menu</a>
            <a href="/page/register.php" class="right">Register</a>
            <a href="/page/login.php">Login</a>
        <?php endif; ?>
    </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>