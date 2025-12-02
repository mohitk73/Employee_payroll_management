<?php
include '../config/auth.php';
requireRole([1, 2]);
include '../config/db.php';
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$sn=($page-1) *$limit+1;

$counttotal = "SELECT COUNT(*) AS total from queries";
$countcheck = mysqli_query($conn, $counttotal);
$countresult = mysqli_fetch_assoc($countcheck)['total'];
$totalpages = ceil($countresult / $limit);

if (isset($_POST['resolve'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE queries SET status=1 WHERE id='$id'");
}

$query = "SELECT * FROM queries ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$querycheck = mysqli_query($conn, $query);
include '../includes/header.php';

?>

<head>
    <link rel="stylesheet" href="../assets/css/queries.css">
     <link rel="stylesheet" href="../assets/css/pagination.css">
</head>
<main>
    <section>
        <h3>Employee Queries</h3>
        <div>
        </div>
        <div class="employeequeries">
            <div class="querydata">
                <table>
                    <thead>
                        <tr>
                            <TH>S.no</TH>
                            <th>Employeee Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($querycheck)) { ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><?= $row['employee_id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><?= htmlspecialchars($row['message']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td>
                                    <?php if ($row['status'] == 1) { ?>
                                        <span style="color: green; font-weight: bold;">Resolved</span>
                                    <?php } else { ?>
                                        <span style="color: red; font-weight: bold;">Pending</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php if ($row['status'] == 0) { ?>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="resolve">Resolve</button>
                                        </form>
                                    <?php } else { ?>
                                        <button disabled>Resolved</button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                    </thead>
                </table>

            </div>
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
        </div>
    </section>
</main>