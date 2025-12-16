<?php
require '../_base.php';

print_r ($_SESSION);
die();
session_unset();
temp('info', 'Logout Successfully!');
redirect('/page/home.php');