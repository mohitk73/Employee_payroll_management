<?php
include "../config/auth.php"; 
requireRole([1,2]);
include "../config/db.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn=($page-1) * $limit + 1;

$where = "1=1";
if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $where .= " AND e.name LIKE '%$name%'";
}

if (!empty($_GET['department'])) {
    $department = mysqli_real_escape_string($conn, $_GET['department']);
    $where .= " AND e.department = '$department'";
}


$counttotal = "SELECT COUNT(*) AS total FROM salaries s JOIN employees e ON s.employee_id = e.id WHERE $where";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

$sql = "SELECT s.id, e.id AS emp_id, e.name, e.department, s.basic_salary, s.hra_allowances AS hra, s.deduction AS deductions
        FROM salaries s
        JOIN employees e ON s.employee_id = e.id WHERE $where ORDER BY s.id LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

include('../includes/header.php');
?>

<head>
    <link rel="stylesheet" href="../assets/css/salarystructure.css">
    <link rel="stylesheet" href="../assets/css/pagination.css">
</head>

<main>
    <section>
        <div style="display: flex;justify-content:space-between;align-items:center;">
            <h3>Employees Salary Structure</h3>
            <a class="salary" href="addsalary.php">+ Add Salary Structure</a>
        </div>
      
        <form method="GET" class="filter">
            <input type="text" name="name" placeholder="Search by name"
                value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>" onchange="this.form.submit()">
            <select name="department" onchange="this.form.submit()">
                <option value="">All Departments</option>
                <?php
                $departments = ["HR", "Sales", "IT"]; 
                foreach ($departments as $dept) {
                    $selected = (isset($_GET['department']) && $_GET['department'] == $dept) ? "selected" : "";
                    echo "<option value='$dept' $selected>$dept</option>";
                }
                ?>
            </select>
        </form>
        
        <?php if(isset($msg)) { echo '<p style="color:green;">'.$msg.'</p>'; } ?>

        <table border="1" cellpadding="10">
            <tr>
                <th>S.no</th>
                <th>Employee Id</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Basic Salary</th>
                <th>HRA</th>
                <th>Deductions</th>
                <th>Action</th>
            </tr>
            <?php if(mysqli_num_rows($result) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $sn++ ?></td>
                    <td><?= $row['emp_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= $row['basic_salary'] ?></td>
                    <td><?= $row['hra'] ?></td>
                    <td><?= $row['deductions'] ?></td>
                    <td><a href="updatesalary.php?id=<?= $row['id'] ?>">Update</a></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="8" style="text-align: center;">No Records Found!</td></tr>
            <?php } ?>
        </table>

        <div class="pagination">
            <nav>
                <ul>
                    <?php if ($page > 1): ?>
                        <li><a href="?page=<?= $page - 1 ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalpages; $i++): ?>
                        <li class="<?= ($i == $page) ? 'active' : '' ?>"><a href="?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalpages): ?>
                        <li><a href="?page=<?= $page + 1 ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </section>
</main>
