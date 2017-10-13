
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

            <div class="clearfix"></div>
            <div class="container">

<?php  foreach ($subscription_list as $key=>$list) { extract($list);} ?>
                    <form id="subscription_form" class="form-horizontal" method="post">


           <span class="error"><?= isset($msg)?ucwords($msg):''; ?></span>

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="table" value="subscription">



    <div class="form-group">
        <label class="control-label col-sm-2">Subscription Name:</label>
        <div class="col-sm-8">
            <input type="text" value="<?php echo isset($subscription_name)?$subscription_name:''; ?>" class="form-control" name="subscription_name">
            <?php echo form_error('subscription_name', '<div class="error">', '</div>'); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Subscription Validity:</label>
        <div class="col-sm-8">

            <select class="form-control" name="subscription_validity">
                <option  value="">--select--</option>

                <option <?php  if(isset($subscription_validity)){ if($subscription_validity == SUBSCRIPTION_VALADITY_WEEKLY){ echo 'selected'; }} ?>  value="<?= SUBSCRIPTION_VALADITY_WEEKLY?>">Weekly</option>
                <option <?php if(isset($subscription_validity)){ if($subscription_validity == SUBSCRIPTION_VALADITY_MONTHLY){ echo 'selected'; }} ?>  value="<?= SUBSCRIPTION_VALADITY_MONTHLY?>">Monthly</option>
                <option  <?php if(isset($subscription_validity)){ if($subscription_validity == SUBSCRIPTION_VALADITY_QUARTERLY){ echo 'selected'; }} ?> value="<?= SUBSCRIPTION_VALADITY_QUARTERLY;?>">Quarterly</option>
                <option <?php if(isset($subscription_validity)){ if($subscription_validity == SUBSCRIPTION_VALADITY_HALFYEARLY){ echo 'selected'; }} ?> value="<?= SUBSCRIPTION_VALADITY_HALFYEARLY?>">Half Yearly</option>
                <option <?php if(isset($subscription_validity)){ if($subscription_validity == SUBSCRIPTION_VALADITY_YEARLY){ echo 'selected'; }} ?> value="<?= SUBSCRIPTION_VALADITY_YEARLY?>"> Annualy</option>
            </select>
            <?php echo form_error('subscription_validity', '<div class="error">', '</div>'); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Subscription Type:</label>
        <div class="col-sm-8">
            <select class="form-control" name="subscription_type">
                <option  value="">--select--</option>
                <option <?php if(isset($subscription_type)){ if($subscription_type == MEMBERSHIP_TYPE_PRO){ echo 'selected'; }} ?>  value="<?= MEMBERSHIP_TYPE_PRO; ?>">Pro Membership</option>
                <option <?php if(isset($subscription_type)){ if($subscription_type == MEMBERSHIP_TYPE_PLATINUM){ echo 'selected'; }} ?> value="<?= MEMBERSHIP_TYPE_PLATINUM;?>">Platinum Membership</option>
                <option <?php if(isset($subscription_type)){ if($subscription_type == MEMBERSHIP_TYPE_NON_PREMIUM){ echo 'selected'; }} ?> value="<?= MEMBERSHIP_TYPE_NON_PREMIUM;?>">Non Premium Membership</option>

            </select>
            <?php echo form_error('subscription_type', '<div class="error">', '</div>'); ?>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2">Price:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" value="<?php echo isset($price)?$price:''; ?>" name="price">
            <?php echo form_error('price', '<div class="error">', '</div>'); ?>
        </div>
    </div>
    <!--<div id="ftype">
        <?php /*if(empty($list)): */?>
        <div class="form-group">
            <label class="control-label col-sm-2">Features Type#1 :</label>
            <div class="col-sm-6">

                <input type="text" value="<?php /*echo isset($fvalues['feature'])?$fvalues['feature']:''; */?>" class="form-control" name="features_type[]">

                <?php /*echo form_error('features_type[]', '<div class="error">', '</div>'); */?>
            </div>
            <a id="ftypeadd" href="javacript:void(0)" class="btn btn-primary col-sm-2">Add+</a>
        </div>
    <?php /*endif;*/?>
    <?php /*if(isset($list)): $i=0; foreach ($list['features'] as $fvalues):  */?>
        <?php /*if($fvalues['feature_type'] == 2): ++$i; */?>
            <?php /*if($i == 1): */?>
            <div class="form-group">
                <label class="control-label col-sm-2">Features Type#1 :</label>
                <div class="col-sm-6">

                    <input type="text" value="<?php /*echo isset($fvalues['feature'])?$fvalues['feature']:''; */?>" class="form-control" name="features_type[]">

                    <?php /*echo form_error('features_type[]', '<div class="error">', '</div>'); */?>
                </div>

                <a id="ftypeadd" href="javacript:void(0)" class="btn btn-primary col-sm-2">Add+</a>
            </div>
            <?php /*else: */?>

                <div id="ftremov<?php /*echo $i; */?>" class="form-group">
                    <label class="control-label col-sm-2">Features Type#<?php /*echo $i; */?>:</label>
                    <div class="col-sm-6">
                        <input type="text" value="<?php /*echo isset($fvalues['feature'])?$fvalues['feature']:''; */?>" class="form-control" name="features_type[]">
                    </div>
                    <a id="<?php /*echo $i; */?>" onclick="removeftbox(this.id)" href="javacript:void(0)">Remove-</a>
                </div>


                <?php /*endif; */?>

            <?php /*endif; */?>
     <?php /*endforeach;  */?>
        <?php /*endif; */?>

            </div>-->
    <div id="append">
        <?php if(empty($list)): ?>
        <div class="form-group">
            <label class="control-label col-sm-2">Features#1 :</label>
            <div class="col-sm-6">

                <input type="text" value="<?php echo isset($fvalues['feature'])?$fvalues['feature']:''; ?>"  class="form-control" name="features[]">

                <?php echo form_error('features[]', '<div class="error">', '</div>'); ?>
            </div>

            <a id="add" href="javacript:void(0)" class="btn btn-primary col-sm-2">Add+</a>

        </div>
        <?php endif; ?>
        <?php if(isset($list)): $k=0; foreach ($list['features'] as $fvalues):  ?>
        <?php if($fvalues['feature_type'] == 1): ++$k; ?>
                <?php if($k == 1): ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Features#1 :</label>
                <div class="col-sm-6">

                    <input type="text" value="<?php echo isset($fvalues['feature'])?$fvalues['feature']:''; ?>"  class="form-control" name="features[]">

                    <?php echo form_error('features[]', '<div class="error">', '</div>'); ?>
                </div>

                    <a id="add" href="javacript:void(0)" class="btn btn-primary col-sm-2">Add+</a>
            </div>
                    <?php else: ?>
            <div id="remov<?php echo $k; ?>" class="form-group">
                <label class="control-label col-sm-2">Features#<?php echo $k; ?>:</label>
                <div class="col-sm-6">
                    <input value="<?php echo isset($fvalues['feature'])?$fvalues['feature']:''; ?>" type="text" class="form-control" name="features[]">
                </div>
                <a  id="<?php echo $k; ?>" onclick="removebox(this.id)" href="javacript:void(0)">Remove-</a>
            </div>


                <?php endif; ?>

            <?php endif; ?>
        <?php endforeach;  ?>
        <?php endif; ?>

    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php if(!empty($this->uri->segment(4))): ?>
            <button  type="submit" class="btn btn-default">Update</button>
                <?php else: ?>
                <button  type="submit" class="btn btn-default">Submit</button>
            <?php endif; ?>
        </div>
    </div>



</form>

</div>
</div>

    </div>
</div>

<!-- wrapper end -->
</body>
<!-- library js -->



</html>

</div>



