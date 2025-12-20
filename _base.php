<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

// ============================================================================
// General Page Functions
// ============================================================================

// Is GET request?
function is_get() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Is POST request?
function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Obtain GET parameter
function get($key, $value = null) {
    $value = $_GET[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain POST parameter
function post($key, $value = null) {
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null) {
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Redirect to URL
function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

// Set or get temporary session variable
function temp($key, $value = null) {
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    }
    else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}

// Obtain uploaded file --> cast to object
function get_file($key) {
    $f = $_FILES[$key] ?? null;
    
    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// Crop, resize and save photo
function save_photo($f, $folder, $width = 225, $height = 225) {
    $photo = uniqid() . '.jpg';
    
    require_once 'lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');

    return $photo;
}

// Is money?
function is_money($value) {
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}

// Is email?
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

// Is date?
function is_date($value, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $value);
    return $d && $d->format($format) == $value;
}

// Is time?
function is_time($value, $format = 'H:i') {
    $d = DateTime::createFromFormat($format, $value);
    return $d && $d->format($format) == $value;
}

// Return year list items
function get_years($min, $max, $reverse = false) {
    $arr = range($min, $max);

    if ($reverse) {
        $arr = array_reverse($arr);
    }

    return array_combine($arr, $arr);
}

// Return month list items
function get_months() {
    return [
        1  => 'January',
        2  => 'February',
        3  => 'March',
        4  => 'April',
        5  => 'May',
        6  => 'June',
        7  => 'July',
        8  => 'August',
        9  => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
}

// Return local root path
function root($path = '') {
    return "$_SERVER[DOCUMENT_ROOT]/$path";
}

// Return base url (host + port)
function base($path = '') {
    return "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/$path";
}

// Return TRUE if ALL array elements meet the condition given
function array_all($arr, $fn) {
    foreach ($arr as $k => $v) {
        if (!$fn($v, $k)) {
            return false;
        }
    }
    return true;
}

// ============================================================================
// HTML Helpers
// ============================================================================

// Placeholder for TODO
function TODO() {
    echo '<span>TODO</span>';
}

// Encode HTML special characters
function encode($value) {
    return htmlentities($value);
}

// Generate <input type='hidden'>
function html_hidden($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='hidden' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='text'>
function html_text($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='password'>
function html_password($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='number'>
function html_number($key, $min = '', $max = '', $step = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value'
                 min='$min' max='$max' step='$step' $attr>";
}

// Generate <input type='search'>
function html_search($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='date'>
function html_date($key, $min= '', $max = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='date' id='$key' name='$key' value='$value'
                 min='$min' max='$max' $attr>";
}

// Generate <input type='time'>
function html_time($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='time' id='$key' name='$key' value='$value' $attr>";
}

// Generate <textarea>
function html_textarea($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<textarea id='$key' name='$key' $attr>$value</textarea>";
}

// Generate SINGLE <input type='checkbox'>
function html_checkbox($key, $label = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    $status = $value == 1 ? 'checked' : '';
    echo "<label><input type='checkbox' id='$key' name='$key' value='1' $status $attr>$label</label>";
}

// Generate <input type='checkbox'> list
function html_checkboxes($key, $items, $br = false) {
    $values = $GLOBALS[$key] ?? [];
    if (!is_array($values)) $values = [];

    echo '<div>';
    foreach ($items as $id => $text) {
        $state = in_array($id, $values) ? 'checked' : '';
        echo "<label><input type='checkbox' id='{$key}_$id' name='{$key}[]' value='$id' $state>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
    echo '</div>';
}

// Generate <input type='radio'> list
function html_radios($key, $items, $br = false) {
    $value = encode($GLOBALS[$key] ?? '');
    echo '<div>';
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'checked' : '';
        echo "<label><input type='radio' id='{$key}_$id' name='$key' value='$id' $state>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
    echo '</div>';
}

// Generate <select>
function html_select($key, $items, $default = '- Select One -', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<select id='$key' name='$key' $attr>";
    if ($default !== null) {
        echo "<option value=''>$default</option>";
    }
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'selected' : '';
        echo "<option value='$id' $state>$text</option>";
    }
    echo '</select>';
}

// Generate <input type='file'>
function html_file($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}

// Generate table headers <th>
function table_headers($fields, $sort, $dir, $href = '') {
    foreach ($fields as $k => $v) {
        $d = 'asc'; // Default direction
        $c = '';    // Default class
        
        if ($k == $sort) {
            $d = $dir == 'asc' ? 'desc' : 'asc';
            $c = $dir;
        }

        echo "<th><a href='?sort=$k&dir=$d&$href' class='$c'>$v</a></th>";
    }
}

// ============================================================================
// Error Handlings
// ============================================================================

// Global error array
$_err = [];

// Generate <span class='err'>
function err($key) {
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    }
    else {
        echo '<span></span>';
    }
}

// ============================================================================
// Security
// ============================================================================

// Global user object
$_user = $_SESSION['user'] ?? null;

// Login user
function login($user, $url = '/') {
    $_SESSION['user'] = $user;

    if ($user -> role == "admin"){    
        $url = '/page/admin6699/admin_home.php';
    }
    else {
        $url = '/page/home.php';
    }

    redirect($url);

}

// Logout user
function logout($url = '/page/home.php') {
    unset($_SESSION['user']);
    redirect($url);
}

// Authorization
function auth(...$roles) {
    global $_user;
    if ($_user) {
        if ($roles) {
            if (in_array($_user->role, $roles)) {
                return; // OK
            }
        }
        else {
            return; // OK
        }
    }
    
    redirect('/page/login.php');
}

// Authorization
function auth2(...$roles) {
    global $_user;
    if ($_user) {
        if ($roles) {
            if (in_array($_user->role, $roles)) {
                return true; // OK
            }
        }
        else {
            return true; // OK
        }
    }
    
    return false;
}

// Check Login
function is_login()
{
    return isset($_SESSION['user_id']);
}

// ============================================================================
// Email Functions
// ============================================================================

// Demo Accounts:
// --------------
// AACS3173@gmail.com           xxna ftdu plga hzxl
// BAIT2173.email@gmail.com     ncom fsil wjzk ptre
// liaw.casual@gmail.com        buvq yftx klma vezl
// liawcv1@gmail.com            pztq znli gpjg tooe

// Initialize and return mail object
function get_mail() {
    require_once 'lib/PHPMailer.php';
    require_once 'lib/SMTP.php';

    $m = new PHPMailer(true);
    $m->isSMTP();
    $m->SMTPAuth = true;
    $m->Host = 'smtp.gmail.com';
    $m->Port = 587;
    $m->Username = 'techcafepro@gmail.com';
    $m->Password = 'jhab fsnd helx fdrq';
    $m->CharSet = 'utf-8';
    $m->setFrom($m->Username, '🍵 TechCafePro Admin');

    return $m;
}

// ============================================================================
// Shopping Cart
// ============================================================================

// Get shopping cart
function get_cart() {
    return $_SESSION['cart'] ?? [];
}

// Set shopping cart
function set_cart($cart = []) {
    $_SESSION['cart'] = $cart;
}

// Update shopping cart
function update_cart($id, $unit) {
    $cart = get_cart();

    if ($unit >= 1 && $unit <= 99 && is_exists($id, 'products', 'product_id')) {
        $cart[$id] = $unit;

        if ($cart[$id] > 99) {
            $cart[$id] = 99;
        }

        ksort($cart);
    }
    else {
        unset($cart[$id]);
    }

    set_cart($cart);
}

// Add to shopping cart
function add_cart($id, $unit) {
    $cart = get_cart();

    if ($unit >= 1 && $unit <= 99 && is_exists($id, 'products', 'product_id')) {
        $cart[$id] += $unit;

        if ($cart[$id] > 99) {
            $cart[$id] = 99;
        }
        
        ksort($cart);
    }
    else {
        unset($cart[$id]);
    }

    set_cart($cart);
}

// Add product to cart
// function cart_add($id, $qty = 1) {
//     $cart = get_cart();
//     if (isset($cart[$id])) {
//         $cart[$id] += $qty;
//         if ($cart[$id] > 10) $cart[$id] = 10; // Max 10
//     } else {
//         $cart[$id] = $qty;
//     }
//     set_cart($cart); 
// }

// Remove product from cart
// function cart_remove($id) {
//     $cart = get_cart();
//     unset($cart[$id]);
//     set_cart($cart);
// }

function get_chosen_cart_item_for_order() {
    return $_SESSION['chosen_cart_item_for_order'] ?? [];
}

function set_chosen_cart_item_for_order($chosen_cart_item_for_order = []) {
    $_SESSION['chosen_cart_item_for_order'] = $chosen_cart_item_for_order;
}

function update_chosen_cart_item_for_order($id, $unit) {
    $chosen_cart_item_for_order = get_chosen_cart_item_for_order();

    if ($unit >= 1 && $unit <= 99 && is_exists($id, 'products', 'product_id')) {
        $chosen_cart_item_for_order[$id] = $unit;
        ksort($chosen_cart_item_for_order);
    }
    else {
        unset($chosen_cart_item_for_order[$id]);
    }

    set_chosen_cart_item_for_order($chosen_cart_item_for_order);
}

// ============================================================================
// Payment Gateway Functions
// ============================================================================

define('STRIPE_SECRET_KEY', 'sk_test_51SevAT0o7LaBu7HImnNfHt2OkAvHEYEy2LQLwlKUZ17qFNWMQdosFU6pdceGZzDm4LgNtPeTiqsPK5ZRPdWFSGdV00i9vIo62c');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51SevAT0o7LaBu7HIANYhjsbMyI1UpSnARa7k7zogSIHWOHBMm8XPG4gM83dj3OtsWWBZJowmUm8AZoBeCCY8iTb60081x4dWFd');

// ============================================================================
// Database Setups and Functions
// ============================================================================

// Global PDO object
$_db = new PDO('mysql:dbname=techcafepro_db', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

// Is unique?
function is_unique($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() == 0;
}

// Is exists?
function is_exists($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}

// ============================================================================
// Export Data
// ============================================================================

// Usage: export($temp_file, "products.csv")
function export($file, $exported_file_name) {
    if ($file == null) {
        temp('info', 'File not found!');
        redirect();
    }

    if ($exported_file_name == null) {
        temp('info', 'Exported file name cannot be empty!');
        redirect();
    }

    // Reset the file pointer to the start of the file
    fseek($file, 0);
    // Tell the browser we want to save it instead of displaying it
    header("Content-Disposition: attachment; filename=$exported_file_name;");
    // Make php send the remaining lines after pointer to the browser
    fpassthru($file);
    fclose($file);
    // Use exit to get rid of unexpected output afterward
    exit();
}

// ============================================================================
// Some JS
// ============================================================================
?>

<script>
    function toggle_visibility(target) {
        var element = document.getElementById(target);
        if (element.style.visibility=='visible') {
            element.style.visibility = 'hidden';
        }
        else
            element.style.visibility = 'visible';
    }

    function toggleAll(source, target) {
        document.querySelectorAll('input[name="' + target + '"]')
            .forEach(cb => cb.checked = source.checked);
    }

    function toggleAllForNameStartedWith(source, nameStartedWith) {
        document.querySelectorAll('input[name^="'+ nameStartedWith +'"]').forEach(cb => {
            cb.checked = source.checked;
        });
    }

    function changeButtonTextAfterClickThenChangeItBack(source, message) {
        const originalText = source.innerText;
        source.innerText = message;

        setTimeout(() => {
            source.innerText = originalText;
        }, 2000);
    }
</script>

<?php
// ============================================================================
// Global Constants and Variables
// ============================================================================
