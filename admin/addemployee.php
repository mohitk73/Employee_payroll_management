<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $date_of_joining = $_POST['date_of_joining'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    $sql = "INSERT INTO employees 
        (name, email, password, role, salary, phone, position, department, date_of_joining, address, status)
        VALUES ('$name', '$email', '$password', '$role', '$salary', '$phone', '$position', '$department', '$date_of_joining', '$address', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location:employees.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Add New Employee</h2>

<form method="POST">

    <label>Name</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="text" name="password" required><br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="0">Employee</option>
        <option value="1">Admin</option>
    </select><br><br>

    <label>Salary</label><br>
    <input type="number" name="salary" required><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Position</label><br>
    <input type="text" name="position" required><br><br>

    <label>Department</label><br>
    <input type="text" name="department" required><br><br>

    <label>Date of Joining</label><br>
    <input type="date" name="date_of_joining" required><br><br>

    <label>Address</label><br>
    <textarea name="address" rows="3" required></textarea><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select><br><br>

    <button type="submit" name="add">Add Employee</button>

</form>

<br>
<a href="employees.php">⬅ Back to Employee List</a>
