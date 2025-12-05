<?php
$activepage = basename($_SERVER['PHP_SELF']);

?>
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
        <?php if ($_SESSION['role'] == 1) { ?>
            <a href="../admin/dashboard.php" class="<?= $activepage == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="../admin/employees.php" class="<?= $activepage == 'employees.php' ? 'active' : '' ?>">Employees</a>
            <a href="../admin/attendance.php" class="<?= $activepage == 'attendance.php' ? 'active' : '' ?>">Leave and Attendance</a>
            <a href="../admin/salary_structure.php" class="<?= $activepage == 'salary_structure.php' ? 'active' : '' ?>">Salary Structure</a>
            <a href="../admin/payroll.php" class="<?= $activepage == 'payroll.php' ? 'active' : '' ?>">Payroll</a>
            <a href="../admin/payslips.php" class="<?= $activepage == 'payslips.php' ? 'active' : '' ?>">Payslips</a>
            <a href="../admin/empqueries.php" class="<?= $activepage == 'empqueries.php' ? 'active' : '' ?>">Employee Queries</a>
            <a href="../logout.php" class="logout">Logout</a>
        <?php } ?>

        <?php if ($_SESSION['role'] == 2) { ?>
            <a href="../hr/hrdashboard.php" class="<?= $activepage == 'hrdashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="../admin/employees.php" class="<?= $activepage == 'employees.php' ? 'active' : '' ?>">Employees</a>
            <a href="../admin/attendance.php" class="<?= $activepage == 'attendance.php' ? 'active' : '' ?>">Leave and Attendance</a>
            <a href="../admin/salary_structure.php" class="<?= $activepage == 'salary_structure.php' ? 'active' : '' ?>">Salary Structure</a>
            <a href="../admin/payroll.php" class="<?= $activepage == 'payroll.php' ? 'active' : '' ?>">Payroll</a>
            <a href="../admin/payslips.php" >Payslips</a>
            <a href="../logout.php" class="logout">Logout</a>
        <?php } ?>

        <?php if ($_SESSION['role'] == 3) { ?>
            <a href="../manager/managerdashboard.php" class="<?= $activepage == 'managerdashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="../manager/manageremployees.php" class="<?= $activepage == 'manageremployees.php' ? 'active' : '' ?>">Employees List</a>
            <a href="../manager/managerattendance.php" class="<?= $activepage == 'managerattendance.php' ? 'active' : '' ?>">Attendance</a>
            <a href="../manager/managerpayslip.php" class="<?= $activepage == 'managerpayslip.php' ? 'active' : '' ?>">Payslips</a>
            <a href="../contactsupport.php" class="<?= $activepage == 'contactsupport.php' ? 'active' : '' ?>">Contact Support</a>
            <a href="../logout.php" class="logout" class="<?= $activepage == 'hrdashboard.php' ? 'active' : '' ?>">Logout</a>
        <?php } ?>

        <?php if ($_SESSION['role'] == 0) { ?>
            <a href="../employee/dashboard.php" class="<?= $activepage == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="../employee/attendance.php" class="<?= $activepage == 'attendance.php' ? 'active' : '' ?>">Attendance</a>
            <a href="../employee/emppayslip.php" class="<?= $activepage == 'emppayslip.php' ? 'active' : '' ?>">Payslips</a>
            <a href="../contactsupport.php" class="<?= $activepage == 'contactsupport.php' ? 'active' : '' ?>">Contact Support</a>
            <div>
                <a href="../logout.php" class="logout">Logout</a>
            </div>
        <?php } ?>
    </div>
    <main>
        <header>

            <div>
               
            </div>
            <div class="nav">
                <h2>Welcome <?= $_SESSION['name'] . " " . "!" ?></h2>
               
                <i onclick="menu(event)" class="fas fa-bars"></i>

                <div class="profile">
                    <nav>
                        <ul>
                            <li><a href="../admin/profile.php">View Profile</a></li>
                            <?php if($_SESSION['role']==1){ ?><li><a href="../admin/empqueries.php">View Queries</a></li><?php } else{?>
                                <li><a href="../contactsupport.php">Get Help</a></li><?php }?>
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