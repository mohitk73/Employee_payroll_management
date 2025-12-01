<?php
include '../config/auth.php';
requireRole([1,2]);
include '../config/db.php';
if (isset($_GET['month'])) {
            $selectmonth = $_GET['month'];
            $payslips = $sql = "SELECT p.*, e.id, e.name
              FROM payroll p
        JOIN employees e ON p.employee_id = e.id
        WHERE DATE_FORMAT(p.month, '%Y-%m') = '$selectmonth'";
                $payslips = mysqli_query($conn, $payslips);
}

include '../includes/header.php';
?>
<head>
    <link rel="stylesheet" href="../assets/css/payslipsadmin.css">
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
                        <th>Month</th>
                        <th>Employee Id</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($_GET['month'])) while($payslipsresult=mysqli_fetch_assoc($payslips)) { ?>
                    <tr>
                        <td><?= date('F Y', strtotime($payslipsresult['month'])); ?></td>
                        <td><?= $payslipsresult['id'] ?></td>
                        <td><?= htmlspecialchars($payslipsresult['name']) ?></td>
                        <td>
                            <a class="slip" href="payslip.php?emp=<?= $payslipsresult['employee_id'] ?>&month=<?= ($payslipsresult['month']) ?>">View Payslip</a>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            
        </div>  
    </section>
</main>