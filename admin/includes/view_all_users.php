                        <?php 
                        if(isset($_POST["delete_user"])) {
                            delete("users", $_POST["delete_user"]);
                        }
                        ?>
                        <?php setUserRole($_POST);?>
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
                                    <?php displayUsers();?>
                                </tbody>
                            </table>
                        </form>