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

    <nav>    
        <a href="/page/home.php">Home</a>
        <a href="/page/menu.php">Menu</a>
        <a href="/page/category.php">Category</a>
        <a href="/page/login.php" class = "right">Login</a>
    </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>