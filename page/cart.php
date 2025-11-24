<?php
require '../_base.php';

$_title = 'Cart';
include '../_head.php';
?>

<html>
    <button type="button" onclick="window.location.href='/page/checkout.php'">Go to checkout</button>
    <button type="button" onclick="window.location.href='/page/home.php'">Cancel</button>
</html>

<?php
include '../_foot.php';
?>