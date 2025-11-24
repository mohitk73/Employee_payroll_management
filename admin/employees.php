<?php
include('../includes/header.php');
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
<head>
      <link rel="stylesheet" type="text/css" href="../assets/css/employees.css">
</head>
<main>
<section>
    <h2>Manage Employees</h2>

<a href="addemployee.php">+ Add New Employee</a>
<a class="backdashboard" href="dashboard.php"> < Back to Dashboard</a>
<a href="addsalary.php">+ Add Salary Structure</a>
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
    <span class="status-badge <?= $row['status'] == 1 ? 'active' : 'inactive' ?>">
        <?= $row['status'] == 1 ? "Active" : "Inactive" ?>
    </span>
</td>


            <td>
                <a href="editemployee.php?id=<?= $row['id'] ?>">Edit</a>
                <a class="delete"  href="employees.php?id=<?= $row['id'] ?>" 
                   onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>
</section>
</main>


