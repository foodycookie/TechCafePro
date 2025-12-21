<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/system/logo.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <header>
        <h1><a href="/">TECH CAFE PRO</a></h1>
        <?php if ( $_user): ?>
            <div>
                <?= $_user -> name ?><br>
                <?= $_user -> role ?>
                <img src="/images/user_photos/<?= $_user -> profile_image_path ?>">
            </div>
        <?php endif ?>
    </header>

    <?php if ($msg = temp('info')): ?>
    <script>alert('<?php echo $msg; ?>');</script>
    <?php endif; ?>

    <nav>
        <?php 
        $role = $_SESSION['user'] -> role ?? null;
        
        if (auth2('member','customer')): ?>
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
                <a href="/page/logout.php">Logout</a>
            </div>

        <?php elseif (auth2('admin')): ?>
            <a href="/page/admin6699/admin_home.php">Home</a>
            <div class="right">
                <a href="/page/admin6699/admin_profile.php">Profile</a>
                <a href="/page/logout.php">Logout</a>
            </div>

        <?php else: ?>
            <a href="/page/home.php">Home</a>
            <a href="/page/menu.php">Menu</a>
            <div class="right">
                <a href="/page/register.php">Register</a>
                <a href="/page/login.php">Login</a>
            </div>
            
        <?php endif; ?>
    </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>