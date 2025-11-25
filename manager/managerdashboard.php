<?php
include "../config/auth.php"; 
requireRole([3]);             

include "../includes/header.php";  
?>
<main>
    <h1>Manager Dashboard</h1>

    <a href="profile.php">View Profile</a><br>
    <a href="salary.php">View Salary</a><br>
    <a href="../admin/logout.php">Logout</a>
</main>
