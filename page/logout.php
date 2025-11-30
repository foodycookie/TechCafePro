<?php
require '../_base.php';

session_unset();
temp('info', 'Logout Successfully!');
redirect('/page/home.php');