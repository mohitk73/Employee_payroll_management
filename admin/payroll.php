<?php
include "../config/auth.php"; 
requireRole([1,2]);
include "../config/db.php";
include '../includes/header.php';
$filter_month = $_POST['month'] ?? date('Y-m'); 
$month=$_GET['filter_month'] ?? '';
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn=($page-1) *$limit+1;
$where = "1"; 
if (!empty($month)) {
    $where .= " AND DATE_FORMAT(month, '%Y-%m') = '$month'";
}
$counttotal = "SELECT COUNT(*) AS total from payroll WHERE $where";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

list($year, $month_num) = explode('-', $filter_month);
$total_working_days = cal_days_in_month(CAL_GREGORIAN, $month_num, $year);

$month_date  = $filter_month . '-01';
$month_start = $month_date;
$month_end   = date("Y-m-t", strtotime($month_start));
$today = date('Y-m');
$last_completed_month = date("Y-m", strtotime("first day of last month"));
$block_generate = true;
if ($filter_month > $today) {
    $msg = "Payroll cannot be generated for a n upcoming month!";
}
elseif ($filter_month == $today) {
    $msg = "Payroll cannot be generated for an ongoing month!";
}
elseif ($filter_month < $last_completed_month) {
    $att_check = mysqli_query(
        $conn,
        "SELECT id FROM attendance 
         WHERE YEAR(date)=$year AND MONTH(date)=$month_num 
         LIMIT 1"
    );
    if (mysqli_num_rows($att_check) == 0) {
        $msg = "No payroll exists for this month because NO attendance data was found.";
    } else {
        $block_generate = false;
        $msg = "Payroll can be generated because attendance exists for $filter_month.";
    }
}
elseif ($filter_month == $last_completed_month) {
    $block_generate = false;
    $msg = "Payroll can be generated for the last completed month: $filter_month.";
}

if (isset($_POST['generate']) && !$block_generate) {

    $salary_sql = "SELECT s.id AS salary_id, s.employee_id, s.basic_salary, s.hra_allowances AS hra, s.deduction AS fixed_deduction
                   FROM salaries s
                   WHERE s.status=1";

    $salary_result = mysqli_query($conn, $salary_sql);

    while ($row = mysqli_fetch_assoc($salary_result)) {
        $employee_id = $row['employee_id'];
        $att_sql = "SELECT 
                        COUNT(CASE WHEN status=1 THEN 1 END) AS present_days,
                        COUNT(CASE WHEN status=0 THEN 1 END) AS absent_days
                    FROM attendance
                    WHERE employee_id=$employee_id 
                      AND YEAR(date)=$year 
                      AND MONTH(date)=$month_num";

        $att_res = mysqli_query($conn, $att_sql);
        $att = mysqli_fetch_assoc($att_res);

        $present_days = $att['present_days'] ?? 0;
        $absent_days  = $att['absent_days'] ?? ($total_working_days - $present_days);
        $per_day_salary= $row['basic_salary'] / $total_working_days;
        $gross_salary = $row['basic_salary'] + $row['hra'];
        $absent_deduction = $per_day_salary * $absent_days;
        $total_deductions = $row['fixed_deduction'] + $absent_deduction;
        $net_salary = $gross_salary - $total_deductions;

        $check_sql = "SELECT * FROM payroll 
                      WHERE employee_id=$employee_id 
                      AND month='$month_date'";

        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) == 0) {
            $insert_sql = "INSERT INTO payroll 
                (employee_id, salary_id, month, basic_salary, hra, present_days, absent_days, gross_salary, deductions, net_salary, status)
                VALUES
                ($employee_id, {$row['salary_id']}, '$month_date', {$row['basic_salary']}, {$row['hra']}, 
                $present_days, $absent_days, $gross_salary, $total_deductions, $net_salary, 0)";
            mysqli_query($conn, $insert_sql);
        }
    }

    $msg = "Payroll generated for $filter_month!";
}
$payroll_sql = "SELECT p.*, e.name, e.department 
                FROM payroll p
                JOIN employees e ON p.employee_id = e.id
                WHERE p.month BETWEEN '$month_start' AND '$month_end'
                ORDER BY p.month DESC LIMIT $limit OFFSET $offset";

$payroll_result = mysqli_query($conn, $payroll_sql);
?>

<head>
    <link rel="stylesheet" href="../assets/css/payroll.css" >
    <link rel="stylesheet" href="../assets/css/pagination.css" >
</head>
<main>
    <section>
        <h3>Payroll Management</h3>
        <?php if(isset($msg)) { echo '<p style="color:green;">'.$msg.'</p>'; } ?><br>

        <form method="post" action="">
            <label>Select Month:</label>
            <input type="month" name="month" value="<?php echo $filter_month; ?>" required>
            <input type="submit" name="generate" value="Generate Payroll">
        </form>
        <h4>Payroll Records for <?php echo date('F Y', strtotime($month_start)); ?></h4>
        <table border="1" cellpadding="10">
        <tr>
            <th>Month</th>
            <th>Employee Id</th>
            <th>Name</th>
            <th>Department</th>
            <th>Basic Salary</th>
            <th>Hra</th>
            <th>Total Working Days</th>
            <th>Total Present Days</th>
            <th>Total Absent Days</th>
            <th>Per Day Salary</th>
            <th>Gross Salary</th>
            <th>Deductions</th>
            <th>Net Salary</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($payroll_result)){ 
            $per_day = $row['basic_salary'] / $total_working_days;
        ?>
        <tr>
            <td><?php echo date('F Y', strtotime($row['month'])); ?></td>
            <td><?php echo $row['employee_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo number_format($row['basic_salary'],2); ?></td>
            <td><?php echo number_format($row['hra'],2);?></td>
            <td><?php echo $row['present_days'] + $row['absent_days']; ?></td>
            <td><?php echo $row['present_days']; ?></td>
            <td><?php echo $row['absent_days']; ?></td>
            <td><?php echo number_format($per_day,2); ?></td>
            <td><?php echo number_format($row['gross_salary'],2); ?></td>
            <td><?php echo number_format($row['deductions'],2); ?></td>
            <td><?php echo number_format($row['net_salary'],2); ?></td>
            <td><?php echo $row['status'] == 1 ? 'Paid' : 'Unpaid'; ?></td>
            <td>
                <a href="../admin/payslip.php?emp=<?= $row['employee_id'] ?>&month=<?= $row['month'] ?>">View Payslip</a>
            </td>
        </tr>
        <?php } ?>
        </table>
        <div class="pagination">
            <nav>
                <ul>
                    <?php if ($page > 1):  ?>
                        <li><a href="?month=<?= $month ?>&page=<?= $page - 1 ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalpages; $i++): ?>
                        <li class="<?= ($i == $page) ? 'active' : '' ?>"><a href="?month=<?= $month ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalpages): ?>
                        <li><a href="?month=<?= $month ?>&page=<?= $page + 1 ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </section><br>
    <br>
    <a class="back"  href="../admin/employees.php"><- Back To Dashboard</a>
</main>
