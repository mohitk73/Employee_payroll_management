<?php
include "../config/auth.php"; 
requireRole([1,2]);
include "../config/db.php";

/* if(isset($_POST['update'])){
    $salary_id = $_POST['salary_id'];
    $basic_salary =$_POST['basic_salary'];
    $hra =$_POST['hra'];
    $deductions =$_POST['deductions'];

    $sql = "UPDATE salaries 
            SET basic_salary='$basic_salary', hra_allowances='$hra',deduction='$deductions' 
            WHERE id='$salary_id'";
    mysqli_query($conn, $sql);
    $msg = "Salary updated successfully!";
} */

$sql = "SELECT s.id, e.name, s.basic_salary, s.hra_allowances AS hra, s.deduction AS deductions
        FROM salaries s
        JOIN employees e ON s.employee_id = e.id";
$result = mysqli_query($conn, $sql);

include('../includes/header.php');
?>
<head>
    <link rel="stylesheet" href="../assets/css/salarystructure.css" >
</head>
<main>
    <section>
        <div style="display: flex;justify-content:space-between;align-items:center;">
              <h3>Employees Salary Structure</h3>
            <a class="salary" href="addsalary.php">+ Add Salary Structure</a>
        </div>
      

<?php if(isset($msg)) { echo '<p style="color:green;">'.$msg.'</p>'; } ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Employee Name</th>
        <th>Basic Salary</th>
        <th>HRA</th>
        <th>Deductions</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)){ ?>
    <tr>
        <form method="post" action="">
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo $row['basic_salary'];?></td>
            <td><?php echo $row['hra']; ?></td>
            <td><?php echo $row['deductions']; ?></td>
            <td>
                <a href="updatesalary.php?id=<?= $row['id'] ?>">Update</a>
            </td>
        </form>
    </tr>
    <?php } ?>
</table>

    </section>
</main>

