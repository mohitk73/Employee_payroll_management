<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admindashboard.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <h1><i class="fas fa-file-invoice-dollar"></i>Mind2Web Payroll</h1>
     <?php if($_SESSION['role'] == 1) { ?>
    <a href="employees.php">Dashboard</a>
    <a href="employees.php">Employees</a>
    <a href="attendance.php">Leave and Attendance</a>
    <a href="salary_structure.php">Salary Structure</a>
    <a href="payroll.php">Payroll</a>
     <a href="employees.php">Reports</a>
     <a href="employees.php">Payslips</a>
      <a href="employees.php">Settings</a>
       <a href="employees.php">Contact Support</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>

     <?php if($_SESSION['role'] == 2) { ?>
    <a href="employees.php">Dashboard</a>
    <a href="employees.php">Employees</a>
    <a href="attendance.php">Leave and Attendance</a>
    <a href="salary_structure.php">Salary Structure</a>
    <a href="payroll.php">Payroll</a>
     <a href="employees.php">Payslips</a>
      <a href="employees.php">Settings</a>
       <a href="employees.php">Contact Support</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>

    <?php if($_SESSION['role'] == 3) { ?>
    <a href="employees.php">Dashboard</a>
    <a href="../admin/employees.php">Employees List</a>
    <a href="../admin/attendance.php">Attendance</a>
     <a href="employees.php">Payslips</a>
      <a href="employees.php">Settings</a>
       <a href="employees.php">Contact Support</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>

    <?php if($_SESSION['role'] == 0) { ?>
    <a href="../employee/dashboard.php">Dashboard</a>
    <a href="../employee/attendance.php">Attendance</a>
     <a href="employees.php">Payslips</a>
       <a href="employees.php">Contact Support</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>
</div>
<main>
<header>
   
    <div>
        <input type="text" placeholder="Search Employee">
    </div>
    <div class="nav">
        <h2>Welcome <?= $_SESSION['name'] . " " ."!" ?></h2>
        <i class="fas fa-bell"></i>
        <i class="fas fa-cog"></i>
        <i onclick="menu(event)" class="fas fa-bars"></i>

        <div class="profile">
        <nav>
            <ul>
                <li><a href="">View Profile</a></li>
                  <li><a href="">Get Help</a></li>
                    <li><a href="">Logout</a></li>
            </ul>
        </nav>

    </div>
    </div>
    
    
</header>

</main>
<script>
function menu(event) {
    const profile = document.querySelector('.profile');

    if (profile.style.display === "block") {
        profile.style.display = "none";
    } else {
        profile.style.display = "block";
    }
}
</script>


</body>
</html>
