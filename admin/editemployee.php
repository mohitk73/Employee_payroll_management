<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    $sql = "UPDATE employees SET 
                name='$name',
                email='$email',
                salary='$salary',
                phone='$phone',
                position='$position',
                department='$department',
                address='$address',
                status='$status'
            WHERE id=$id";

    mysqli_query($conn, $sql);
    header("Location:employees.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
</head>
<body>

<h2>Edit Employee</h2>

<form method="POST">

    Name:<br>
    <input type="text" name="name" value="<?= $emp['name'] ?>" required><br><br>

    Email:<br>
    <input type="email" name="email" value="<?= $emp['email'] ?>" required><br><br>

    Salary:<br>
    <input type="number" name="salary" value="<?= $emp['salary'] ?>" required><br><br>

    Phone:<br>
    <input type="text" name="phone" value="<?= $emp['phone'] ?>" required><br><br>

    Position:<br>
    <input type="text" name="position" value="<?= $emp['position'] ?>" required><br><br>

    Department:<br>
    <input type="text" name="department" value="<?= $emp['department'] ?>" required><br><br>

    Address:<br>
    <textarea name="address" required><?= $emp['address'] ?></textarea><br><br>

    Status:<br>
    <select name="status">
        <option value="1" <?= $emp['status'] == 1 ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= $emp['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
    </select><br><br>

    <button type="submit" name="update">Update Employee</button>
</form>

<br>
<a href="employees.php">⬅ Back to Employee List</a>

</body>
</html>
