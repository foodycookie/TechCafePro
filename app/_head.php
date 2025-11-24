<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon.jpg">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <header>
        <h1><a href="/">TECH CAFE PRO</a></h1>
    </header>

    <?php if ($msg = temp('info')): ?>
    <script>alert('<?php echo $msg; ?>');</script>

<?php endif; ?>
    <nav>
        <?php if (is_login() && $_SESSION['user_id'] != '0'): ?>
            <?php //echo $_SESSION['user_id']; ?>
            <a href="/page/home.php">Home</a>
            <a href="/page/menu.php">Menu</a>
            <a href="/page/category.php">Category</a>
            <div class="right">
                <a href="/page/cart.php">Cart</a>
                <a href="/page/profile.php">Profile</a>
            </div>
            <?php elseif (is_login() && $_SESSION['user_id'] == 0): ?>
                <?php// echo $_SESSION['user_id']; ?>
                <a href="/page/admin_home.php">Home</a>
                <a href="/page/insert.php">Insert</a>
                <a href="/page/update.php">Update</a>
                <a href="/page/delete.php">Delete</a>
                <div class="right">
                    <a href="/page/cart.php">Cart</a>
                    <a href="/page/profile.php">Profile</a>
                </div>
            <?php else: ?>
                ><?php //echo $_SESSION['user_id']; ?>
                <a href="/page/home.php">Home</a>
                <a href="/page/menu.php">Menu</a>
                <a href="/page/category.php">Category</a>
                <a href="/page/login.php" class="right">Login</a>
            <?php endif; ?>
    </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>