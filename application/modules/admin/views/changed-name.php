
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

                <h1 style="color:white">Change Email</h1>
            </div>
            <div class="container">


                <form id="name_form" class="form-horizontal" method="post" >


                    <input type="hidden" name="<?= $csrfname ?>" value="<?= $csrfvalue ?>">
                    <div class="form-group">
                        <span class="error"><?= isset($msg)?$msg:''; ?></span>
                        <label class="control-label col-sm-2" for="email">Name:</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Your Name">

                            <?php echo form_error('name', '<div class="error">', '</div>'); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Update</button>
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

