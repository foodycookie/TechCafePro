<?php
require '../_base.php';

$_title = 'payment';
include '../_head.php';
?>

<html>
    <button type="button" onclick="window.location.href='/page/after_payment.php'">Go to after payment</button>
    <button type="button" onclick="window.location.href='/page/home.php'">Cancel</button>
</html>

<?php
include '../_foot.php';
?>