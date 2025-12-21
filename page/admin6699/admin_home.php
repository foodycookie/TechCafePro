<?php
require '../../vendor/autoload.php';
require '../../_base.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

auth('admin');

// ----------------------------------------------------------------------------

$user_id = $_SESSION['user']->user_id ?? null;

function export_products_report() {
    global $_db;

    if (ob_get_level()) {
        ob_end_clean();
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $headers = ['Product ID','Product Name','Category Name','Price','Amount Sold','Revenue'];
    $sheet->fromArray($headers, null, 'A1');

    // Bold headers and add border
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    $sheet->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $products = $_db->query('SELECT products.*, categories.category_name 
                             FROM products
                             LEFT JOIN categories ON categories.category_id = products.category_id
                             ORDER BY products.sold DESC, products.category_id ASC, products.product_id ASC')
                    ->fetchAll();

    $row = 2;
    foreach ($products as $product) {
        $revenue = $product->price * $product->sold;

        $sheet->setCellValue('A'.$row, $product->product_id)
              ->setCellValue('B'.$row, $product->product_name)
              ->setCellValue('C'.$row, $product->category_name)
              ->setCellValue('D'.$row, $product->price)
              ->setCellValue('E'.$row, $product->sold)
              ->setCellValue('F'.$row, $revenue);

        // Format currency
        $sheet->getStyle('D'.$row)->getNumberFormat()
              ->setFormatCode('"RM"#,##0.00');
        $sheet->getStyle('F'.$row)->getNumberFormat()
              ->setFormatCode('"RM"#,##0.00');

        // Add border to this row
        $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);

        $row++;
    }

    // Auto-size columns
    foreach (range('A','F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="product_report.xlsx"');
    $writer->save('php://output');
    exit;
}

if (isset($_POST['export_report'])) {
    export_products_report();
}

// ----------------------------------------------------------------------------

// [ (object) ['product_name' => 'Chocolate', 'sold' => 120], (object) ['product_name' => 'Strawberry', 'sold' => 115] ]
$topSellingProducts = $_db->query('SELECT product_name, sold
                                   FROM products
                                   ORDER BY sold DESC, category_id ASC, product_id ASC
                                   LIMIT 15')
                          ->fetchAll();

// Prepare data for JS
// array_column($topSellingProducts, 'product_name') = Extract only the product_name values from the array
// json_encode() = Convert PHP array to JSON string use by JS

// $productNames = ['Chocolate', 'Strawberry']; (After array_column())
// $productNames = '["Chocolate","Strawberry"]'; (After json_encode())
$productNames = json_encode(array_column($topSellingProducts, 'product_name'));
$soldQuantities = json_encode(array_column($topSellingProducts, 'sold'));

// ----------------------------------------------------------------------------

$_title = 'Admin | Home Page';
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
                        <button class="icon-btn"  onclick="location.href='/page/admin6699/customer_crud.php'">
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
                        <button class="icon-btn" onclick="location.href='/page/admin6699/admin_crud.php'">
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

                        <button class="icon-btn" onclick="location.href='/page/admin6699/product_crud.php'">
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

                        <button class="icon-btn" onclick="location.href='/page/admin6699/order_crud.php'">
                            <img src="../../images/system/order_icon.png" alt="Order Icon">
                            <span>Order</span>
                            <div class="badge-group">
                                <div class="badge_available">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM payments
                                        WHERE status = "Completed"
                                    ');
                                    $stm -> execute();
                                    $o_active_amount = $stm -> fetchColumn();

                                    echo $o_active_amount;
                                    ?>
                                </div>
                                <div class="badge_unavailable">
                                    <?php $stm = $_db -> prepare('
                                        SELECT COUNT(*) FROM payments
                                        WHERE status != "Completed"
                                    ');
                                    $stm -> execute();
                                    $o_unactive_amount = $stm -> fetchColumn();

                                    echo $o_unactive_amount;
                                    ?>
                                </div>
                            </div>
                        </button>

                        <button class="icon-btn" onclick="location.href='/page/admin6699/category_crud.php'">
                            <img src="../../images/system/category_icon.png" alt="Category Icon">
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

                        <button class="icon-btn" onclick="location.href='/page/admin6699/tag_crud.php'">
                            <img src="../../images/system/tag_icon.png" alt="Tag Icon">
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

<!-- ---------------------------------------------------------------------------- -->

<h2>Top 15 Selling Products</h2>

<form method="post">
    <button type="submit" id="export_report" name="export_report" onclick="changeButtonTextAfterClickThenChangeItBack(this, 'File Exported!')">Export Full Data as Report</button>
</form>

<canvas id="topSellingProductChart" width="800" height="400" style="background-color: #fff;"></canvas>
<br>
<button id="exportChartButton">Export Chart as Image</button>

<script>
    const xValues = <?php echo $productNames; ?>;
    const yValues = <?php echo $soldQuantities; ?>;

    const chart = new Chart("topSellingProductChart", {
        type: 'bar',
        data: {
            labels: xValues,
            datasets: [{
                label: 'Units Sold',
                data: yValues,
                backgroundColor: 'rgba(99, 123, 255, 0.7)',
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Top 15 Selling Products'
                }
            },
        }
    });

    // Download
    document.getElementById('exportChartButton').addEventListener('click', function() {
        const link = document.createElement('a');
        link.href = chart.toBase64Image();
        link.download = 'top_15_selling_products.png';
        link.click();
    });
</script>

<?php
include '../../_foot.php';