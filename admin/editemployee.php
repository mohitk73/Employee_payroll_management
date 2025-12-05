<?php
include "../config/db.php";
include "../config/auth.php"; 
requireRole([1,2,3]);  
if (!isset($_GET['id'])) {
    header("Location:employees.php");
    exit();
}

$id = $_GET['id'];
$emp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM employees WHERE id=$id"));

if (!$emp) {
    echo "Employee not found!";
    exit();
}

if (isset($_POST['update'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role=$_POST['role'];
    $position = htmlspecialchars($_POST['position']);
    $department = htmlspecialchars($_POST['department']);
    $manager_id =$_POST['manager_id'];
    $address = htmlspecialchars($_POST['address']);
    $status = $_POST['status'];
    if (empty($name) || !preg_match("/^[A-Za-z\s]{2,50}$/", $name)) {
        $error = "Name should be between 2-50 characters and contain only letters and spaces.";
    }
     if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }
   
    if (empty($phone) || !preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Phone number must be exactly 10 digits.";
    }
    if (empty($position)) {
        $error = "Position is required.";
    }
    if (empty($department)) {
        $error = "Department is required.";
    }
    
    if (empty($address) || !preg_match("/^[A-Za-z0-9\s,.-]{5,200}$/", $address)) {
        $error = "Address is required and should be between 5-200 characters.";
    }
    if (empty($manager_id)) {
        $manager_id = NULL;  
    }
   if (!isset($error)) {
    $sql = "UPDATE employees SET name='$name',email='$email',
                phone='$phone',
                role='$role',
                position='$position',
                department='$department',
                manager_id=" . ($manager_id !== NULL ? $manager_id : "NULL") . ",
                address='$address',
                status='$status'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: employees.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
}
}
include('../includes/header.php');
?>

<head>
    <title>Edit Employee</title>
    <link rel="stylesheet" href="../assets/css/editemployee.css">
</head>
<main>
<section>
<form method="POST">

<h3>Edit Employee</h3>
<hr>
 <?php if(!empty($error)) {?>
            <p style="color: red;margin-bottom:5px;"><?= $error ?></p>
            <?php }?>

    Name:<br>
    <input type="text" name="name" value="<?= $emp['name'] ?>" pattern="[A-Za-z\s]{2,50}" required><br><br>

    Email:<br>
    <input type="email" name="email" value="<?= $emp['email'] ?>" required><br><br>

    Phone:<br>
    <input type="text" name="phone" value="<?= $emp['phone'] ?>" maxlength="10" pattern="[0-9]{10}" required><br><br>

     Role:<br>
    <select name="role">
    <option value="0">Employee</option>
    <option value="1">Admin</option>
    <option value="2">HR</option>
    <option value="3">Manager</option>
</select><br><br>

    Position:<br>
    <input type="text" name="position" value="<?= $emp['position'] ?>" required><br><br>

    Department:<br>
    <!-- <input type="text" name="department" value="<?= $emp['department'] ?>" required><br><br> -->
        <select name="department" required>
            <option value="IT" <?= $emp['department'] == 'IT' ? 'selected' : '' ?>>IT</option>
            <option value="HR" <?= $emp['department'] == 'HR' ? 'selected' : '' ?>>HR</option>
            <option value="Sales" <?= $emp['department'] == 'Sales' ? 'selected' : '' ?>>Sales</option>
        </select><br><br>
        Assign Manager:
<select name="manager_id">
        <option value="">No Manager</option>
        <?php
        $managers = mysqli_query($conn, "SELECT id, name FROM employees WHERE role = 3"); 
        while ($manager = mysqli_fetch_assoc($managers)) {
            echo "<option value='" . $manager['id'] . "'>" . $manager['name'] . "</option>";
        }
        ?>
    </select><br><br>
    Address:<br>
    <textarea name="address" required><?= $emp['address'] ?></textarea><br><br>

    Status:<br>
    <select name="status">
        <option value="1" <?= $emp['status'] == 1 ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= $emp['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
    </select><br><br>

    
    <button type="submit" name="update">Update Employee</button>
    <a class="back" href="employees.php">⬅ Back to Employee List</a>
</form>


</section>
</main>

