<<<<<<< HEAD
<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php?action=login");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

/* =========================
   HANDLE ACCOUNT DEACTIVATION
========================= */
if (isset($_POST['deactivate_account'])) {

    $stmt = $conn->prepare("
        UPDATE users 
        SET is_active = 0 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);

    // End session after deactivation
    session_destroy();

    header("Location: ../auth.php?action=login&deactivated=1");
    exit;
}

/* =========================
   FETCH USER + ADDRESS
========================= */
$sql = "
SELECT 
    u.user_id, u.name, u.email, u.profile_image_path,
    sa.address, sa.city, sa.postal_code, sa.state, sa.country
FROM users u
LEFT JOIN shipping_addresses sa ON u.user_id = sa.user_id
WHERE u.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

/* =========================
   HANDLE UPDATE
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name        = trim($_POST['name']);
    $email       = trim($_POST['email']);
    $address     = trim($_POST['address']);
    $city        = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $state       = trim($_POST['state']);
    $country     = trim($_POST['country']);

    if (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    /* ===== PROFILE IMAGE ===== */
    $imagePath = $user['profile_image_path'];

    if (!empty($_FILES['profile_image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, JPEG, PNG allowed";
        } else {
            $imagePath = "profile_" . $user_id . "_" . time() . "." . $ext;
            move_uploaded_file(
                $_FILES['profile_image']['tmp_name'],
                "../uploads/profile_pics/" . $imagePath
            );
        }
    }

    if (empty($errors)) {

        /* UPDATE USERS */
        $stmt = $conn->prepare("
            UPDATE users 
            SET name = ?, email = ?, profile_image_path = ?
            WHERE user_id = ?
        ");
        $stmt->execute([$name, $email, $imagePath, $user_id]);

        /* SAVE SHIPPING ADDRESS */
        $stmt = $conn->prepare("
            REPLACE INTO shipping_addresses
            (shipping_address_id, address, city, postal_code, state, country, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $shipping_id = "SA" . substr($user_id, -5);
        $stmt->execute([
            $shipping_id,
            $address,
            $city,
            $postal_code,
            $state,
            $country,
            $user_id
        ]);

        header("Location: profile.php?success=1");
        exit;
    }
}

if (isset($_GET['success'])) {
    $success = "Profile updated successfully";
}
?>

=======
>>>>>>> e52c893b7447209813902a59cef01c24f0ec8fd9
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <title>My Profile</title>
</head>
<body>

<h2>My Profile</h2>

<?php foreach ($errors as $e): ?>
    <p style="color:red"><?= $e ?></p>
<?php endforeach; ?>

<?php if ($success): ?>
    <p style="color:green"><?= $success ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <label>Profile Photo</label><br>
    <?php if (!empty($user['profile_image_path'])): ?>
        <img src="../uploads/profile_pics/<?= $user['profile_image_path'] ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="profile_image"><br><br>

    <label>Name</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>

    <label>Email</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label>Address</label><br>
    <input type="text" name="address" value="<?= $user['address'] ?? '' ?>"><br>

    <input type="text" name="city" placeholder="City" value="<?= $user['city'] ?? '' ?>"><br>
    <input type="text" name="postal_code" placeholder="Postal Code" value="<?= $user['postal_code'] ?? '' ?>"><br>
    <input type="text" name="state" placeholder="State" value="<?= $user['state'] ?? '' ?>"><br>
    <input type="text" name="country" placeholder="Country" value="<?= $user['country'] ?? '' ?>"><br><br>

    <button type="submit">Update Profile</button>

</form>

<hr>

<h3 style="color:red;">Danger Zone</h3>

<form method="post" 
      onsubmit="return confirm('Are you sure you want to deactivate your account? This action cannot be undone.')">

    <button type="submit" 
            name="deactivate_account"
            style="background:red;color:white;padding:10px;">
        Deactivate Account
    </button>

</form>

</body>
</html>
