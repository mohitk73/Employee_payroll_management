<?php
include '../config/auth.php';
requireRole([1]);
include '../config/db.php';
include('../includes/header.php');

$role = [
    1 => 'Admin'
];
 $filter_month = $_POST['month'] ?? date('Y-m', strtotime("first day of last month"));   
list($year, $month_num) = explode('-', $filter_month);
$month_date  = $filter_month . '-01';
$month_start = $month_date;
$month_end   = date("Y-m-t", strtotime($month_start));
 $payroll = "SELECT SUM(net_salary) AS totalsalary,COUNT(*) AS totalemployee FROM payroll WHERE month BETWEEN '$month_start' AND '$month_end'";
$payrollcheck = mysqli_query($conn, $payroll);
$payrollresult=mysqli_fetch_assoc($payrollcheck); 

$deduction="SELECT SUM(deductions) AS totaldeduction FROM payroll where month BETWEEN '$month_start' AND '$month_end'";
$deductioncheck=mysqli_query($conn,$deduction);
$deductionresult=mysqli_fetch_assoc($deductioncheck);

$activeemployee="SELECT COUNT(*) AS totalactive FROM employees WHERE status=1";
$activeemployeecheck=mysqli_query($conn,$activeemployee);
$activeemployeeresult=mysqli_fetch_assoc($activeemployeecheck);

$presenttoday="SELECT COUNT(*) AS totalpresent FROM attendance WHERE date=CURDATE() AND status=1";
$presenttodaycheck=mysqli_query($conn,$presenttoday);
$presenttodayresult=mysqli_fetch_assoc($presenttodaycheck);

$absenttoday="SELECT COUNT(*) AS totalabsent FROM attendance WHERE date=CURDATE() AND status=0";
$absenttodaycheck=mysqli_query($conn,$absenttoday);
$absenttodayresult=mysqli_fetch_assoc($absenttodaycheck);
?>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admindashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <main>
        <section>
            <div class="welcome">
                <h3>Welcome, <?php echo $_SESSION['name']; ?>!</h3>
                <h4>Role: <?= $role[$_SESSION['role']] ?></h4>
            </div>
            <div class="payrun">
                <div class="processpayrun">
                    <h3>Process Pay Run for <span><?= date('F Y', strtotime($month_start)) ?></span></h3>
                    <div class="payrundetails">
                        <div class="netpay">

                            <div>
                                <h4>Employees Net Pay</h4>
                                <p><?= number_format($payrollresult['totalsalary'], 2) ?></p>
                            </div><hr>
                            <div>
                                <h4>Payment Date</h4>
                                <p><?= date('d/m/Y', strtotime($month_end)) ?></p>
                            </div><hr>
                            <div>
                                <h4>No. of Employees</h4>
                                <p><?= $payrollresult['totalemployee'] ?></p>
                            </div><hr>
                            <div>
                                <a href="/admin/payroll.php">View Details</a>
                                <p></p>
                            </div>
                        </div>
                        <div class="pay">
                            <p>Pay your employees on <span><?= date('d/m/Y', strtotime($month_end)) ?></span>. Record it here</p>
                        </div>
                    </div>
                    <div class="deduction">
                        <div class="deduction-summary">
                        <div>
                            <h4>Deduction Summary</h4>
                        <p>Previous Month <span>(<?= date('F Y', strtotime($month_start)) ?>)</span></p>
                        </div>
                        <div class="tds">
                            <div>
                                <h4>Government tax</h4>
                                <p>200</p>
                                <a href="payroll.php">View Details</a>
                            </div><hr>
                            <div>
                                <h4>PF</h4>
                                <p>1000</p>
                                <a href="payroll.php">View Details</a>
                            </div><hr>
                             <div>
                                <h4>Total Deduction</h4>
                                <p><?= number_format($deductionresult['totaldeduction'], 2) ?></p>
                                <a href="payroll.php">View Details</a>
                            </div>

                        </div>
                        </div>

                        <div class="employee-summary">
                            <div><h4>Employees Summary</h4></div>
                            <div>
                            <h4>Active Employees</h4>
                        <p><?= $activeemployeeresult['totalactive'] ?></p>
                        <a href="employees.php">View Employees</a>
                    </div>

                        </div>
                        
                    </div>
                </div>
                
                <div class="todotask">
                <h3>To do tasks</h3>
                <div class="pending">
                    <div>
                        <h4>Mark Employee Attendance</h4>
                        <p>Pending</p>
                        <a href="../admin/attendance.php">Mark Attendance</a>
                    </div>
                     <div>
                        <h4>Salary Revision</h4>
                        <p>Pending</p>
                        <a href="../admin/addsalary.php">Update</a>
                    </div>
                    <div>
                        <h4>Leave Requests</h4>
                        <p>Upcoming Soon</p>
                        <a href="../admin/leave.php">Approve Leaves</a>
                    </div>
                </div>
            </div>
            </div>
            </div>

            <div class="attendance">
                <h3>Attendance Summary</h3>
                <div class="attendance-summary">
                    <div>
                        <h4>Total Employees</h4>
                        <p><?= $activeemployeeresult['totalactive'] ?></p>
                    </div><hr>
                    <div>
                        <h4>Present Today</h4>
                        <p><?= $presenttodayresult['totalpresent'] ?></p>
                    </div><hr>
                    <div>
                        <h4>Absent Today</h4>
                        <p><?= $absenttodayresult['totalabsent'] ?></p>
                    </div><hr>
                    <div>
                        <h4>On Leave</h4>
                        <p>1</p>
                    </div><hr>
                    <div>
                        <a href="../admin/attendance.php">View Details</a>
                        <p></p>
                    </div>

                </div>

            </div>
            <div>

            </div>

        </section>
    </main>
</body>

</html>