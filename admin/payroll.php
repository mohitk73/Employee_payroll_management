<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != '1'){
    header("Location: login.php");
    exit();
}

include "../config/db.php";

// Generate payroll
if(isset($_POST['generate'])){
    $month_input = $_POST['month']; // format YYYY-MM
    $month_date = $month_input . '-01';
    list($year, $month_num) = explode('-', $month_input);
    $total_working_days = cal_days_in_month(CAL_GREGORIAN, $month_num, $year); // total days in month

    $salary_sql = "SELECT s.id AS salary_id, s.employee_id, s.basic_salary, s.hra_allowances AS hra, s.deduction AS fixed_deduction
                   FROM salaries s
                   WHERE s.status=1";
    $salary_result = mysqli_query($conn, $salary_sql);

    while($row = mysqli_fetch_assoc($salary_result)){
        $employee_id = $row['employee_id'];

        // Count present and absent days
        $att_sql = "SELECT 
                        COUNT(CASE WHEN status=1 THEN 1 END) AS present_days,
                        COUNT(CASE WHEN status=0 THEN 1 END) AS absent_days
                    FROM attendance
                    WHERE employee_id=$employee_id AND YEAR(date)=$year AND MONTH(date)=$month_num";
        $att_res = mysqli_query($conn, $att_sql);
        $att = mysqli_fetch_assoc($att_res);
        $present_days = $att['present_days'] ?? 0;
        $absent_days = $att['absent_days'] ?? ($total_working_days - $present_days);

        // Salary calculations
        $per_day_salary = $row['basic_salary'] / $total_working_days;
        $gross_salary = $row['basic_salary'] + $row['hra'];
        $absent_deduction = $per_day_salary * $absent_days;
        $total_deductions = $row['fixed_deduction'] + $absent_deduction;
        $net_salary = $gross_salary - $total_deductions;

        // Check before inserting
        $check_sql = "SELECT * FROM payroll WHERE employee_id=$employee_id AND month='$month_date'";
        $check_res = mysqli_query($conn, $check_sql);

        if(mysqli_num_rows($check_res) == 0){
            $insert_sql = "INSERT INTO payroll 
                (employee_id, salary_id, month, basic_salary, hra, present_days, absent_days, gross_salary, deductions, net_salary, status)
                VALUES
                ($employee_id, {$row['salary_id']}, '$month_date', {$row['basic_salary']}, {$row['hra']}, $present_days, $absent_days, $gross_salary, $total_deductions, $net_salary, 0)";
            mysqli_query($conn, $insert_sql);
        }
    }
    $msg = "Payroll generated for $month_input!";
}

// Filter payroll to display only selected month
$filter_month = $_POST['month'] ?? date('Y-m'); 
$month_start = $filter_month . '-01';
$month_end = date("Y-m-t", strtotime($month_start));

$payroll_sql = "SELECT p.*, e.name, e.department 
                FROM payroll p
                JOIN employees e ON p.employee_id = e.id
                WHERE p.month BETWEEN '$month_start' AND '$month_end'
                ORDER BY p.month DESC";
$payroll_result = mysqli_query($conn, $payroll_sql);
?>

<h2>Payroll Management</h2>

<?php if(isset($msg)) { echo '<p style="color:green;">'.$msg.'</p>'; } ?>

<form method="post" action="">
    <label>Select Month:</label>
    <input type="month" name="month" value="<?php echo $filter_month; ?>" required>
    <input type="submit" name="generate" value="Generate Payroll">
</form>

<h3>Payroll Records for <?php echo date('F Y', strtotime($month_start)); ?></h3>

<table border="1" cellpadding="10">
<tr>
    <th>Month</th>
    <th>Employee Id</th>
    <th>Name</th>
    <th>Department</th>
    <th>Basic Salary</th>
    <th>Total Working Days</th>
    <th>Total Present Days</th>
    <th>Total Absent Days</th>
    <th>Per Day Salary</th>
    <th>Gross Salary</th>
    <th>Deductions</th>
    <th>Net Salary</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($payroll_result)){ 
    $per_day = $row['basic_salary'] / $total_working_days;
?>
<tr>
    <td><?php echo date('F Y', strtotime($row['month'])); ?></td>
    <td><?php echo $row['employee_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['department']; ?></td>
    <td><?php echo number_format($row['basic_salary'],2); ?></td>
    <td><?php echo $total_working_days; ?></td>
    <td><?php echo $row['present_days']; ?></td>
    <td><?php echo $row['absent_days']; ?></td>
    <td><?php echo number_format($per_day,2); ?></td>
    <td><?php echo number_format($row['gross_salary'],2); ?></td>
    <td><?php echo number_format($row['deductions'],2); ?></td>
    <td><?php echo number_format($row['net_salary'],2); ?></td>
    <td><?php echo $row['status'] == 1 ? 'Paid' : 'Unpaid'; ?></td>
</tr>
<?php } ?>
</table>
