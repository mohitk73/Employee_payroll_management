<?php
include "../config/auth.php";
requireRole([3]);
include "../config/db.php";
$user_id = $_SESSION['user_id'];

$dashboard = "SELECT p.gross_salary, p.deductions,p.net_salary,p.month,p.present_days,p.absent_days,
SUM(CASE WHEN a.status = 1 THEN 1 END) AS presentdays,
SUM(CASE WHEN a.status = 0 THEN 1 END) AS absentdays
FROM payroll p
JOIN attendance a ON p.employee_id = a.employee_id
WHERE p.employee_id = '$user_id'
  AND p.month = (SELECT MAX(month) FROM payroll WHERE employee_id='$user_id')
GROUP BY p.month, p.gross_salary, p.deductions, p.net_salary,p.present_days,p.absent_days

LIMIT 3
";
$detailcheck = mysqli_query($conn, $dashboard);
$dashboarddetails = mysqli_fetch_assoc($detailcheck);
$attendancestatus = "SELECT a.* FROM attendance a
JOIN employees e ON a.employee_id = e.id
 WHERE employee_id='$user_id' AND date=CURDATE()";
$statuscheck = mysqli_query($conn, $attendancestatus);
$attendancestatus = mysqli_fetch_assoc($statuscheck);
if ($attendancestatus) {
    $dashboarddetails['status'] = ($attendancestatus['status'] == 1) ? "Present" : "Absent";
} else {
    $dashboarddetails['status'] = "Not Marked";
}
$totalworking = $dashboarddetails['present_days'] + $dashboarddetails['absent_days'];
$totalworkingdays = $dashboarddetails['presentdays'] + $dashboarddetails['absentdays'];
$attendancepercentage = ($totalworkingdays > 0) ? round($dashboarddetails['presentdays'] / $totalworkingdays * 100, 2) : 0;
$pay_date = date("Y-m-06", strtotime($dashboarddetails['month'] . " +1 month"));
$nextpay_date = date("Y-m-06", strtotime($dashboarddetails['month'] . " +2 month"));

$TotalEmployees = "SELECT COUNT(*) AS totalemp FROM employees WHERE manager_id='$user_id";
$totalempcheck = mysqli_query($conn, $TotalEmployees);
$totalresult = mysqli_fetch_assoc($totalempcheck);
$presenttoday = "SELECT COUNT(*) AS present FROM attendance 
                    WHERE date = CURDATE() AND status = 1";
$presentcheck = mysqli_query($conn, $presenttoday);
$presentresult = mysqli_fetch_assoc($presentcheck);
$absenttoday = "SELECT COUNT(*) AS absent 
                   FROM attendance 
                   WHERE date = CURDATE() AND status = 0 AND ";
$absentcheck = mysqli_query($conn, $absenttoday);
$absentresult = mysqli_fetch_assoc($absentcheck);

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

        <div>

            <div class="attendance">
                <h4>Employees Attendance Summary</h4>
            </div>
            <div class="attendance-summary">
                <div class="attendancedetails">
                    <div>
                        <h4>Total employees</h4>
                        <p><?= $totalresult['totalemp'] ?> </p>
                    </div>
                    <hr>
                    <div>
                        <h4>Present Today</h4>
                        <p><?= $presentresult['present'] ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Absent Today</h4>
                        <p><?= $absentresult['absent'] ?></p>
                    </div>
                

                    <div class="attreports">
                        <a href="../manager/managerattendance.php">View Details</a>
                        <p></p>
                    </div>
                </div>
                 
            </div>

            <div class="overview">
                <div>
                    <h4>Net Pay (Last Month)</h4>
                    <p><?= number_format($dashboarddetails['net_salary'], 2) ?></p>
                </div>
                <div>
                    <h4>Attendance Percentage</h4>
                    <p><?= $attendancepercentage ?>%</p>
                </div>
                <div>
                    <h4>Next Salary Date</h4>
                    <p><?= $nextpay_date ?></p>
                </div>
            </div>

            <div class="last">
                <h4>Your Salary Summary <span>(Last Month : <?= date("F Y", strtotime($dashboarddetails['month'])) ?>)</span></h4>
            </div>
            <div class="salary-summary">
                <div class="salarydetails">
                    <div>
                        <h4>Gross Earnings</h4>
                        <p><?= number_format($dashboarddetails['gross_salary'], 2) ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Deductions</h4>
                        <p><?= number_format($dashboarddetails['deductions'], 2) ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Net Pay</h4>
                        <p><?= number_format($dashboarddetails['net_salary'], 2) ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Pay Date</h4>
                        <p><?= $pay_date ?></p>
                    </div>
                </div>
                <div class="latest">
                    <h4>View Recent Payslip </h4>
                    <a href="../manager/managerpayslip.php/?month=<?= date("Y-m", strtotime($dashboarddetails['month'])) ?>">View Payslip</a>

                </div>
            </div>

            <div class="attendance">
                <h4>Your Attendance Summary</h4>
            </div>
            <div class="attendance-summary">
                <div class="attendancedetails">
                    <div>
                        <h4>Total Working Days</h4>
                        <p><?= $totalworkingdays ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Total Present Days</h4>
                        <p><?= $dashboarddetails['presentdays'] ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Total Absent Days</h4>
                        <p><?= $dashboarddetails['absentdays'] ?></p>
                    </div>
                    <hr>
                    <div>
                        <h4>Attendance Percentage</h4>
                        <p><?= $attendancepercentage ?>%</p>
                    </div>
                    <div class="attreports">
                        <a href="../admin/attendance.php">View Details</a>
                        <p></p>
                    </div>
                </div>
                <div class="attendance-status">
                    <h4> Your Today's Attendance Status</h4>
                    <p class="status"><?= $dashboarddetails['status'] ?></p>
                </div>
            </div>

            <div class="attendance-trend">
                <h4>Attendance Trend (Last Month)</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Working Days</th>
                            <th>Present Days</th>
                            <th>Absent Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= date("F Y", strtotime($dashboarddetails['month'])) ?></td>
                            <td><?= $totalworking ?></td>
                            <td><?= $dashboarddetails['present_days'] ?></td>
                            <td><?= $dashboarddetails['absent_days'] ?></td>
                        </tr>


                    </tbody>
                </table>

            </div>

            <div class="notice">
                <h4>Announcements</h4>
            </div>
            <div class="notification">
                 <h4>Upcoming Festivals</h4>
                <div class="festival">
                    <div class="festivelist">
                        <div>
                            <h5>Christmas</h5>
                            <p>25th Dec 2025</p>
                        </div>
                        <div>
                            <h5>New Year</h5>
                            <p>1st Jan 2026</p>
                        </div>
                        <div>
                            <h5>Republic Day</h5>
                            <p>26th Jan 2026</p>
                        </div>
                    </div>
                    <div class="next-salary">
                        <h5>Next Salary Date</h5>
                        <p><?= $nextpay_date ?></p>
                    </div>
                </div>
            </div>
    </section>
</main>
