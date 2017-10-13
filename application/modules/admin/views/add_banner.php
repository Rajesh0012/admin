
<body>
<div class="wrapper">
    <?php require_once ('sidebar.php');?>
    <!-- side-menubar end -->

    <!-- center section -->
    <div class="center-section">
        <!-- header -->
        <?php require_once ('common-header.php');?>
        <!-- header end  -->
        <div class="center-section-inner">
            <div class="dashboard-wrapper">
                <div class="row">

                    <h1 style="color:white">Contact Us</h1>
                </div>
                <div class="container">

                    <form class="form-horizontal" method="post" >
                        <?php
                        if(isset($editdata)){
                            foreach($editdata as $values){}
                        }
                        ?>
                        <span class="error"><?= isset($msg)?$msg:''; ?></span>
                        <input type="hidden" name="<?= $csrfname ?>" value="<?= $csrfvalue ?>">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Title:</label>
                            <div class="col-sm-8">
                                <input type="text" value="<?= isset($values->title)?$values->title:''; ?>" name="title" class="form-control" id="email" placeholder="Enter email">
                                <?php echo form_error('title', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Description:</label>
                            <div class="col-sm-8">
                                <textarea name="description"  rows="15" cols="50"><?= isset($values->description)?$values->description:''; ?></textarea>
                                <?php echo form_error('description', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Meta Title:</label>
                            <div class="col-sm-8">
                                <input type="text" name="meta_title" value="<?= isset($values->meta_title)?$values->meta_title:''; ?>" class="form-control" id="email" placeholder="Enter email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Meta Description:</label>
                            <div class="col-sm-8">
                                <input type="text" name="meta_description" value="<?= isset($values->meta_description)?$values->meta_description:''; ?>" class="form-control" id="email" placeholder="Enter email">
                            </div>
                        </div>  <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Meta Keywords:</label>
                            <div class="col-sm-8">
                                <input type="text" name="meta_keywords" value="<?= isset($values->meta_keywords)?$values->meta_keywords:''; ?>" class="form-control" id="email" placeholder="Enter email" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        CKEDITOR.replace( 'description' );
                    </script>
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