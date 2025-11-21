<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM employees ORDER BY id ";
$result = mysqli_query($conn, $sql);

if (isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");
    header("Location:employees.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Manage Employees</h2>

<a href="addemployee.php">+ Add New Employee</a>
<a href="dashboard.php">Back to Dashboard</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Salary</th>
        <th>Phone</th>
        <th>Position</th>
        <th>Department</th>
        <th>Date of Joining</th>
        <th>Address</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>

            <td>
                <?= $row['role'] == 1 ? "Admin" : "Employee" ?>
            </td>

            <td><?= $row['salary'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['position'] ?></td>
            <td><?= $row['department'] ?></td>
            <td><?= $row['date_of_joining'] ?></td>
            <td><?= $row['address'] ?></td>

            <td>
                <?= $row['status'] == 1 ? "Active" : "Inactive" ?>
            </td>

            <td>
                <a href="editemployee.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="employees.php?id=<?= $row['id'] ?>" 
                   onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>
    
</body>
</html>

