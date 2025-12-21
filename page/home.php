<?php
require '../_base.php';

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Home Page';
include '../_head.php';
?>

<style>
    p{
        text-align: justify;
        font-size: 28px;
        font-weight: 525;
        color: brown;
        font-family: 'Times New Roman', Times, serif;
    }

    main img{
        height: 550px;
        width: 425px;
        padding-left: 50px;
        float: right;
    }

    a{
        color: brown;
    }

    a:hover{
        color: orangered;
    }
</style>

<main>
    <img src="../images/system/cafeimage.jpg" alt="Cafe Image">
    <p>Welcome to <strong>Tech Cafe Pro</strong>! Located in Setapak, we are a modern café offering freshly 
        brewed beverages and delicious comfort food to give you a relaxing and enjoyable café experience.</p>

    <p><strong>Tech Cafe Pro</strong> is a locally owned café established in 2023. Our menu features a variety of favorites, 
        including premium coffee, refreshing beverages, breads and delightful desserts. Every item is carefully prepared to 
        ensure great taste and quality. Whether you&rsquo;re here to study, work, catch up with friends, or enjoy a casual meeting, 
        our café provides the perfect setting.</p>

    <p>Our team is made up of skilled baristas and friendly staff who are committed to delivering excellent service. Our mission 
        is to create a comfortable space where customers can enjoy great desserts, quality drinks, and a pleasant atmosphere.</p>

    <p>To learn more about <strong>Tech Cafe Pro</strong> or make an enquiry, feel free to contact us anytime. 
        You can reach us by phone at (+60 12-34567890) or via email at 
        <strong><a href="mailto:techcafepro@gmail.com" style="text-decoration: none;">techcafepro@gmail.com</a></strong>. 
        We look forward to welcoming you!</p>

    <p>Opening Hours:<br>
        Tuesday to Sunday (12:00pm - 10:00pm)<br>
        Monday (Closed)</p><br>
</main>

<?php
include '../_foot.php';