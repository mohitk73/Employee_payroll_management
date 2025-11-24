<?php
/* session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1){
    header("Location: login.php");
    exit;
} */
include('../includes/header.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admindashboard.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <main><?php
$totalEmployees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees"))['total'];
$netPay = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(net_pay) AS total FROM payroll WHERE MONTH(pay_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)"
))['total'];

$pendingLeave = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM attendance WHERE status='pending'"
))['total'];

$nextPayday = date("d M Y", strtotime("last day of this month"));

$recent = mysqli_query($conn, "SELECT * FROM employees ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admindashboard.css">
</head>

<body>

<?php include "sidebar.php"; ?>
<?php include "header.php"; ?>

<main>

<h1>Dashboard Overview</h1>

<!-- KEY METRICS -->
<div class="metrics">

    <div class="card">
        <h3>Total Employees</h3>
        <p><?= $totalEmployees ?></p>
    </div>

    <div class="card">
        <h3>Total Net Pay (Last Month)</h3>
        <p>₹ <?= $netPay ? $netPay : 0 ?></p>
    </div>

    <div class="card">
        <h3>Pending Leave Requests</h3>
        <p><?= $pendingLeave ?></p>
    </div>

    <div class="card">
        <h3>Next Pay Date</h3>
        <p><?= $nextPayday ?></p>
    </div>

</div>

<!-- RECENT EMPLOYEES -->
<h2>Recently Added Employees</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Position</th>
        <th>Joined</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($recent)) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['position'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
    <?php } ?>
</table>

<!-- QUICK ACTION -->
<h2>Quick Actions</h2>
<div class="actions">
    <a class="btn" href="add_employee.php">+ Add New Employee</a>
    <a class="btn" href="payroll.php">Run Payroll</a>
    <a class="btn" href="attendance.php">Manage Attendance</a>
    <a class="btn" href="reports.php">Generate Reports</a>
</div>

</main>

</body>
</html>
</main>
</body>
</html>
