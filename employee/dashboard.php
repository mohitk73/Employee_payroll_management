<?php
include "../config/auth.php";
requireRole([0]);  
include "../config/db.php";
$user_id=$_SESSION['user_id'];

$dashboard = "SELECT p.gross_salary, p.deductions,p.net_salary,p.month,
SUM(CASE WHEN a.status = 1 THEN 1 END) AS presentdays,
SUM(CASE WHEN a.status = 0 THEN 1 END) AS absentdays
FROM payroll p
JOIN attendance a ON p.employee_id = a.employee_id
WHERE p.employee_id = '$user_id'
  AND p.month = (SELECT MAX(month) FROM payroll WHERE employee_id='$user_id')
GROUP BY p.month, p.gross_salary, p.deductions, p.net_salary
";
$detailcheck=mysqli_query($conn,$dashboard);
$dasboarddetails=mysqli_fetch_assoc($detailcheck);
$attendancestatus="SELECT status FROM attendance WHERE employee_id='$user_id' AND date=CURDATE()";
$statuscheck=mysqli_query($conn,$attendancestatus);
$attendancestatus=mysqli_fetch_assoc($statuscheck);
if($attendancestatus){
    $dasboarddetails['status'] = ($attendancestatus['status'] == 1) ? "Present" : "Absent";
} else {
    $dasboarddetails['status'] = "Not Marked";
}
$totalworkingdays = $dasboarddetails['presentdays'] + $dasboarddetails['absentdays'];
$attendancepercentage=($totalworkingdays >0) ?round($dasboarddetails['presentdays'] / $totalworkingdays * 100, 2) : 0;
$pay_date = date("Y-m-06", strtotime($dasboarddetails['month'] . " +1 month"));

include('../includes/header.php');
?>
<head>
    <link rel=stylesheet href="../assets/css/empdashboard.css">
</head>
<main>
    <section>
    <div class="welcome">
        <h3>Welcome, <?php echo $_SESSION['name']; ?>!</h3>
    </div>

<div class="last">
    <h4>Salary Summary <span>(Last Month : <?= date("F Y", strtotime($dasboarddetails['month'])) ?>)</span></h4>
</div>
    <div class="salary-summary">
        <div class="salarydetails">
            <div>
                <h4>Gross Earnings</h4>
                <p><?= number_format($dasboarddetails['gross_salary'], 2) ?></p>
            </div><hr>
            <div>
                <h4>Deductions</h4>
                <p><?= number_format($dasboarddetails['deductions'], 2) ?></p>
            </div><hr>
            <div>
                <h4>Net Pay</h4>
                <p><?= number_format($dasboarddetails['net_salary'], 2) ?></p>
            </div><hr>
            <div>
                <h4>Pay Date</h4>
                <p><?= $pay_date ?></p>
            </div>
        </div>
        <div class="latest">
            <h4>View Recent Payslip <span>(<?= date("F Y", strtotime($dasboarddetails['month'])) ?>)</span></h4>
            <a href="emppayslip.php?month=<?= date("Y-m", strtotime($dasboarddetails['month'])) ?>">View Payslip</a>

        </div>
</div>

    <div class="attendance"><h4>Attendance Summary</h4></div>
    <div class="attendance-summary">
        <div class="attendancedetails">
            <div>
                <h4>Total Working Days</h4>
                <p><?= $totalworkingdays ?></p>
            </div><hr>
            <div>
                <h4>Total Present Days</h4>
                <p><?= $dasboarddetails['presentdays'] ?></p>
            </div><hr>
            <div>
                <h4>Total Absent Days</h4>
                <p><?= $dasboarddetails['absentdays'] ?></p>
            </div><hr>
            <div>
                <h4>Attendance Percentage</h4>
                <p><?= $attendancepercentage ?>%</p>
            </div>
            <div class="attreports">
                <a href="../employee/attendance.php">View Details</a>
                <p></p>
            </div>
        </div>
        <div class="attendance-status">
            <h4>Today's Attendance status</h4>
            <p class="status"><?= $dasboarddetails['status'] ?></p>
        </div>


    </div>
</section>
</main>

