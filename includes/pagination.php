<?php 
?>
<head>
    <link rel="stylesheet" href="../assets/css/pagination.css">
</head>

        <div class="pagination">
            <nav>
                <ul>
                    <?php if ($page > 1):  ?>
                        <li><a href="?month=<?= $month ?>&page=<?= $page - 1 ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalpages; $i++): ?>
                        <li class="<?= ($i == $page) ? 'active' : '' ?>"><a href="?month=<?= $month ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalpages): ?>
                        <li><a href="?month=<?= $month ?>&page=<?= $page + 1 ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>