<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon.jpg">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <header>
        <h1><a href="/">TECH CAFE PRO</a></h1>
    </header>
    <h2>Profile</h2>

    <main>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/logout.php">Logout</button>
        </div>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/update_profile_picture.php">Update profile pic</button>
        </div>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/order_history.php">Order history</button>
        </div>
        <div style = "text-align:center; margin:50px auto;">
        <button data-get="/page/home.php">Back</button>
        </div>
    </main>

</body>
</html>

<?php
require '../_base.php';

        


include '../_foot.php';

//////////////////////////////////////////////////////////////////////////////////////////////
/*✅ Features Included in This Profile Page

View current profile info

Edit personal info: name, age, gender, phone, email

Upload or change profile picture

Edit address (stored in Shipping_Address table)

Server-side validation: email format, phone number, age range, gender

File upload validation: only jpg, png, gif allowed

Automatic update in database using PDO

Security: only logged-in users can access */


<?php
session_start();
include '../config/database.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth.php?action=login");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM User u 
                        LEFT JOIN Shipping_Address a ON u.id = a.userId
                        WHERE u.id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if($_SERVER['REQUEST_METHOD']=='POST'){
    // Gather inputs
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $postalCode = trim($_POST['postalCode']);
    $state = trim($_POST['state']);
    $country = trim($_POST['country']);

    // Validation
    if(strlen($name)<2) $errors[] = "Name must be at least 2 characters.";
    if($age<1 || $age>120) $errors[] = "Age must be valid.";
    if(!in_array($gender, ['Male','Female','Other'])) $errors[] = "Invalid gender.";
    if(!preg_match("/^[0-9]{10,15}$/",$phone)) $errors[] = "Invalid phone number.";
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    // Profile picture upload
    if(isset($_FILES['profileImage']) && $_FILES['profileImage']['error']==0){
        $allowed = ['jpg','jpeg','png','gif'];
        $fileName = $_FILES['profileImage']['name'];
        $fileTmp = $_FILES['profileImage']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = 'profile_'.$user_id.'_'.time().'.'.$fileExt;

        if(!in_array($fileExt,$allowed)){
            $errors[] = "Only JPG, PNG, GIF files are allowed.";
        } else {
            move_uploaded_file($fileTmp, "../uploads/profile_pics/".$newFileName);
        }
    }

    if(empty($errors)){
        // Update User table
        $stmt = $conn->prepare("UPDATE User SET name=?, age=?, gender=?, phone=?, email=? ".(isset($newFileName)?", profileImagePath=?":"")." WHERE id=?");
        $params = [$name,$age,$gender,$phone,$email];
        if(isset($newFileName)) $params[] = $newFileName;
        $params[] = $user_id;
        $stmt->execute($params);

        // Update Shipping Address
        $stmt = $conn->prepare("REPLACE INTO Shipping_Address (userId,address,city,postalCode,state,country) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$user_id,$address,$city,$postalCode,$state,$country]);

        $success = "Profile updated successfully!";
        // Refresh user data
        header("Location: profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tech Cafe Pro - Profile</title>
</head>
<body>
<h2>My Profile</h2>

<?php foreach($errors as $err) echo "<p style='color:red'>$err</p>"; ?>
<?php if($success) echo "<p style='color:green'>$success</p>"; ?>

<form method="post" enctype="multipart/form-data">
    <label>Profile Picture:</label><br>
    <?php if($user['profileImagePath']): ?>
        <img src="../uploads/profile_pics/<?php echo $user['profileImagePath']; ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="profileImage"><br><br>

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>

    <label>Age:</label><br>
    <input type="number" name="age" value="<?php echo $user['age'] ?? ''; ?>" required><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <option value="">--Select--</option>
        <option value="Male" <?php if($user['gender']=='Male') echo 'selected';?>>Male</option>
        <option value="Female" <?php if($user['gender']=='Female') echo 'selected';?>>Female</option>
        <option value="Other" <?php if($user['gender']=='Other') echo 'selected';?>>Other</option>
    </select><br>

    <label>Phone Number:</label><br>
    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

    <label>Address:</label><br>
    <input type="text" name="address" value="<?php echo $user['address'] ?? ''; ?>" required><br>
    <input type="text" name="city" placeholder="City" value="<?php echo $user['city'] ?? ''; ?>"><br>
    <input type="text" name="postalCode" placeholder="Postal Code" value="<?php echo $user['postalCode'] ?? ''; ?>"><br>
    <input type="text" name="state" placeholder="State" value="<?php echo $user['state'] ?? ''; ?>"><br>
    <input type="text" name="country" placeholder="Country" value="<?php echo $user['country'] ?? ''; ?>"><br><br>

    <button type="submit">Update Profile</button>
</form>

</body>
</html>
