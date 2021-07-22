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

?>