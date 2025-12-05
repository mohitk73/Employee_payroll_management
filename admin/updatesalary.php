<?php
include "../config/auth.php";
requireRole([1, 2]);
include "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: salary_structure.php");
    exit();
}

$salary_id = $_GET['id'];
$sql = "SELECT * FROM salaries WHERE id = $salary_id";
$result = mysqli_query($conn, $sql);
$salary = mysqli_fetch_assoc($result);

if (!$salary) {
    echo "Salary structure not found!";
    exit();
}

if (isset($_POST['update'])) {
    $basic_salary = $_POST['basic_salary'];
    $hra = $_POST['hra'];
    $deductions = $_POST['deductions'];

    $update_sql = "UPDATE salaries 
                   SET basic_salary='$basic_salary', hra_allowances='$hra', deduction='$deductions' 
                   WHERE id='$salary_id'";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: salary_structure.php");
        exit(); 
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

include '../includes/header.php';
?>

<head>
    <link rel="stylesheet" href="../assets/css/updatesalary.css">
</head>

<main>
    <section>
        <h3>Update Salary</h3>

        <form method="post">
            <label for="basic_salary">Basic Salary</label><br>
            <input type="number" name="basic_salary" value="<?= $salary['basic_salary'] ?>" required><br><br>

            <label for="hra">HRA (House Rent Allowance)</label><br>
            <input type="number" name="hra" value="<?= $salary['hra_allowances'] ?>" required><br><br>

            <label for="deductions">Fixed Deductions</label><br>
            <input type="number" name="deductions" value="<?= $salary['deduction'] ?>" required><br><br>
<a href="salary_structure.php">⬅ Back</a>
            <button type="submit" name="update">Update Salary</button>
        </form>

        
    </section>
</main>
