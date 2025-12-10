<?php
require '../../_base.php';

$_title = 'Cake';
include '../../_head.php';
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

<?php foreach ($_cake as $b => $p): ?>
<p><?= $b ?></p>
<img id="img" src="../../images/system/placeholder.jpg">
<?php endforeach; ?>

<script>
    const arr = [
        '../../images/system/placeholder.jpg',
        '../../images/system/placeholder.jpg',
        '../../images/system/placeholder.jpg',
        '../../images/system/placeholder.jpg'
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
include '../../_foot.php';