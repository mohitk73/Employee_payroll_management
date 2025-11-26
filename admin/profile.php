<?php 
include "../config/auth.php"; 
requireRole([0,1,2,3]);
include "../config/db.php";
include '../includes/header.php';

$userid=$_SESSION['user_id'];
$profile="SELECT * FROM employees where id='$userid'";
$profilecheck=mysqli_query($conn,$profile);
$profiledetails=mysqli_fetch_assoc($profilecheck);
$roles = [
    0 => "Employee",
    1 => "Admin",
    2 => "HR",
    3 => "Manager"
];
$status = [
    0 => "Inactive",
    1 => "Active"
];
$statusText = $status[$profiledetails['status']];
$statusClass = ($profiledetails['status'] == 1) ? "active" : "inactive";
?>
<head>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<main>
    <section>
        <div class="heading">
            <h3>Profile Details</h3>
            <?php if($_SESSION['role']==1){
                 $dashboard = "../admin/dashboard.php";
            }
                 else if($_SESSION['role']==2){
                    $dashboard="../hr/hrdashboard.php";
                 } 
                 else if($_SESSION['role']==3){
                    $dashboard="../manager/managerdashboard.php";
                 }
                 else {
                    $dashboard="../employee/dashboard.php";
                 }
             ?>
<a class="edit" href="<?= $dashboard ?>"><-Back to Dashboard</a>
        </div>
<div class="profile-details">
    <div>
        <h4>Employee-id</h4>
        <p><?php echo $_SESSION['user_id'] ?></p>
    </div>
     <div>
        <h4>Name</h4>
        <p><?php echo $_SESSION['name'] ?></p>
    </div>
    <div>
        <h4>Email</h4>
        <p><?= $profiledetails['email'] ?></p>
    </div>
    <div>
        <h4>Position</h4>
        <p><?= $profiledetails['position'] ?></p>
    </div>
    <div>
        <h4>Role</h4>
        <p><?= $roles[$profiledetails['role'] ]?></p>
    </div>

     <div>
        <h4>Date of Joining</h4>
       <p><?= $profiledetails['date_of_joining'] ?></p>
    </div>
     <div>
        <h4>Created At</h4>
       <p><?= $profiledetails['created_at'] ?></p>
    </div>
    <div>
        <h4>Status</h4>
      <h6 class="<?= $statusClass ?>"><?= $statusText ?></h6>
    </div>
</div>
<div>
</div>

<div class="contact-head">
            <h3>Contact Details</h3>
        </div>
<div class="profile-details">
    <div>
        <h4>Email</h4>
        <p><?= $profiledetails['email'] ?></p>
    </div>
     <div>
        <h4>Mobile Number</h4>
       <p><?= $profiledetails['phone'] ?></p>
    </div>
    <div>
        <h4>Address</h4>
        <p><?= $profiledetails['address'] ?></p>
    </div>
</div>
<div>
</div>


<div class="account-head">
            <h3>Account Details</h3>
        </div>
<div class="profile-details">
    <div>
        <h4>Account Number</h4>
        <p>567465876898</p>
    </div>
     <div>
        <h4>IFSC code</h4>
        <p>axis45677</p>
    </div>
    <div>
        <h4>Bank Name</h4>
        <p>Axis bank</p>
    </div>
</div>
<div>
</div>
    </section>
</main>