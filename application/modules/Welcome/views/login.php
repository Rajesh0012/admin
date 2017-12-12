

<body>
<div class="body-wrapper">
    <div class="form-wrapper">
        <div class="login-wrapper">
            <div class="logo-wrapper"><img src="<?= site_url();?>asset/images/logo.png" alt="logo"></div>
            <form method="post" id="login" class="login-form">

                <span style="color: red"><?= isset($msg)?$msg:'';?></span><br>
                <input type="hidden" name="<?= $csrfname; ?>" value="<?= $csrfvalue; ?>">
                <span><?= $this->session->flashdata('Logout_Success');?></span>



                <h1 class="heading">Login Panel</h1><br>
                <span style="color: green"><?= $this->session->flashdata('msg'); ?></span><br>
                <div class="input-wrap">
                    <input name="username" type="text" class="inputfield" placeholder="Email Address*" required />
                    <div class="input-addon"><img src="<?= site_url();?>assets/images/email-icon.png" alt=""></div>
                   <?php echo form_error('username','<div class="error">','</div>'); ?>
                </div>
                <div class="input-wrap">
                    <input name="password" type="password" class="inputfield" placeholder="Password*" required />
                    <div class="input-addon"><img src="<?= site_url();?>assets/images/password.png"></div>
                    <?php echo form_error('username','<div class="error">','</div>'); ?>
                </div>

                <?= anchor(site_url().'Welcome/Qepr_admin/senemaildOtp','Forgot password?'); ?>

                <div class="button-wrapper">
                     <button type="submit" class="custom-btn">Login</button>


                </div>
            </form>
        </div>
    </div>
</div>
</body>
