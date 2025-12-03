<?php

include "../config/auth.php";
requireRole([0]);
include "../config/db.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$today = date("Y-m-d");
$sn=1;
$count = 0;
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
$attendancestatus = "SELECT a.* FROM attendance a WHERE employee_id='$user_id' AND date=CURDATE()";
$statuscheck = mysqli_query($conn, $attendancestatus);
$attendancestatus = mysqli_fetch_assoc($statuscheck);

$totalworking = $dashboarddetails['present_days'] + $dashboarddetails['absent_days'];
$totalworkingdays = $dashboarddetails['presentdays'] + $dashboarddetails['absentdays'];
$attendancepercentage = ($totalworkingdays > 0) ? round($dashboarddetails['presentdays'] / $totalworkingdays * 100, 2) : 0;
$pay_date = date("Y-m-06", strtotime($dashboarddetails['month'] . " +1 month"));
$nextpay_date = date("Y-m-06", strtotime($dashboarddetails['month'] . " +2 month"));

$api_key = "Rsz13oNw618tGiUAVIGdhn44kF2yeyBE";
$country = "IN";
$year = 2025;

$url = "https://calendarific.com/api/v2/holidays?api_key={$api_key}&country={$country}&year={$year}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$holidays = [];

if ($httpcode == 200 && $response) {
    $data = json_decode($response, true);
    if (isset($data['response']['holidays'])) {
        $holidays = $data['response']['holidays'];
    }
}

$query="SELECT * FROM queries WHERE employee_id='$user_id' ORDER BY created_at DESC";
$querycheck=mysqli_query($conn,$query);

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
                <h4>Salary Summary <span>(Last Month : <?= date("F Y", strtotime($dashboarddetails['month'])) ?>)</span></h4>
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
                    <h4>View Recent Payslip <span>(<?= date("F Y", strtotime($dashboarddetails['month'])) ?>)</span></h4>
                    <a href="emppayslip.php?month=<?= date("Y-m", strtotime($dashboarddetails['month'])) ?>">View Payslip</a>

                </div>
            </div>

            <div class="attendance">
                <h4>Attendance Summary</h4>
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
                        <a href="../employee/attendance.php">View Details</a>
                        <p></p>
                    </div>
                </div>
                <div class="attendance-status">
                    <h4>Today's Attendance Status</h4>
                    <p class="status"><?php if($attendancestatus['status']=='1'){ ?>
                        <span style="background-color: green;">Present</span>
                        <?php } else{?>
                            <span style="background-color: red;">Absent</span>
                            <?php } ?>
                    
                    </p>
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
                <h4>Upcoming Events</h4>
                <div class="festival">
                    <div class="festivelist">
                        <?php if (!empty($holidays)) { ?>
                            <?php foreach ($holidays as $event) {
                                if ($event['date']['iso'] >= $today) { ?>
                                    <div>
                                        <h5><?= htmlspecialchars($event['name']) ?></h5>
                                        <p><?= htmlspecialchars(substr($event['date']['iso'], 0, 10)) ?></p>
                                    </div>
                        <?php $count++;
                                }
                                if ($count >= 4) break;
                            }
                            if ($count == 0) {
                                echo "<h5>No upcoming events found</h5>";
                            }
                        } else {
                            echo "no event found";
                        }
                        ?>
                    </div>
                    <div class="next-salary">
                        <h5>Next Salary Date</h5>
                        <p><?= $nextpay_date ?></p>
                    </div>
                </div>
            </div>

            <div class="query">
                <h3>Queries Record</h3>
                <div class="queries-table">
                    <table>
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Query Id</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php while($queryresult=mysqli_fetch_assoc($querycheck)) {?>
                            <tr>  
                                <td><?= $sn++ ?></td>
                                    <td><?= $queryresult['id'] ?></td>
                                    <td><?= htmlspecialchars($queryresult['subject']) ?></td>
                                    <td><?= htmlspecialchars($queryresult['message']) ?></td>
                                    <td><?php if($queryresult['status'] == 1) {?>
                                        <span style="color: green;">Resolved</span>
                                        <?php } else {?>
                                            <span style="color:red;">Pending</span>
                                            <?php }?>
                                        </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
    </section>
</main>