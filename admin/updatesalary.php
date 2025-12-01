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

    $sql = "UPDATE salaries 
            SET basic_salary='$basic_salary', hra_allowances='$hra', deduction='$deductions' 
            WHERE id='$salary_id'";
    mysqli_query($conn, $sql);
    $msg = "Salary updated successfully!";
}
include '../includes/header.php';
?>

<head>
    <style>
        section {
            display: grid;
            margin: 100px auto;
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        h3 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #2c3e50;
        }


        form input {
            width: 100%;
            padding: 10px 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-top: 10px;
        }

        button {
            background-color: #2c3e50;
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            float: right;
            cursor: pointer;
        }
        form a{
            text-decoration: none;
            background-color: #2c3e50;
            color:white;
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            float: left;
              
        }
    </style>
</head>
<main>
    <section>
        <h3>Update Salary</h3>
         <div>
<p style="color: green;"><?= isset($msg) ? $msg : '' ?></p>
            </div><br>
        <form method="post">
            <label for="basic_salary">Basic Salary</label><br>
            <input type="number" id="basic_salary" name="basic_salary" value="<?= $salary['basic_salary'] ?>" required><br><br>

            <label for="hra">HRA (House Rent Allowance)</label><br>
            <input type="number" id="hra" name="hra" value="<?= $salary['hra_allowances'] ?>" required><br><br>

            <label for="deductions">Fixed Deductions</label><br>
            <input type="number" id="deductions" name="deductions" value="<?= $salary['deduction'] ?>" required><br><br>
            <a class="back" href="salary_structure.php">⬅ Back</a>
            <button type="submit" name="update">Update Salary</button>
        </form>


    </section>
</main>