<?php
include "../config/auth.php";
requireRole([1, 2, 3]);
include "../config/db.php";
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn = ($page - 1) * $limit + 1;
$where = [];
if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $where[] = "name LIKE '%$name%'";
}

if (!empty($_GET['department'])) {
    $department = mysqli_real_escape_string($conn, $_GET['department']);
    $where[] = "department = '$department'";
}

if (isset($_GET['status']) && ($_GET['status'] === "1" || $_GET['status'] === "0")) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $where[] = "status = '$status'";
}

$wherestm = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

$counttotal = "SELECT COUNT(*) AS total from employees $wherestm ";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

$sql = "SELECT * FROM employees $wherestm ORDER BY id  LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");
    header("Location:employees.php");
    exit();
}
$roles = [
    0 => "Employee",
    1 => "Admin",
    2 => "HR",
    3 => "Manager"
];
include '../includes/header.php';
?>

<head>
    <link rel="stylesheet"  href="../assets/css/employees.css">
    <link rel="stylesheet" href="../assets/css/pagination.css">
</head>
<main>
    <section>
        <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
            <h2>Manage Employees</h2>
        <?php } else { ?>
            <h2>Employees List</h2>
        <?php } ?>

        <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
            <a class="add" href="addemployee.php">+ Add New Employee</a>
            <a class="add"  href="addsalary.php">+ Add Salary Structure</a><?php } ?>
        <a class="backdashboard" <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) echo ' href="dashboard.php"';
                                    else echo 'href="../manager/managerdashboard.php"'
                                    ?>>
            < Back to Dashboard</a>
                <br><br>

                <form method="GET" class="filter">
               <input type="text" name="name" placeholder="Search by name"
                  value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>">

    <select name="department">
        <option value="">All Departments</option>
        <?php
        $departments = ["HR", "Sales", "IT"]; 
        foreach ($departments as $dept) {
            $selected = (isset($_GET['department']) && $_GET['department'] == $dept) ? "selected" : "";
            echo "<option value='$dept' $selected>$dept</option>";
        }
        ?>
    </select>

    <select name="status">
        <option value="">All Status</option>
        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === "1") ? "selected" : "" ?>>Active</option>
        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === "0") ? "selected" : "" ?>>Inactive</option>
    </select>

    <button type="submit">Filter</button>
</form>

                <table border="1" cellpadding="8" cellspacing="0">
                    <tr>
                        <th>S.no</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
                            <th>Role</th>
                        <?php } ?>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Date of Joining</th>
                        <th>Address</th>
                        <th>Status</th>
                        <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
                            <th>Actions</th>
                        <?php } ?>
                    </tr>
<?php if(mysqli_num_rows($result) > 0){?>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        
                        <tr>
                            <td><?= $sn++ ?></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['email'] ?></td>

                            <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
                                <td><?= $roles[$row['role']] ?? "Unknown"; ?></td>
                            <?php } ?>

                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['position'] ?></td>
                            <td><?= $row['department'] ?></td>
                            <td><?= $row['date_of_joining'] ?></td>
                            <td><?= $row['address'] ?></td>

                            <td>
                                <span class="status-badge <?= $row['status'] == 1 ? 'active' : 'inactive' ?>">
                                    <?= $row['status'] == 1 ? "Active" : "Inactive" ?>
                                </span>
                            </td>

                            <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { ?>
                                <td>
                                    <a href="editemployee.php?id=<?= $row['id'] ?>">Edit</a>
                                    <a class="delete" href="employees.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            <?php } ?>
                        </tr>
                        
                    <?php } ?>
                    <?php } else{?>
                          <tr>
                           <td colspan="12" style="text-align:center;">No Record Found!</td>
                         </tr>
                        <?php }?>
                    

                   

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