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

    $sql = "UPDATE employees SET name='$name',email='$email',
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
include('../includes/header.php');
?>

<head>
    <title>Edit Employee</title>
    <style>
        section{
    margin-left: 250px; 
    padding: 30px;
    font-family: Arial, sans-serif;
    display: grid;
    max-width: 800px;
    margin: 10px auto;
    
}

h3{
    font-size: 22px;
    margin-bottom: 5px;
    color: #2c3e50;
}
hr{
    margin-bottom: 10px;
    border: 1px solid #ddd;
}

form {
    background: #ffffff;
    padding: 25px;
    max-width: 800px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

form input,
form textarea,
form select {
    width: 100%;
    padding: 10px;
    font-size: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-top: 5px;
}

form textarea {
    height: 40px;
    resize: none;
}

form button {
    background: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    float: right;
}

form button:hover {
    background: #0056b3;
}

.back  {
    background-color: #444;
     
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    float: right;
    font-size: 15px;
    text-decoration: none;
    margin-right: 5px;
}

.back :hover {
    color: #000;
    
}

form label {
    font-weight: bold;
    display: block;
    margin-top: 12px;
}

    </style>
</head>
<section>
<form method="POST">

<h3>Edit Employee</h3>
<hr>

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
    <a class="back" href="employees.php">⬅ Back to Employee List</a>
</form>


</section>

