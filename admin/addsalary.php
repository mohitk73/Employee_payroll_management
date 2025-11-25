<?php
include "../config/db.php";
include "../config/auth.php"; 
requireRole([1,2,3]);  
if(isset($_POST['submit'])){
$employee_id=$_POST['employee_id'];
 $basic_salary = $_POST['basic_salary'];
    $hra = $_POST['hra'];
    $deductions = $_POST['deductions'];

    $sql = "INSERT INTO salaries (employee_id, basic_salary, hra_allowances, deduction) 
            VALUES ('$employee_id', '$basic_salary', '$hra', '$deductions')";
    $result = mysqli_query($conn, $sql);
    
    if($result){
        echo "<p style='color:green;'>Salary structure added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: ".mysqli_error($conn)."</p>";
    }
}
include('../includes/header.php');
?>
<main>
    <section>
       <form method="post" action="">

       <h3>Set Salary Structure</h3><br>
       <hr><br>
    <label>Employee:</label>
    <select name="employee_id" required>
        <option value="">Select Employee</option>
        <?php
        $sql = "SELECT id, name FROM employees";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            echo "<option value='".$row['id']."'>".$row['name']."</option>";
        }
        ?>
    </select>
    <br><br>
    <label>Basic Salary:</label>
    <input type="number" name="basic_salary" required>
    <br><br>

    <label>HRA (House Rent Allowance):</label>
    <input type="number" name="hra" required>
    <br><br>

    <label>Fixed Deductions:</label>
    <input type="number" name="deductions" value="0">
    <br><br>
    <a href="employees.php">Back to Employees</a>

    <input type="submit" name="submit" value="Save Salary Structure">
</form>
    </section>
</main>