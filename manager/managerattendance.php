<?php
include "../config/db.php";
include "../config/auth.php";
requireRole([1, 2, 3]);

$date = date("Y-m-d");
$limit = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn = ($page - 1) * $limit + 1;

$mngid=$_SESSION['user_id'];
$where = [];
$where[]=" manager_id='$mngid'";
if (!empty($_GET['date'])) {
    $date = mysqli_real_escape_string($conn, $_GET['date']);
} else {
    $date = date("Y-m-d");
}

if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $where[] = "e.name LIKE '%$name%'";
}

if (isset($_GET['status'])) {
    if ($_GET['status'] === "1" || $_GET['status'] === "0") {
        $status = mysqli_real_escape_string($conn, $_GET['status']);
        $where[] = "a.status = '$status'";
    } elseif ($_GET['status'] === "null") {
        $where[] = "a.status IS NULL";
    }
}

$wherestm = "";
if (!empty($where)) {
    $wherestm = "WHERE " . implode(" AND ", $where);
}

$counttotal = "SELECT COUNT(*) AS total FROM employees e
    LEFT JOIN attendance a 
    ON e.id = a.employee_id 
    AND a.date = '$date'
";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

if (isset($_GET['emp_id']) && isset($_GET['status'])) {
    $emp_id = $_GET['emp_id'];
    $status = $_GET['status'];
    $check = mysqli_query(
        $conn,
        "SELECT id FROM attendance WHERE employee_id='$emp_id' AND date='$date'"
    );

    if (mysqli_num_rows($check) > 0) {
        $_SESSION['msg'] = "Attendance already marked!";
    } else {
        mysqli_query(
            $conn,
            "INSERT INTO attendance (employee_id, date, status)
            VALUES ('$emp_id', '$date', '$status')"
        );
        $_SESSION['msg'] = "Attendance saved successfully!";
    }

    header("Location: attendance.php");
    exit();
}

$employees = mysqli_query($conn, "SELECT * FROM employees ORDER BY name");

$today = mysqli_query($conn, "
    SELECT e.id, e.name, e.position, a.status, a.created_at
    FROM employees e
    LEFT JOIN attendance a 
        ON e.id = a.employee_id
        AND a.date = '$date'
    $wherestm
    ORDER BY e.name
    LIMIT $limit OFFSET $offset
");

include('../includes/header.php');
?>

<head>
    <link rel="stylesheet" href="../assets/css/attendance.css">
    <link rel="stylesheet" href="../assets/css/pagination.css">
</head>

<main>
    <section>
        <h3>Attendance – <?= $date ?></h3>

        <form method="GET" class="attendance-filter">
            <input type="date" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') ?>"  onchange="this.form.submit()">
            <input type="text" name="name" placeholder="Search by name" value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>" onchange="this.form.submit()">
            <select name="status"  onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === "1") ? "selected" : "" ?>>Present</option>
                <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === "0") ? "selected" : "" ?>>Absent</option>
                <option value="null" <?= (isset($_GET['status']) && $_GET['status'] === "null") ? "selected" : "" ?>>Not Marked</option>
            </select>
        </form>
        <table class="attendance-table">
            <tr>
                <th>Employee Id</th>
                <th>Name</th>
                <th>Position</th>
                <th>Status</th>
                <th>Marked At</th>
            </tr>
            <?php if (mysqli_num_rows($today) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($today)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['position']) ?></td>

                        <td>
                            <?php if ($row['status'] === NULL) { ?>
                                <span class="badge gray">Not Marked</span>
                            <?php } elseif ($row['status'] == 1) { ?>
                                <span class="badge green">Present</span>
                            <?php } else { ?>
                                <span class="badge red">Absent</span>
                            <?php } ?>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="12" style="text-align:center;">No Record Found!</td>
                </tr>
            <?php } ?>
        </table>

        <?php include '../includes/pagination.php' ?>
    </section>
</main>

<script>
    function disableButton(btn) {
        btn.textContent = "Marked...";
        btn.style.opacity = "0.5";
        btn.style.pointerEvents = "none";
    }
</script>
