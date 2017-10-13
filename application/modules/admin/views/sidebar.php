<aside class="side-menubar">
    <div class="wrapper-logo">
        <img src="<?= site_url();?>assets/images/log.png" alt="">
    </div>
    <div class="user-detail-aside">
        <div class="inner">
            <figure style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-7GceZVhNLpqgqYsaFjgpcAcjobWWMZ_x8UpxCC9mpFTe2d_L')"></figure>
            <span>John Smith </span>
        </div>

    </div>
    <div class="menubar-wrapper">

        <ul>
        <li><?= anchor(site_url().'admin/admin_Controller/dashboard','Dashboard',$this->uri->segment(3, 0) === 'dashboard'?'class="active"':''); ?></li>
        <li><?= anchor(site_url().'admin/Cms_pageControlller/page_list','CMS Pages',$this->uri->segment(3, 0) === 'page_list'?'class="active"':''); ?></li>
            <li><?= anchor(site_url().'admin/Product_Controller/products','Products',$this->uri->segment(3, 0) === 'products'?'class="active"':''); ?></li>
            <li><?= anchor(site_url().'admin/Users_Controller/get_user','User Management',$this->uri->segment(3, 0) === 'get_user'?'class="active"':''); ?></li>
            <li><?= anchor(site_url().'admin/Subscription_Controller/subscription_list','Subscription',$this->uri->segment(3, 0) === 'subscription_list'?'class="active"':''); ?></li>
        <!--<li><?/*= anchor(site_url().'Welcome/Qepr_admin/notification','Send Notification',$this->uri->segment(3, 0)==='notification'?'class="active"':''); */?></li>-->
         <li><?= anchor(site_url().'admin/admin_Controller/change_password_vai_admin','Change Password',$this->uri->segment(3, 0) === 'change_password_vai_admin'?'class="active"':''); ?></li>
         <li><?= anchor(site_url().'admin/admin_Controller/change_email','Change Email',$this->uri->segment(3, 0) === 'change_email'?'class="active"':''); ?></li>
         <li><?= anchor(site_url().'admin/admin_Controller/change_name','Change Name',$this->uri->segment(3, 0) === 'change_name'?'class="active"':''); ?></li>

    </ul>
    </div>
</aside>

<div class="center-section">
    <!-- header -->
