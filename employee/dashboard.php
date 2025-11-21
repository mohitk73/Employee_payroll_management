<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 0){
    header("Location: ../admin/login.php");
    exit;
}
?>
<h1>Employee Dashboard</h1>

<a href="profile.php">View Profile</a><br>
<a href="salary.php">View Salary</a><br>
<a href="../admin/logout.php">Logout</a>
