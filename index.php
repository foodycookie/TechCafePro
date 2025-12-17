<?php
require '_base.php'; // set KL time
?>

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
    </header>
    <h2>Enjoy your Time!!</h2>

    <main>
        <div style = "text-align:center; margin:50px auto;">
        <?php if (in_array($_SESSION['user'] -> role ?? null, ['admin'])): ?>
            <button data-get="/page/admin6699/admin_home.php">Let's Start</button>
        <?php else: ?>
            <button data-get="/page/home.php">Let's Start</button>
        <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
include '_foot.php';
?>