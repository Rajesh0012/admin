
<body>
<div class="wrapper">
    <aside class="side-menubar">


        <?php require_once ('sidebar.php');?>
    </aside>
        <!-- header -->
        <?php require_once ('common-header.php');?>
        <!-- header end  -->
        <div class="center-section-inner">
            <div class="dashboard-wrapper">
                <div class="row">

                    <h1 style="color:white">Change Password</h1>
                </div>
                <div class="container">


                    <form id="change-admin-password" class="form-horizontal" method="post" >


                        <input type="hidden" name="<?= $csrfname ?>" value="<?= $csrfvalue ?>">
                        <div class="form-group">
                            <span class="error"><?= isset($msg)?$msg:''; ?></span>
                            <label class="control-label col-sm-2" for="email">Old Password:</label>
                            <div class="col-sm-8">
                                <input type="text" name="old_password" class="form-control" id="email" placeholder="Enter email">

                                <?php echo form_error('old_password', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">New Password:</label>
                            <div class="col-sm-8">
                                <input type="text" name="password" class="form-control" id="email" placeholder="Enter email" name="email">
                                <?php echo form_error('password', '<div class="error">', '</div>'); ?>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Confirm New Password:</label>
                            <div class="col-sm-8">
                                <input type="text" name="passconf" class="form-control" id="passconf" placeholder="Enter email" name="email">
                                <?php echo form_error('passconf', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- center section end-->
</div>
<!-- wrapper end -->
</body>
<!-- library js -->


</html>

</div>

