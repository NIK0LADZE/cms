<?php

function pager($link, $page, $pagerCount) { ?>
    <ul class="pager">
        <?php if($page != 1) { ?>
            <li class="previous">
                <a href="<?=$link?>page=<?=($page - 1)?>">&larr; Previous</a>
            </li>
        <?php }
        for ($i = $page - 4; $i <= $page + 4; $i++) { 
            if($i > 0 && $i <= $pagerCount) {
                if($i == $page) { ?>
                    <li>
                        <a class="active" href="<?=$link?>page=<?=$i?>"><?=$i?></a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="<?=$link?>page=<?=$i?>"><?=$i?></a>
                    </li>
                <?php }
            } 
        }
        if($page != $pagerCount) {?>
            <li class="next">
                <a href="<?=$link?>page=<?=($page + 1)?>">Next &rarr;</a>
            </li>
        <?php } ?>
    </ul>
<?php }

function displayCategories() {
    openConn();
    global $conn;
    $sql = "SELECT cat_title as title FROM categories";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $categories = $stmt->fetchAll();
    foreach ($categories as $category) {
        echo "<li><a href='category.php?category_title={$category['title']}'>{$category['title']}</a></li>";
    }
    return $conn = 0;
}
?>