<body>
<div class="body-wrapper">
    <div class="form-wrapper">
        <div class="login-wrapper">
            <div class="logo-wrapper"><img src="<?= site_url();?>assets/images/logo.png" alt="logo"></div>
            <form method="post" id="save_pass" class="login-form">

                <input type="hidden" name="<?= $csrfname; ?>" value="<?= $csrfvalue; ?>"/>
                <span class="error"><?= isset($msg)?$msg:'';?></span><br>
                <span><?= $this->session->flashdata('msg') !== ''?'':''; ?></span><br>
                <h1 class="heading">Reset Password</h1>
                <div class="input-wrap">
                    <input type="password" name="otp" class="inputfield" placeholder="Enter Otp*" required />
                    <div class="input-addon"><img src="<?= site_url();?>assets/images/password.png" alt=""></div>
                    <?php echo form_error('otp','<div class="error">','</div>'); ?>
                </div>
                <div class="input-wrap">
                    <input id="password" type="password" name="password" class="inputfield" placeholder="New Password*" required />
                    <div class="input-addon"><img src="<?= site_url();?>assets/images/password.png" alt=""></div>
                    <?php echo form_error('password','<div class="error">','</div>'); ?>
                </div>
                <div class="input-wrap">
                    <input name="passconf" type="password" class="inputfield" placeholder="Confirm New Password*" required />
                    <div class="input-addon"><img src="<?= site_url();?>assets/images/password.png" alt=""></div>
                    <?php echo form_error('passconf','<div class="error">','</div>'); ?>
                </div>
                <a href="<?= site_url(); ?>/Welcome/Stellarclubs/senemaildOtp" class="pull-right paragph">Resend Otp</a>
                <div class="clearfix"></div>
                <div class="button-wrapper">
                    <button type="submit" class="custom-btn">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>
</body>

