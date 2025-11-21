<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admindashboard.css">
</head>
<body>
<div class="sidebar">
    <h1>Employee Payroll</h1>
     <a href="employees.php">Dashboard</a>
    <a href="employees.php">Employees</a>
    <a href="attendance.php">Leave and Attendance</a>
    <a href="payroll.php">Payroll</a>
     <a href="employees.php">Reports</a>
      <a href="employees.php">Settings</a>
       <a href="employees.php">Contact Support</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>
<main>
<header>
    <div>
    <div>
        <input type="text" placeholder="Search Employee">
    </div>
    <div>
        <h2>Welcome <?= $_SESSION['name'] . "!" ?></h2>
    </div>
    </div>
</header>

</main>


<!-- <div class="main-content">
    
    <p>Welcome To payroll Management platform</p>
</div> -->

</body>
</html>
