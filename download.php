<?php
require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload
include "./config/db.php";
include "./config/auth.php";

use Dompdf\Dompdf;

$emp_id = $_GET['emp'] ?? 0;
$month = $_GET['month'] ?? '';

if (!$emp_id || !$month) {
    die("Missing Employee or month");
}

$query = "
SELECT 
    p.*, 
    s.*, 
    ps.payslip_id,
    e.id AS emp_id,
    e.name,
    e.position,
    e.date_of_joining AS joining
FROM payroll p
JOIN employees e ON p.employee_id = e.id
JOIN salaries s ON p.salary_id = s.id
LEFT JOIN payslips ps ON ps.payroll_id = p.payroll_id
WHERE p.employee_id = ? AND p.month = ?
LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $emp_id, $month);
$stmt->execute();
$result_set = $stmt->get_result();

if ($result_set->num_rows === 0) {
    die("No payslip found for the selected month");
}

$result = $result_set->fetch_assoc();

$perday_salary = $result['basic_salary'] / $result['total_working_days'];
$absentdeduction = $perday_salary * $result['absent_days'];
$pay_date = date("Y-m-06", strtotime($result['month'] . " +1 month"));


ob_start();
include __DIR__ . "/payslip_view.php";
$html = ob_get_clean();

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Payslip.pdf", ["Attachment" => 1]);
