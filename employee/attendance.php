<?php
include '../config/db.php';
include '../config/auth.php';
requireRole([0]);
include '../includes/header.php';
$user_id = $_SESSION['user_id'];
$today = date("Y-m-d");
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn = ($page - 1) * $limit + 1;
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = $_GET['status'] ?? '';
$where = "employee_id='$user_id'";
if ($from != "") {
    $where .= " AND date >= '$from'";
}
if ($to != "") {
    $where .= " AND date <= '$to'";
}
if ($status != "") {
    $where .= " AND status='$status'";
}
$counttotal = "SELECT COUNT(*) AS total from attendance WHERE $where";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);


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

$attendancehistory = "SELECT * FROM attendance WHERE $where ORDER BY date DESC LIMIT $limit OFFSET $offset";
$history = mysqli_query($conn, $attendancehistory);


?>

<head>
    <link rel="stylesheet" href="../assets/css/empattendance.css">
    <link rel="stylesheet" href="../assets/css/pagination.css">

    <title>Employee Attendance page</title>
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
            <div class="attendance-filter">
                <form method="GET" class="filter">
                    <div>
                        <label>From:</label>
                        <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>" onchange="this.form.submit()">
                    </div>
                    <div>
                        <label>to:</label>
                        <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>"onchange="this.form.submit()">
                    </div>
                    <div>
                        <label>Status:</label>
                        <select name="status" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="1" <?= (($_GET['status'] ?? '') == '1') ? 'selected' : '' ?>>Present</option>
                            <option value="0" <?= (($_GET['status'] ?? '') == '0') ? 'selected' : '' ?>>Absent</option>
                        </select>
                    </div>
                </form>
            </div>
            <table class="attendance-table">
                <tr>
                    <th>S.NO</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Marked At</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($history)) { ?>
                    <tr>
                        <td><?= $sn++ ?></td>
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

        <?php include '../includes/pagination.php' ?>
    </section>
</main>