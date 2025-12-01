
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <h1><i class="fas fa-file-invoice-dollar"></i>Mind2Web Payroll</h1>
     <?php if($_SESSION['role'] == 1) { ?>
    <a href="../admin/dashboard.php">Dashboard</a>
    <a href="../admin/employees.php">Employees</a>
    <a href="../admin/attendance.php">Leave and Attendance</a>
    <a href="../admin/salary_structure.php">Salary Structure</a>
    <a href="../admin/payroll.php">Payroll</a>
     <a href="../admin/payslips.php">Payslips</a>
       <a href="../admin/employee_queries.php">Employee Queries</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>

     <?php if($_SESSION['role'] == 2) { ?>
    <a href="../hr/hrdashboard.php">Dashboard</a>
    <a href="../admin/employees.php">Employees</a>
    <a href="../admin/attendance.php">Leave and Attendance</a>
    <a href="../admin/salary_structure.php">Salary Structure</a>
    <a href="../admin/payroll.php">Payroll</a>
     <a href="../admin/payslip.php">Payslips</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>

    <?php if($_SESSION['role'] == 3) { ?>
    <a href="../manager/managerdashboard.php">Dashboard</a>
    <a href="../admin/employees.php">Employees List</a>
    <a href="../admin/attendance.php">Attendance</a>
     <a href="../manager/managerpayslip.php">Payslips</a>
        <a href="../contactsupport.php">Contact Support</a>
    <a href="../logout.php" class="logout">Logout</a>
    <?php } ?>

    <?php if($_SESSION['role'] == 0) { ?>
    <a href="../employee/dashboard.php">Dashboard</a>
    <a href="../employee/attendance.php">Attendance</a>
     <a href="../employee/emppayslip.php">Payslips</a>
       <a href="../contactsupport.php">Contact Support</a>
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
                <li><a href="../admin/profile.php">View Profile</a></li>
                  <li><a href="../contactsupport.php">Get Help</a></li>
                    <li><a href="../logout.php">Logout</a></li>
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
