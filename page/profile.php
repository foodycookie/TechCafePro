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
    <h2>Profile</h2>

    <main>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/logout.php">Logout</button>
        </div>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/update_profile_picture.php">Update profile pic</button>
        </div>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/order_history.php">Order history</button>
        </div>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/home.php">Back</button>
        </div>
    </main>

</body>
</html>

<?php
require '../_base.php';

        


include '../_foot.php';