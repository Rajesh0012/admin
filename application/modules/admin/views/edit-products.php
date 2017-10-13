
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

            <span class="error"><?= isset($msg)?ucwords($msg):''; ?></span>
            <span class="error"><?= $this->session->flashdata('wrong_format'); ?></span><br>
            <span class="error"><?= $this->session->flashdata('update_failled'); ?></span>
            <span class="error"><?= $this->session->flashdata('large_file'); ?></span>
            <span class="error"><?= $this->session->flashdata('threshold'); ?></span>
            <div class="clearfix"></div>
            <div class="container">
         <form enctype="multipart/form-data" id="product_form" class="form-horizontal" method="post" >

                    <?php


                    if(!empty($products)){  foreach ($products as $key=>$values); } ?>


                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="table" value="subscription">



                    <div class="form-group">
                        <label class="control-label col-sm-2">Products Name:</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?= isset($values->name)?$values->name:''; ?>" class="form-control" name="name">
                            <?php echo form_error('name', '<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Products Price:</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?= isset($values->price)?$values->price:''; ?>" class="form-control" name="price">
                            <?php echo form_error('price', '<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Membership Type:</label>
                        <div class="col-sm-8">
                            <select name="membership_type" class="form-control">
                            <option value="<?= MEMBERSHIP_TYPE_NON_PREMIUM; ?>" <?= $values->membership_type == MEMBERSHIP_TYPE_NON_PREMIUM?'selected':''; ?>>Non Premium Memeber</option>
                            <option value="<?= MEMBERSHIP_TYPE_PRO; ?>" <?= $values->membership_type == MEMBERSHIP_TYPE_PRO?'selected':''; ?>>Pro Member</option>
                            <option value="<?= MEMBERSHIP_TYPE_PLATINUM; ?>" <?= $values->membership_type == MEMBERSHIP_TYPE_PLATINUM?'selected':''; ?>>Platinum Member</option>
                            <?php echo form_error('membership_type', '<div class="error">', '</div>'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Products Type:</label>
                        <div class="col-sm-8">
                            <select name="product_type" class="form-control">
                                <option value="<?= PRODUCT_KEYFOB; ?>" <?= $values->product_type == PRODUCT_KEYFOB?'selected':''; ?>>Key Fob</option>
                                <option value="<?= PRODUCT_ORIGINAL_TAG; ?>" <?= $values->product_type == PRODUCT_ORIGINAL_TAG?'selected':''; ?>>Original Tag</option>
                                <option value="<?= PRODUCT_CUSTOM_TAG ?>" <?= $values->product_type == PRODUCT_CUSTOM_TAG?'selected':''; ?>>Custom Tag</option>
                                <option value="<?= PRODUCT_OTHER; ?>" <?= $values->product_type == PRODUCT_OTHER?'selected':''; ?>>Other</option>
                                <?php echo form_error('product_type', '<div class="error">', '</div>'); ?>
                            </select>
                           <?php echo form_error('product_type', '<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Images:</label>
                        <div class="col-sm-8">
                            <input type="file" multiple  name="product_image[]">

                            <?php foreach ($values->image as $img) : ?>
                                <input type="hidden" name="not_updated_image[]" value="<?= $img;?>">
                                <img alt="Image NOt Found" width="100px" src="<?= $img;?>" />
                            <?php endforeach; ?>
                            <?php echo form_error('images', '<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Description:</label>
                        <div class="col-sm-8">
                            <textarea name="description"  rows="15" cols="50"><?= isset($values->description)?$values->description:''; ?></textarea>
                            <?php echo form_error('description', '<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                <script>
                    CKEDITOR.replace( 'description' );
                </script>

                 <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button  type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>



                </form>


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



