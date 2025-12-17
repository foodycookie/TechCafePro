<?php
require '../_base.php';

$_title = 'Page | Home';
include '../_head.php';
?>
<style>
    #img {
        width: 600px;
        height: 400px;
        border: 10px solid #fff;
        box-shadow: 0 0 5px #000;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<p id="p">Image 1 of 4</p>
<img id="img" src="../images/system/placeholder.jpg">
<!-- <button onclick="location.href='/page/admin6699/admin_login.php'">Admin Login</button> -->

<script>
    const arr = [
        '../images/system/placeholder.jpg',
        '../images/system/placeholder.jpg',
        '../images/system/placeholder.jpg',
        '../images/system/placeholder.jpg'
    ];

    let i = 0;

    $('#img').on('click', e => {
        i = ++i % arr.length;

        $('#p').text(`Image ${i + 1} of 4`);

        $('#img')
            .hide()
            .prop('src', arr[i])
            .fadeIn();
    });
</script>


<?php
include '../_foot.php';