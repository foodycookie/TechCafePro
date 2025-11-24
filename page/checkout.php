<?php
require '../_base.php';

$_title = 'Checkout';
include '../_head.php';
?>

<html>
    <button type="button" onclick="window.location.href='/page/payment.php'">Go to payment</button>
    <button type="button" onclick="window.location.href='/page/home.php'">Cancel</button>
</html>

<?php
include '../_foot.php';
?>