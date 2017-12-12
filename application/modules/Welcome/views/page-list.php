


<body>
<div class="wrapper">
    <aside class="side-menubar">


        <?php require_once ('sidebar.php');?>
    </aside>
    <!-- side-menubar end -->
    <?php require_once ('common-header.php');?>
    <!-- header end  -->
    <div class="center-section-inner">

            <h1 class="heading">Page List</h1>
            <h1 style="color: darkgreen; margin-bottom: 5px"><?= $this->session->flashdata('updated');?></h1>
            <div class="clearfix"></div>
            <div class="tabel-wrapper clearfix">
                <table class="table outlet-table table-striped table-hover">
                    <thead>
                    <tr>

                        <th width="120">ID</th>
                        <th> Title </th>
                        <th> Description </th>


                        <th width="80"> Action </th>
                    </tr>
                    </thead>
                    <tbody>


                    <?php foreach($list as $contact_list): ?>

                        <tr>

                            <td>
                                1
                            </td>
                            <td>
                                <?php echo ucwords('privacy policy'); ?>
                            </td>
                            <td>
                                <?php echo $contact_list->privacy_policy; ?>
                            </td>

                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Qepr_admin/cms_pages?id=privacy_policy','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr><tr>

                            <td>
                                2
                            </td>
                            <td>
                                <?php echo ucwords('contact us'); ?>
                            </td>
                            <td>
                                <?php echo $contact_list->contact_us; ?>
                            </td>

                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Qepr_admin/cms_pages?id=contact_us','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr><tr>

                            <td>
                                3
                            </td>
                            <td>
                                <?php echo ucwords('faq'); ?>
                            </td>
                            <td>
                                <?php echo $contact_list->faq; ?>
                            </td>

                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Qepr_admin/cms_pages?id=faq','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr><tr>

                            <td>
                                4
                            </td>
                            <td>
                                <?php echo ucwords('term and condition'); ?>
                            </td>
                            <td>
                                <?php echo $contact_list->term_and_condition; ?>
                            </td>

                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Qepr_admin/cms_pages?id=term_and_condition','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr><tr>

                            <td>
                                5
                            </td>
                            <td>
                                <?php echo ucwords('about us'); ?>
                            </td>
                            <td>
                                <?php echo $contact_list->about_us; ?>
                            </td>

                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Qepr_admin/cms_pages?id=about_us','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr><tr>

                            <td>
                                6
                            </td>
                            <td>
                                <?php echo ucwords('return policy'); ?>
                            </td>
                            <td>
                                <?php echo $contact_list->return_policy; ?>
                            </td>

                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Qepr_admin/cms_pages?id=return_policy','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>



                    </tbody>
                </table>
            </div>

        <!-- center section end-->
    </div>
</div>
<!-- center section end-->
</div>
<!-- wrapper end -->
</body>
<body>
<div class="wrapper">
    <?php require_once ('sidebar.php');?>
    <!-- side-menubar end -->

    <!-- center section -->
    <div class="center-section">
        <!-- header -->
        <?php require_once ('common-header.php');?>
        <!-- header end  -->

    <!-- wrapper end -->
</body>
<!-- library js -->


</html>

</div>