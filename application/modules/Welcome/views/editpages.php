
<body>
<div>
    <div class="wrapper">
    <aside class="side-menubar">


        <?php require_once ('sidebar.php');?>
    </aside>
    <!-- side-menubar end -->
    <?php require_once ('common-header.php');?>
    <!-- header end  -->
    <div class="center-section-inner">
        <h1 class="heading"><?= isset($eid)?str_replace('-',' ',$eid):''; ?></h1>
        <div class="clearfix"></div>
     <div class="container">
                <?php foreach($editdata as $values): ?>
                    <form class="form-horizontal" method="post" >


                        <span class="error"><?= isset($msg)?ucwords($msg):''; ?></span>
                        <input type="hidden" name="<?= $csrfname ?>" value="<?= $csrfvalue ?>">
                        <input type="hidden" name="thiscolomn" value="<?= $eid ?>">

                        <div class="form-group">
                            <label class="control-label col-sm-2">Description:</label>
                            <div class="col-sm-8">
                                <textarea name="description"  rows="15" cols="50"><?= isset($values->$eid)?$values->$eid:''; ?></textarea>
                                <?php echo form_error('description', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button  type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </div>

                    </form>
                <?php endforeach; ?>
                <script>
                    CKEDITOR.replace( 'description' );
                </script>
            </div>
        </div>
        <!-- pagination -->


        <!-- pagination end -->

    </div>
</div>

<!-- wrapper end -->
</body>
<!-- library js -->



</html>

</div>



