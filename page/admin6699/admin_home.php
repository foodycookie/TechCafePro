<?php
require '../../_base.php';
$user_id = $_SESSION['user']->user_id ?? null;

$_title = 'Page | Home';
include '../../_head.php';
?>

<style>
    table {
    margin: auto;
    border-collapse: collapse;
    }

    .button-container {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        padding: 20px;
    }

    /* Button styling */
    .icon-btn {
        position: relative;
        width: 140px;
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-family: Arial, sans-serif;
    }

    /* Icon image */
    .icon-btn img {
        width: 75px;
        height: 75px;
        margin-bottom: 8px;
    }

    /* Button label */
    .icon-btn span {
        margin-top: 6px;
        font-weight: bold;
    }

    /* Number badge */
    .badge_available {
        background: green;
    }

    .badge_unavailable {
        background: red;
    }

    .badge_available,
    .badge_unavailable {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        color: white;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge-group {
        position: absolute;
        bottom: 10px;
        display: flex;
        gap: 6px;
    }

    .icon-btn:hover {
        transform: translateY(-12px);
    }
</style>
<h1>Show Report or table</h1>


<body>
    <table>
        <tbody >
            <tr>
                <td style="justify-content : center">
                    <div class="button-container">
                        <button class="icon-btn"  onclick="location.href='../customer_crud.php'">
                            <img src="../../images/system/customer_icon.jpg" alt="Customer Icon">
                            <span>Customer</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM users 
                                        WHERE (role = "customer" OR role = "member")
                                        AND status = 1
                                    ');
                                    $stm -> execute();
                                    $c_active_amount = $stm -> fetchColumn();

                                    echo $c_active_amount;
                                    ?>
                                </div>
                                <div class="badge_unavailable">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM users 
                                        WHERE (role = "customer" OR role = "member")
                                        AND status = 0
                                    ');
                                    $stm -> execute();
                                    $c_unactive_amount = $stm -> fetchColumn();

                                    echo $c_unactive_amount;
                                    ?>
                                </div>
                            </div>
                        </button>
                        <?php if ($user_id == 1):?>
                        <button class="icon-btn" onclick="location.href='./admin_crud.php'">
                            <img src="../../images/system/admin_icon.jpg" alt="Admin Icon">
                            <span>Admin</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM users 
                                        WHERE role = "admin" AND status = 1
                                    ');
                                    $stm -> execute();
                                    $a_active_amount = $stm -> fetchColumn();

                                    echo $a_active_amount;
                                    ?>
                                </div>
                                <div class="badge_unavailable">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM users 
                                        WHERE role = "admin" AND status = 0
                                    ');
                                    $stm -> execute();
                                    $a_unactive_amount = $stm -> fetchColumn();

                                    echo $a_unactive_amount;
                                    ?>
                                </div>
                            </div>
                        </button>
                        <?php endif?>

                        <button class="icon-btn" onclick="location.href='/page/product_crud.php'">
                            <img src="../../images/system/product_icon.png" alt="Product Icon">
                            <span>Product</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM products
                                        WHERE status = 1
                                    ');
                                    $stm -> execute();
                                    $p_active_amount = $stm -> fetchColumn();

                                    echo $p_active_amount;
                                    ?>
                                </div>
                                <div class="badge_unavailable">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM products
                                        WHERE status = 0
                                    ');
                                    $stm -> execute();
                                    $p_unactive_amount = $stm -> fetchColumn();

                                    echo $p_unactive_amount;
                                    ?>
                                </div>
                            </div>
                        </button>

                        <button class="icon-btn" onclick="location.href='/page/order_crud.php'">
                            <img src="../../images/system/order_icon.png" alt="Order Icon">
                            <span>Order</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM orders
                                    ');
                                    $stm -> execute();
                                    $o_active_amount = $stm -> fetchColumn();

                                    echo $o_active_amount;
                                    ?>
                                </div>
                                <div class="badge_unavailable">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM orders
                                    ');
                                    $stm -> execute();
                                    $o_unactive_amount = $stm -> fetchColumn();

                                    echo $o_unactive_amount;
                                    ?>
                                </div>
                            </div>
                        </button>

                        <button class="icon-btn" onclick="location.href='/page/category_crud.php'">
                            <img src="../../images/system/product_icon.png" alt="Product Icon">
                            <span>Category</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM categories
                                        WHERE status = 1
                                    ');
                                    $stm -> execute();
                                    $p_active_amount = $stm -> fetchColumn();

                                    echo $p_active_amount;
                                    ?>
                                </div>
                                <div class="badge_unavailable">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM categories
                                        WHERE status = 0
                                    ');
                                    $stm -> execute();
                                    $p_unactive_amount = $stm -> fetchColumn();

                                    echo $p_unactive_amount;
                                    ?>
                                </div>
                            </div>
                        </button>

                        <button class="icon-btn" onclick="location.href='/page/tag_crud.php'">
                            <img src="../../images/system/order_icon.png" alt="Order Icon">
                            <span>Tag</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM tags
                                    ');
                                    $stm -> execute();
                                    $o_active_amount = $stm -> fetchColumn();

                                    echo $o_active_amount;
                                    ?>
                                </div>
                            </div>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<?php
include '../../_foot.php';