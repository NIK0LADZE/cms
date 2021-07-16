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
        echo "<li><a href='/cms/category.php?category_title={$category['title']}'>{$category['title']}</a></li>";
    }
    return $conn = 0;
}

function search($keyword) {
    openConn();
    if(!isset($keyword)) {
        $conn = 0;
        return header("Location: ../");
    }
    global $conn;
    $keyword = explode(" ", $keyword);
    $posts = "SELECT * FROM posts WHERE post_tags LIKE ";
    foreach ($keyword as $key => $value) {
        if($key > 0) {
            $posts .= "OR";
        }
        $posts .= "'%".$value."%'";
    } 
    $posts .= " ORDER BY post_date DESC";
    $count = $conn->query($posts)->rowCount();
    
    if($count == 0) {
        echo "<h2>NO RESULT!</h2>";
        return $conn = 0;
    } else { ?>
        <h1 class="page-header">
            Page Heading
            <small>Secondary Text</small>
        </h1>
    <?php 
    foreach ($conn->query($posts) as $post) { ?>
    <h2>
        <a href="/cms/post.php?post_id=<?php echo $post["post_id"];?>"><?php echo $post["post_title"];?></a>
    </h2>
    <p class="lead">
        by <a href="index.php"><?php echo $post["post_author"];?></a>
    </p>
    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo date("F d, Y \a\\t H:i A", strtotime($post["post_date"]));?></p>
    <hr>
    <img class="img-responsive" src="/cms/uploads/<?php echo $post["post_image"];?>" alt="">
    <hr>
    <p><?php echo substr($post["post_content"], 0, 200)."...";?></p>
    <a class="btn btn-primary" href="/cms/post.php?post_id=<?php echo $post["post_id"];?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
    <hr>
    <?php } 
    $conn = 0;
    }
}
?>