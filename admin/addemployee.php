<?php
include "../config/db.php";
include "../config/auth.php"; 
requireRole([1,2,3]);  

if (isset($_POST['add'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $position = htmlspecialchars($_POST['position']);
    $department = htmlspecialchars($_POST['department']);
    $manager_id = htmlspecialchars($_POST['manager_id']);
    $date_of_joining = $_POST['date_of_joining'];
    $address = htmlspecialchars($_POST['address']);
    $status = $_POST['status'];
    if (empty($name) || !preg_match("/^[A-Za-z\s]{2,50}$/", $name)) {
        $error = "Name should be between 2-50 characters and contain only letters and spaces.";
    }
     if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }
   
    if (empty($phone) || !preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Phone number must be exactly 10 digits.";
    }
    if (empty($position)) {
        $error = "Position is required.";
    }
    if (empty($department)) {
        $error = "Department is required.";
    }
    if (empty($date_of_joining)) {
        $error = "Date of joining is required.";
    }
    if (empty($address) || !preg_match("/^[A-Za-z0-9\s,.-]{5,200}$/", $address)) {
        $error = "Address is required and should be between 5-200 characters.";
    }
    if (empty($manager_id)) {
        $manager_id = NULL;  
    }
    
    if (!isset($error)) {
        $sql = "INSERT INTO employees 
            (name, email, password, role, phone, position, department, manager_id, date_of_joining, address, status)
            VALUES ('$name', '$email', '$password', '$role', '$phone', '$position', '$department', " . ($manager_id !== NULL ? $manager_id : "NULL") . ", '$date_of_joining', '$address', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: employees.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
include('../includes/header.php');
?>
<head>
    <link rel="stylesheet" href="../assets/css/addemployee.css">
</head>
<main>
<form method="POST">
    <h3>Add New Employee</h3>
    <hr>
    <?php if(!empty($error)) {?>
            <p style="color: red;margin-bottom:5px;"><?= $error ?></p>
            <?php }?>

    <label>Name</label><br>
    <input type="text" name="name" pattern="[A-Za-z\s]{2,50}" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role</label><br>
    <select name="role">
    <option value="0">Employee</option>
    <option value="1">Admin</option>
    <option value="2">HR</option>
    <option value="3">Manager</option>
</select><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone" maxlength="10" pattern="[0-9]{10}" required><br><br>

    <label>Position</label><br>
    <input type="text" name="position" required><br><br>

    <label>Department</label><br>
    <!-- <input type="text" name="department" required><br><br> -->
     <select name="department" required>
        <option value="">Select Department</option>
        <option value="IT">IT</option>
        <option value="HR">HR</option>
        <option value="Sales">Sales</option>
</select><br><br>
<label>Assign Manager</label><br>
<select name="manager_id">
        <option value="">No Manager</option>
        <?php
        $managers = mysqli_query($conn, "SELECT id, name FROM employees WHERE role = 3"); 
        while ($manager = mysqli_fetch_assoc($managers)) {
            echo "<option value='" . $manager['id'] . "'>" . $manager['name'] . "</option>";
        }
        ?>
    </select><br><br>


    <label>Date of Joining</label><br>
    <input type="date" name="date_of_joining" required><br><br>

    <label>Address</label><br>
    <textarea name="address" rows="3"  pattern="[A-Za-z0-9\s,.-]{5,200}" maxlength="200" required></textarea><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select><br><br>

    <button type="submit" name="add">Add Employee</button>
    <a class="back" href="employees.php">⬅ Back to Employee List</a>

</form>
<br>
</main>
