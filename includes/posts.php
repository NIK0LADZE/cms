                        <?php foreach ($posts->array as $post) { ?>
                        <h2>
                            <a href="/cms/post.php?post_id=<?=$post["id"]?>"><?=$post["title"]?></a>
                        </h2>
                        <p class="lead">
                            by <a href="/cms/author.php?user_id=<?=$post["user_id"]?>"><?=$post["author"]?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?=date("F d, Y \a\\t H:i A", strtotime($post["date"]))?></p>
                        <hr>
                        <img class="img-responsive" src="/cms/uploads/<?=$post["image"];?>" alt="Post Image">
                        <hr>
                        <p><?=substr($post["content"], 0, 200)."..."?></p>
                        <a class="btn btn-primary" href="post.php?post_id=<?=$post["id"];?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                        <?php } 
                        ?>