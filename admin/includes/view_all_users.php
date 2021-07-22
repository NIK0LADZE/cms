<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/users.php");
$users = new Users();
$users->perPage();
$users->setRole();
$users->delete();
if(isset($users->startPostsFrom)) { ?>
    <form action="" method="post">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Date of birth</th>
                    <th>Email</th>
                    <th style="width: 0;">Image</th>
                    <th colspan=3>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php $users->display(); ?>
            </tbody>
        </table>
    </form>
    <!-- Pager  -->
    <?php pager("users.php?", $users->page, $users->pagerCount);
} ?>