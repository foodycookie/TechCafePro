<?php
require '../_base.php';
include '../_head.php';

session_destroy();
redirect('/page/home.php');

include '../_foot.php';