<?php
include "../config/auth.php"; 
requireRole([1,2]);
include "../config/db.php";
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn=($page-1) *$limit+1;
$where = 1;
if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $where = "name LIKE '%$name%'";
}
$counttotal = "SELECT COUNT(*) AS total from salaries s JOIN employees e ON s.employee_id=e.id  WHERE $where ";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

$sql = "SELECT s.id,e.id, e.name, s.basic_salary, s.hra_allowances AS hra, s.deduction AS deductions
        FROM salaries s
        JOIN employees e ON s.employee_id = e.id WHERE $where ORDER BY s.id  LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

include('../includes/header.php');
?>
<head>
    <link rel="stylesheet" href="../assets/css/salarystructure.css" >
    <link rel="stylesheet" href="../assets/css/pagination.css" >
</head>
<main>
    <section>
        <div style="display: flex;justify-content:space-between;align-items:center;">
              <h3>Employees Salary Structure</h3>
            <a class="salary" href="addsalary.php">+ Add Salary Structure</a>
        </div>
      
<form method="GET" class="filter">
               <input type="text" name="name" placeholder="Search by name"
                  value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>">
    <button type="submit">Filter</button>
</form>
<?php if(isset($msg)) { echo '<p style="color:green;">'.$msg.'</p>'; } ?>

<table border="1" cellpadding="10">
    <tr>
        <th>S.no</th>
        <th>Employee Id</th>
        <th>Employee Name</th>
        <th>Basic Salary</th>
        <th>HRA</th>
        <th>Deductions</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)){ ?>
    <tr>
        <form method="post" action="">
            <td><?= $sn++ ?></td>
            <td><?= $row['id'] ?></td>
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
<div class="pagination">
            <nav>
                <ul>
                    <?php if ($page > 1):  ?>
                        <li><a href="?page=<?= $page - 1 ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalpages; $i++): ?>
                        <li class="<?= ($i == $page) ? 'active' : '' ?>"><a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalpages): ?>
                        <li><a href="?page=<?= $page + 1 ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

    </section>
</main>

