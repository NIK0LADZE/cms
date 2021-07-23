<?php 
require_once("../database/posts.php");
$posts = new Posts();
$posts->delete();
$posts->clone();
$posts->perPage();
?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Title</th>
            <th>Category</th>
            <th style="width: 0;">Image</th>
            <th>Tags</th>
            <th>Views</th>
            <th>Comments</th>
            <th colspan=4>Date</th>
        </tr>
    </thead>
    <tbody>
    <?php $posts->table();?>
    </tbody>
</table>
<!-- Pager  -->
<?php pager("posts.php?action=".$_GET["action"]."&", $posts->page, $posts->pagerCount);?>