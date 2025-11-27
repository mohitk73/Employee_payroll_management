<?php
include "../config/auth.php"; 
requireRole([1,2,3]);
include "../config/db.php";
include '../includes/header.php'; 
$sql = "SELECT * FROM employees ORDER BY id ";
$result = mysqli_query($conn, $sql);

if (isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");
    header("Location:employees.php");
    exit();
}
$roles = [
    0 => "Employee",
    1 => "Admin",
    2 => "HR",
    3 => "Manager"
];
?>
<head>
      <link rel="stylesheet" type="text/css" href="../assets/css/employees.css">
</head>
<main>
<section>
    <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
    <h2>Manage Employees</h2>
<?php } else { ?>
    <h2>Employees List</h2>
<?php } ?>

<?php if($_SESSION['role']==1 || $_SESSION['role']==2) {?>
<a href="addemployee.php">+ Add New Employee</a>
<a href="addsalary.php">+ Add Salary Structure</a><?php }?>
<a class="backdashboard" href="dashboard.php"> < Back to Dashboard</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <?php if($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
        <th>Role</th>
        <th>Salary</th>
    <?php } ?>
    <th>Phone</th>
    <th>Position</th>
    <th>Department</th>
    <th>Date of Joining</th>
    <th>Address</th>
    <th>Status</th>
    <?php if($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
        <th>Actions</th>
    <?php } ?>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name'] )?></td>
    <td><?= $row['email'] ?></td>

    <?php if($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
        <td><?= $roles[$row['role']] ?? "Unknown"; ?></td>
        <td><?= $row['salary'] ?></td>
    <?php } ?>

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

    <?php if($_SESSION['role']==1 || $_SESSION['role']==2) { ?>
        <td>
            <a href="editemployee.php?id=<?= $row['id'] ?>">Edit</a>
            <a class="delete" href="employees.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    <?php } ?>
</tr>
<?php } ?>
</table>
</section>
</main>

