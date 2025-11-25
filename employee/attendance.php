<?php
include '../config/db.php';
include '../config/auth.php';
requireRole([0]);
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$today = date("Y-m-d");

$atten = "SELECT status FROM attendance WHERE employee_id='$user_id' AND date='$today'";
$status = mysqli_query($conn, $atten);
$todayattendance = mysqli_fetch_assoc($status);
if ($todayattendance) {
    $todaystatus = ($todayattendance['status'] == 1) ? "Present" : "Absent";
} else {
    $todaystatus = "Not Marked";
}

$attandance = "SELECT COUNT(CASE WHEN status=1 THEN 1 END) AS presentdays,
    COUNT(CASE WHEN status=0 THEN 1 END) AS absentdays FROM attendance
    WHERE employee_id='$user_id'";
$attandancecheck = mysqli_query($conn, $attandance);
$attandancedays = mysqli_fetch_assoc($attandancecheck);
$totalpresent = $attandancedays['presentdays'];
$totalabsent = $attandancedays['absentdays'];
$totalworking = $totalpresent + $totalabsent;
$percentage = ($totalworking > 0) ? round(($totalpresent / $totalworking) * 100, 2) : 0;
if ($todayattendance) {
    if ($todayattendance['status'] == 1) {
        $todaystatus = "Present";
        $statusClass = "present";
    } else {
        $todaystatus = "Absent";
        $statusClass = "absent";
    }
} else {
    $todaystatus = "Not Marked";
    $statusClass = "not-marked";
}

$attendancehistory = "SELECT * FROM attendance WHERE employee_id='$user_id' ORDER BY date DESC";
$history = mysqli_query($conn, $attendancehistory);

?>

<head>
    <link rel="stylesheet" href="../assets/css/empattendance.css">
</head>
<main>
    <section>
        <h3>Your Attendance - <span><?= $today ?></span></h3>
        <div class="status">
            <h4>Today's Attendance Status : <span class="<?= $statusClass ?>"><?= $todaystatus ?></span>
            </h4>
            </h4>
        </div>

        <div class="summary">
            <h4>Attendance Summary</h4>
            <div class="summary-details">
                <div class="card">
                    <h3>Total Working Days</h3>
                    <h4><?= $totalworking ?></h4>
                </div>
                <div class="card">
                    <h3>Total Present Days</h3>
                    <h4><?= $totalpresent ?></h4>
                </div>
                <div class="card">
                    <h3>Total Absent Days</h3>
                    <h4><?= $totalabsent ?></h4>
                </div>
                <div class="card">
                    <h3>Attendance Percentage</h3>
                    <h4><?= $percentage ?> %</h4>

                </div>
            </div>
        </div>
        <div class="attendance-history">
            <h4>Attendance History</h4>
            <table class="attendance-table">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Marked At</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($history)) { ?>
                    <tr>
                        <td><?= $row['date'] ?></td>
                        <td>
                            <?php if ($row['status'] == 1) { ?>
                                <span class="present">Present</span>
                            <?php } else { ?>
                                <span class="absent">Absent</span>
                            <?php } ?>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </section>
</main>