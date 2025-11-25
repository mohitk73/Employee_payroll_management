<?php
include "../config/db.php";
include "../config/auth.php"; 
requireRole([1,2,3]);  
$date = date("Y-m-d"); 
if (isset($_GET['emp_id']) && isset($_GET['status'])) {
    $emp_id = $_GET['emp_id'];
    $status = $_GET['status'];
    $date = date("Y-m-d");
    $check = mysqli_query($conn,
        "SELECT id FROM attendance WHERE employee_id='$emp_id' AND date='$date'"
    );

    if (mysqli_num_rows($check) > 0) {
        $_SESSION['msg'] = "Attendance already marked!";
    } else {
        mysqli_query($conn,
            "INSERT INTO attendance (employee_id, date, status)
            VALUES ('$emp_id', '$date', '$status')"
        );
        $_SESSION['msg'] = "Attendance saved successfully!";
    }

    header("Location: attendance.php");
    exit();
}
$employees = mysqli_query($conn, "SELECT * FROM employees ORDER BY name");

$today = mysqli_query($conn, "
    SELECT e.id, e.name, e.position, a.status 
    FROM employees e
    LEFT JOIN attendance a 
        ON e.id = a.employee_id AND a.date = '$date'
    ORDER BY e.name
");

include('../includes/header.php');


?>
<head>
    <link rel="stylesheet" href="../assets/css/attendance.css">
</head>
<main>
<section>
<h3>Attendance – <?= $date ?></h3>

<div class="attendance-filter">
    <a href="?filter=all" class="btn">All</a>
    <a href="?filter=present" class="btn green">Present</a>
    <a href="?filter=absent" class="btn red">Absent</a>
</div>

<table class="attendance-table">
    <tr>
        <th>Employee Id</th>
        <th>Name</th>
        <th>Position</th>
        <th>Status</th>
          <?php if($_SESSION['role'] ==1 || $_SESSION['role'] == 2) {?><th>Action</th><?php }?>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($today)) { 
        
        if (isset($_GET['filter'])) {
            if ($_GET['filter'] == "present" && $row['status'] != 1){
                 continue;
            }
            if ($_GET['filter'] == "absent" && $row['status'] != 0) 
                {continue;
                }
        }
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['position'] ?></td>

        <td>
            <?php if ($row['status'] === NULL) { ?>
                <span class="badge gray">Not Marked</span>
            <?php } elseif ($row['status'] == 1) { ?>
                <span class="badge green">Present</span>
            <?php } else { ?>
                <span class="badge red">Absent</span>
            <?php } ?>
        </td>
 <?php if($_SESSION['role'] ==1 || $_SESSION['role'] == 2){?>
     <td>
       
    <?php if ($row['status'] === NULL) { ?>
        <a href="attendance.php?emp_id=<?= $row['id'] ?>&status=1"
           class="btn green">
           Present
        </a>

        <a href="attendance.php?emp_id=<?= $row['id'] ?>&status=0"
           class="btn red">
           Absent
        </a>

    <?php } else { ?>
        <span class="badge gray">Already Marked</span>
    <?php } ?>
    
</td>
 <?php } ?>
    </tr>
   
    <?php } ?>

</table>
</section>
</main>
<script>
function disableButton(btn) {
    btn.textContent = "Marked...";
    btn.style.opacity = "0.5";
    btn.style.pointerEvents = "none"; 
}
</script>

