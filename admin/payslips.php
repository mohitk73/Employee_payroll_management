<?php
include '../config/auth.php';
requireRole([1,2]);
include '../config/db.php';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn=($page-1) *$limit+1;
$where = "1"; 
if (!empty($month)) {
    $where .= " AND DATE_FORMAT(month, '%Y-%m') = '$month'";
}
$counttotal = "SELECT COUNT(*) AS total from payroll WHERE $where ";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

if (isset($_GET['month'])) {
            $selectmonth = $_GET['month'];
            $payslips = "SELECT p.*, e.id, e.name,ps.payslip_id
              FROM payroll p
        JOIN employees e ON p.employee_id = e.id
         JOIN payslips ps ON p.payroll_id=ps.payroll_id
        WHERE DATE_FORMAT(p.month, '%Y-%m') = '$selectmonth'
        LIMIT $limit OFFSET $offset";
                $payslips = mysqli_query($conn, $payslips);
}

include '../includes/header.php';
?>
<head>
    <link rel="stylesheet" href="../assets/css/payslipsadmin.css">
    <link rel="stylesheet" href="../assets/css/pagination.css">
</head>
<main>
    <section>
        <div class="formdata">
            <form method="get" class="form">
                <label>Select Month:</label>
               <input type="month" name="month" required value="<?= isset($_GET['month']) ? $_GET['month'] : '' ?>">
                <button type="submit">Generate Payslips</button>
            </form>
        </div>
        <div class="allpayslip">
        <h3>All Pay Slips<?= isset($_GET['month']) ? " for " . date('F Y', strtotime($_GET['month'])) : '' ?></h3>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Payslip Id</th>
                        <th>Month</th>
                        <th>Employee Id</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($_GET['month'])){?>
                        <?php if(mysqli_num_rows($payslips)>0){?>
                            <?php while($payslipsresult=mysqli_fetch_assoc($payslips)) { ?>
                    <tr>
                        <td><?= $sn++ ?></td>
                        <td><?= $payslipsresult['payslip_id'] ?></td>
                        <td><?= date('F Y', strtotime($payslipsresult['month'])); ?></td>
                        <td><?= $payslipsresult['id'] ?></td>
                        <td><?= htmlspecialchars($payslipsresult['name']) ?></td>
                        <td>
                            <a class="slip" href="payslip.php?emp=<?= $payslipsresult['employee_id'] ?>&month=<?= ($payslipsresult['month']) ?>">View Payslip</a>
                        </td>
                    </tr>
                    <?php }?>
                    <?php } else{?>
                        <tr>
                            <td colspan="12" style="text-align: center;">
                                No Records Found!
                            </td>
                        </tr>
                        <?php }?>
                        <?php } else{?>
                             <tr>
                            <td colspan="12" style="text-align: center;">
                                No Records Found!
                            </td>
                        </tr>
                            <?php }?>
                </tbody>
            </table>
           <?php include '../includes/pagination.php' ?>
        </div>  
    </section>
</main>