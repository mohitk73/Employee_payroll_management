<?php
include "../config/auth.php";
requireRole([1, 2]);
include "../config/db.php";
 if (!isset($_GET['emp']) || !isset($_GET['month'])) {
    die("Missing Employee or month");
} 
include "../config/db.php";
$emp_id = intval($_GET['emp']);
$month = $_GET['month'];

$payslip = "SELECT p.*,s.*,ps.payslip_id ,e.id,e.name,e.position,e.date_of_joining AS joining FROM payroll p 
JOIN employees e ON p.employee_id=e.id 
JOIN salaries s ON p.salary_id = s.id
LEFT JOIN payslips ps ON ps.payroll_id = p.payroll_id
where p.employee_id='$emp_id' AND p.month='$month'
LIMIT 1";
$payslipcheck=mysqli_query($conn,$payslip);
$result=mysqli_fetch_assoc($payslipcheck);
$perday_salary=$result['basic_salary']/$result['total_working_days'];
$totalworking=$result['present_days'] + $result['absent_days'];
$absentdeduction=$perday_salary * $result['absent_days'];
$pay_month = $result['month'];
$pay_date = date("Y-m-06", strtotime($pay_month . " +1 month"));
include '../includes/header.php';
?>

<head>
    <link rel="stylesheet" href="../assets/css/payslip.css">
</head>
<main>
    <section>
        <div class="company-name">
            <div class="company-head">
                <h3>Mind2web</h3>
                <p>Mohali,India</p>
            </div>
            <div class="company-logo">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4ufOjMD7ZU4Ww2oHuSAuX1nTeLe6hZu_gIA&s">
            </div>
        </div>
        <hr>
        <div class="month">
            <h3>Payslip for the month of <span><?= date('F Y', strtotime($result['month']));?></span></h3>
        </div>


        <div class="employee">
            <h3>Employee Pay Summary</h3>
        </div>
        <div class="employee-summary">
            <div class="employee-data">
                <div class="employee-head">
                    <p>Payslip ID</p>
                    <P>Employee ID</P>
                    <p>Employee Name</p>
                    <p>Job Position</p>
                    <p>Date of Joining</p>
                    <p>Pay Month</p>
                    <p>Pay Date</p>

                </div>
                <div class="employee-result">
                    <p>: <?= $result['payslip_id'] ?></p>
                     <p>: <?= $result['id'] ?></p>
                    <p>: <?= $result['name'] ?></p>
                    <p>: <?= $result['position'] ?></p>
                    <p>: <?= $result['joining'] ?></p>
                    <p>: <?= date('F Y', strtotime($result['month']));?></p>
                    <p>: <?= date("d M Y", strtotime($pay_date)); ?></p>
                </div>

            </div>
            <div class="employeenet-pay">
                <h3>Employee Net Pay</h3>
                <p>₹<?= number_format($result['net_salary'],2) ?></p>
                <p class="paid">Paid Days : <?= $result['present_days'] ?> | LOP Days : <?= $result['absent_days'] ?></p>
            </div>
        </div>
        <hr>

        <div class="salary-details">
            <table>
                <thead>
                    <tr>
                        <th>Earnings</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td>₹<?= number_format($result['basic_salary'],2) ?></td>
                    </tr>
                    <tr>
                        <td>House Rent Allowance</td>
                        <td>₹<?= number_format($result['hra'],2) ?></td>
                    </tr>
                    <tr class="gross">
                        <td>Gross Earnings</td>
                        <td>₹<?= number_format($result['gross_salary'],2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>

        <div class="attendance-details">
            <table>
                <thead>
                    <tr>
                        <th>Attendence</th>
                        <th>Total Days</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Present Days</td>
                        <td><?= $result['present_days'] ?></td>
                    </tr>
                    <tr>
                        <td>Absent Days</td>
                        <td><?= $result['absent_days'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>

        <div class="deduction-details">
            <table>
                <thead>
                    <tr>
                        <th>Deductions</th>
                        <th>(-)Amounts</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Professional Tax</td>
                        <td>₹<?= number_format($result['deduction'],2) ?></td>
                    </tr>
                    <tr>
                        <td>Absent Days</td>
                        <td>₹<?= number_format($absentdeduction,2) ?></td>
                    </tr>
                    <tr class="deduction">
                        <td>Total Deductions</td>
                        <td>₹<?= number_format($result['deductions'],2) ?></td>
                    </tr>
                    <tr class="netpay">
                        <td>Net Pay (Gross Earnings - Deductions)</td>
                        <td>₹<?= number_format($result['net_salary'],2) ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="totalnetpay">
            <h3>Total Net Payable <span>₹<?= number_format($result['net_salary'],2) ?></span></h3>
        </div>
        <hr>
        <div class="note">
            <p>--This Document is generated by Mind2Web Payroll, therefore ,a signature is not required--</p>
        </div>
    </section>
    <div class="downloadslip">
    <a href ="../admin/payroll.php" class="back">Back to Payroll</a>
    <a class="download" href="download.php">Download Pay Slip</a>
    </div>
</main>