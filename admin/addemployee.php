<?php
include "../config/db.php";
include "../config/auth.php"; 
requireRole([1,2,3]);  

if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $date_of_joining = $_POST['date_of_joining'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    $sql = "INSERT INTO employees 
        (name, email, password, role, salary, phone, position, department, date_of_joining, address, status)
        VALUES ('$name', '$email', '$password', '$role', '$salary', '$phone', '$position', '$department', '$date_of_joining', '$address', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location:employees.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
include('../includes/header.php');
?>
<head>
    <style>
main {
    padding: 40px;
    background: #f5f6fa;
    display: grid;
    margin: 0 auto;
    max-width: 800px;
    
}
h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 10px;
   color: #2c3e50;
}
hr{
    border: 1px solid #ddd;
    margin-bottom: 20px;
}
form {
    max-width: 700px;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}
form label {
    font-size: 15px;
    color: #333;
    font-weight: 500;
    display: block;
}
form input,
form select,
form textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #d0d0d0;
    border-radius: 6px;
    font-size: 15px;
    outline: none;
    transition: 0.2s;
}

form textarea{
    height: 40px;
}

form button {
    background: #4a90e2;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 10px;
    width: 200px;
    float: right;
}

form button:hover {
    background: #357ABD;
}
.back  {
    background-color: #444;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    float: right;
    font-size: 15px;
    text-decoration: none;
    margin-right: 5px;
}

.back :hover {
    color: #000;
    
}


    </style>
</head>
<main>
<form method="POST">
    <h3>Add New Employee</h3>
    <hr>

    <label>Name</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="text" name="password" required><br><br>

    <label>Role</label><br>
    <select name="role">
    <option value="0">Employee</option>
    <option value="1">Admin</option>
    <option value="2">HR</option>
    <option value="3">Manager</option>
</select><br><br>

    <label>Salary</label><br>
    <input type="number" name="salary" required><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Position</label><br>
    <input type="text" name="position" required><br><br>

    <label>Department</label><br>
    <input type="text" name="department" required><br><br>

    <label>Date of Joining</label><br>
    <input type="date" name="date_of_joining" required><br><br>

    <label>Address</label><br>
    <textarea name="address" rows="3" required></textarea><br><br>

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
