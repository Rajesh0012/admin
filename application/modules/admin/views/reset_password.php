
<body>
<div class="body-wrapper">
    <div class="form-wrapper">
        <div class="login-wrapper">
            <div class="logo-wrapper"><img src="<?= site_url();?>assets/images/logo.png" alt="logo"></div>
            <form method="post" id="otpmail" class="login-form">
                <h1 class="heading">Forgot Password</h1>
                <p class="error"> <?= !empty($msg)?$msg:'We\'ll send you an email otp to reset your password.'; ?></p>
                <span class="error"><?php echo validation_errors(); ?></span>
                <input type="hidden" name="<?= $csrfname; ?>" value="<?= $csrfvalue; ?>">
                <div class="input-wrap">
                    <input type="email" name="email" class="inputfield" placeholder="Email Address*" required />
                    <div class="input-addon"><img src="<?= site_url();?>assets/images/email-icon.png" alt=""></div>
                    <span class="error-message">email is not correct email is not correct</span>
                </div>
               <?= anchor(site_url().'admin/Admin_Controller','Back','class="pull-right paragph"'); ?>
                <div class="clearfix"></div>
                <div class="button-wrapper">
                    <button type="submit" class="custom-btn" data-toggle="modal" data-target="#forgot-modal">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>


</html>

<!--<div id="forgot-modal" class="modal fade fadeout-modal" role="dialog">
    <div class="modal-dialog create-modal">
        <!-- Modal content-->
        <!--<div class="modal-content">
            <a href="javascript:void(0);" class="close" data-dismiss="modal">
                <img src="images/cross.png" alt="">
            </a>
            <div class="modal-body">
                <p>A reset password link has been sent to your registered Email Id.</p>
            </div>
        </div>-->
        <!-- Modal content- end -->
    </div>
</div>




