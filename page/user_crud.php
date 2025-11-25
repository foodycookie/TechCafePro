<?php
require '../_base.php';

$_title = 'All user';
include '../_head.php';
?>

<html>
    <button type="button" onclick="window.location.href='/page/update.php'">Update</button>
    <button type="button" onclick="window.location.href='/page/delete.php'">Delete</button>
    <button type="button" onclick="window.location.href='/page/insert.php'">Insert</button>
</html>

<?php
include '../_foot.php';
?>