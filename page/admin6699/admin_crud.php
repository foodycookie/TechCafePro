<?php
include '../../_base.php';

function update_multiple() {
    global $_db;

    $user_id = req('user_id', []);
    if (!is_array($user_id)) {
        $user_id = [$user_id];
    }
    $selected_field_to_update = req('selected_field_to_update');

    if ($selected_field_to_update != '') {
        $count = 0;

        if ($selected_field_to_update == 'active') {
            $stm = $_db->prepare('
                UPDATE users
                SET status = 1
                WHERE user_id = ? AND role = "admin" AND user_id != 1
            ');
        }

        elseif ($selected_field_to_update == 'inactive') {
            $stm = $_db->prepare('
                UPDATE users
                SET status = 0
                WHERE user_id = ? AND role = "admin" AND user_id != 1
            ');
        }

        foreach ($user_id as $uid) {
            if ($uid == 1) {
                continue;
            }

            $count += $stm->execute([$uid]);
        }

        temp('info', "$count record(s) updated to $selected_field_to_update!");
        redirect();
    }
}

// function import_admin_csv() {
//     if ($_FILES['import']['type'] != 'text/csv') {
//         temp('info', 'Not a CSV file!');
//         redirect();
//     }

//     global $_db;

//     $header_row = true;
//     $stm1 = $_db->prepare('INSERT INTO users (name, email, password, profile_image_path, role)
//                            VALUES (?, ?, SHA1(?), ?, "admin")');
//     // $stm2 = $_db->prepare('INSERT INTO product_tags (product_id, tag_id)
//     //                        VALUES (?, ?)');
//     $success_count = 0;
//     $failed_count = 0;

//     // $_FILES['userfile']['tmp_name (or any attribute)']
//     //The temporary filename of the file in which the uploaded file was stored on the server
//     $handle = fopen($_FILES['import']['tmp_name'], 'r');

//     while($data = fgetcsv($handle)) {
//         if ($header_row == true) {
//             $header_row = false;
//             continue;
//         }

//         if (count($data) != 4) {
//             $failed_count++;
//             continue;
//         }

//         $name = trim($data[0]);
//         $email = trim($data[1]);
//         $password = trim($data[2]);
//         $profile_image_path = trim($data[3]);


//         // Validation: name
//         if (($name === '') || (strlen($name) > 100) || (!is_unique($name, 'users', 'name'))) {
//             $failed_count++;
//             continue;
//         }

//         // Validation: email
//         if (($email === '') || (strlen($name) > 100) || (!is_email($email)) || (!is_unique($name, 'users', 'name'))) {
//             $failed_count++;
//             continue;
//         }

//         // Validation: password
//         if (($password === '') || (strlen($password) < 5 || (strlen($password) > 100))) {
//             $failed_count++;
//             continue;
//         }

//         $success_count += $stm1->execute([$name, $email, $password, $profile_image_path]);

//     }

//     fclose($handle);
//     temp('info', "$success_count record(s) inserted, $failed_count record(s) failed!");
//     redirect();
// }

function import_photo_file($_PHOTO) {
    if($_PHOTO['files']['name'][0] == ""){
        return "Empty file";
    }
    else if (!str_starts_with($_PHOTO->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    }
    else if ($_PHOTO->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }
    
        $path = "../../images/user_photos/";

        $name = $_PHOTO['files']['name'];
        $tmp_names = $_PHOTO['files']['tmp_name'];

        $files_array = array_combine($tmp_names, $name);

        foreach($files_array as $tmp_names => $image_name) {
            move_uploaded_file($tmp_names, $path.$image_name);
        }

        return "success";
    
}

function import_users_with_photos() {
    global $_db, $_err;
// ----------------------------------------------------------------------------

// (1) Sorting

    // Counters
    $success_count       = 0; // users inserted
    $failed_user_count   = 0; // users skipped (duplicate or incomplete)
    $photo_success_count = 0; // photos successfully uploaded
    $failed_photo_count  = 0; // photos skipped (duplicate or not matched)

    // 1️⃣ Read CSV
    if (!isset($_FILES['import_csv']) || $_FILES['import_csv']['error'] !== UPLOAD_ERR_OK) {
        $_err['csv'] = 'CSV file is required or upload failed';
        return;
    }

    $handle = fopen($_FILES['import_csv']['tmp_name'], 'r');
    $header = true;
    $new_users = [];

    while (($data = fgetcsv($handle)) !== false) {
        if ($header) { $header = false; continue; }

        if (count($data) < 4) {
            $failed_user_count++; // incomplete data
            continue;
        }

        [$name, $email, $password, $photo] = array_map('trim', $data);

        // Skip if user exists
        $stm = $_db->prepare("SELECT user_id FROM users WHERE name = ? OR email = ?");
        $stm->execute([$name, $email]);
        if ($stm->fetch()) {
            $failed_user_count++; // duplicate
            continue;
        }

        $new_users[] = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'photo' => $photo
        ];
    }
    fclose($handle);

    // 2️⃣ Upload photos
    if (!empty($_FILES['photos']['name'][0])) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/images/user_photos/';
        if (!is_dir($path)) mkdir($path, 0755, true);

        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        $user_photos = array_column($new_users, 'photo');

        foreach ($_FILES['photos']['name'] as $i => $name) {

            if (!in_array($_FILES['photos']['type'][$i], $allowed)) {
                $failed_photo_count++;
                continue;
            }

            if ($_FILES['photos']['size'][$i] > 1 * 1024 * 1024) {
                $failed_photo_count++;
                continue;
            }

            $filename = basename($name);

            // Skip if file is not in CSV or already exists
            if (!in_array($filename, $user_photos) || file_exists($path . $filename)) {
                $failed_photo_count++;
                continue;
            }

            move_uploaded_file($_FILES['photos']['tmp_name'][$i], $path . $filename);
            $photo_success_count++;
        }
    }

    // 3️⃣ Insert users into DB
    $stm = $_db->prepare('
        INSERT INTO users (name, email, password, profile_image_path, role)
        VALUES (?, ?, SHA1(?), ?, "admin")
    ');

    foreach ($new_users as $user) {
        $photo_path = $_SERVER['DOCUMENT_ROOT'] . '/images/user_photos/' . $user['photo'];

        if (!file_exists($photo_path)) {
            $user['photo'] = ''; // photo missing, optional default
        }

        $stm->execute([$user['name'], $user['email'], $user['password'], $user['photo']]);
        $success_count++;
    }

    // 4️⃣ Report
    $temp_msg = "
        $success_count user(s) inserted.
        $failed_user_count user(s) failed (duplicate/incomplete).
        $photo_success_count photo(s) uploaded.
        $failed_photo_count photo(s) skipped (duplicate/not matched).
    ";

temp('info', nl2br($temp_msg));
    redirect();
}

function upload_user_photos($photos, $new_users) {
    global $_err;

    $path = $_SERVER['DOCUMENT_ROOT'] . '/images/user_photos/';
    if (!is_dir($path)) mkdir($path, 0755, true);

    if (count($photos['name']) > 25) {
        $_err['photos'] = 'Maximum 25 photos per upload';
        return false;
    }

    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];

    $user_photos = array_column($new_users, 'photo');

    foreach ($photos['name'] as $i => $name) {

        if (!in_array($photos['type'][$i], $allowed)) continue;
        if ($photos['size'][$i] > 1 * 1024 * 1024) continue;

        $filename = basename($name);

        // Skip if file is not in new_users or already exists
        if (!in_array($filename, $user_photos)) continue;
        if (file_exists($path . $filename)) continue;

        move_uploaded_file($photos['tmp_name'][$i], $path . $filename);
    }

    return true;
}

$fields = [
    'user_id'        => 'Id',
    'name'           => 'Name',
    'email'          => 'Email',
    'role'           => 'Role',
    'status'         => 'Status',
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'user_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Filtering
$name   = req('name', '');
// $user_id = req('user_id', '');

// (3) Paging
$page = req('page', 1);

require_once '../../lib/SimplePager.php';

// ----------------------------------------------------------------------------
// Build SQL for SimplePager
$baseSQL = "FROM users WHERE role = 'admin' AND name LIKE ?";
$params = ["%$name%"];

// Sorting applied after pagination
$sql = "SELECT user_id $baseSQL ORDER BY $sort $dir";

// Pager
$p = new SimplePager($sql, $params, 10, $page);

// Fetch full product rows AFTER pagination
$arr = [];
foreach ($p->result as $row) {
    $full = $_db->prepare("
        SELECT u.*
        FROM users u 
        WHERE u.user_id = ?
    ");
    $full->execute([$row->user_id]);
    $arr[] = $full->fetch();
}

if (isset($_POST['update_multiple'])) {
    update_multiple();
}

// if (isset($_POST['import_submit'])) {
//     import_admin_csv();
// }

if (isset($_POST['import_photo'])) {
    import_photo_file($_PHOTO);
}

if (isset($_POST['import_users'])) {
    import_users_with_photos();
}

$_title = 'All admin';
include '../../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 125px;
    }
</style>

<form>
    <?= html_search('name','placeholder="Search user..."') ?>

    <button>Search</button>
</form>

<form method="POST" id="modify_multiple">
    <select name="selected_field_to_update">
        <option value="">Select Field</option>
        <option value="active">Update: Status to Active</option>
        <option value="inactive">Update: Status to Inactive</option>
    </select>

    <button type="submit" id="update_multiple" name="update_multiple" data-confirm>Update Selected</button>
    <!-- <button formaction="admin_delete.php" data-confirm>Delete Multiple</button> -->
</form>

<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <th><input type="checkbox" onclick="toggleAll(this, 'user_id[]')"></th>
        <?= table_headers(
                $fields,
                $sort,
                $dir,
                "page={$p->page}&name={$name}"
            ) ?>
    </tr>

    <?php foreach ($arr as $m): ?>
    <tr>
        <td>
            <input type="checkbox"
                   name="user_id[]"
                   value="<?= $m->user_id ?>"
                   form="modify_multiple">
        </td>
        <td><?= $m->user_id ?></td>
        <td><?= $m->name ?></td>
        <td><?= $m->email ?></td>
        <td><?= $m->role ?></td>
        <td><?= (int)$m->status === 1 ? 'Active' : 'Inactive' ?></td>
        <td>
            <button data-get="/page/admin6699/admin_update.php?user_id=<?= $m->user_id ?>">Update</button>
            <!-- <button data-post="/page/admin6699/admin_delete.php?user_id=<?= $m->user_id ?>" id="delete" data-confirm>Delete</button> -->
            <img src="../../images/user_photos/<?= $m->profile_image_path ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<!-- <//?= $p->html("sort=$sort&dir=$dir&name=$name&user_id=$user_id") ?> -->
<?= $p->html("sort=$sort&dir=$dir&name=$name") ?>

<p>
    <button data-get="/page/admin6699/admin_insert.php">Insert</button>
    <button data-get="/page/admin6699/admin_home.php">Back to Home</button>
</p>

<!-- <form method="post" enctype="multipart/form-data">
    <label for="import">Insert CSV File</label>
    <?= html_file('import', '.csv') ?>
    <?= err('import') ?>
    <section>
        <button type="submit" id="import_submit" name="import_submit">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form> -->
<form method="post" enctype="multipart/form-data">
    <label>Import Admin CSV</label>
        <input type="file" name="import_csv" accept=".csv">
        <?= err('csv') ?>
        <br>
    <label>Upload Admin Photos</label>
        <input type="file" name="photos[]" multiple accept="image/*">
    <section>
        <button type="submit" name="import_users">Import Users</button>
    </section>
</form>

<form method="post" enctype="multipart/form-data">
    <label>Insert Admin Photo</label>
    <?= html_file('photo', 'accept="image/*"') ?>
    <?= err('photo') ?>
    <section>
        <button type="submit" name="import_photo">Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../../_foot.php';
?>
