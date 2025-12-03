<main>
<div class="pagination">
    <nav>
        <ul>
            <?php
            $params = $_GET;
            if ($page > 1) {
                $params['page'] = $page - 1;
                echo '<li><a href="?' . http_build_query($params) . '">Previous</a></li>';
            }
            for ($i = 1; $i <= $totalpages; $i++) {
                $params['page'] = $i;
                $active = ($i == $page) ? 'active' : '';
                echo '<li class="' . $active . '"><a href="?' . http_build_query($params) . '" class="' . $active . '">' . $i . '</a></li>';
            }
            if ($page < $totalpages) {
                $params['page'] = $page + 1;
                echo '<li><a href="?' . http_build_query($params) . '">Next</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>
</main>
