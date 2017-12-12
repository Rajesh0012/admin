
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
            <h1 class="heading">About Us</h1>
            <h1 style="color: darkgreen; margin-bottom: 5px"><?= $this->session->flashdata('updated');?></h1>
            <div class="clearfix"></div>
            <div class="tabel-wrapper clearfix">
                <table class="table outlet-table table-striped table-hover">
                    <thead>
                    <tr>

                        <th width="120">ID</th>
                        <th> Title </th>
                        <th> Description </th>
                        <th> Meta Title </th>
                        <th> Meta Description </th>
                        <th> Meta Keywords </th>

                        <th width="80"> Action </th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach($list as $contact_list){

                        ?>

                        <tr>

                            <td><?= $contact_list->id; ?></td>
                            <td><?= $contact_list->title; ?></td>
                            <td><?= substr($contact_list->description,0,40); ?></td>
                            <td><?= $contact_list->meta_title; ?></td>
                            <td><?= $contact_list->meta_description; ?></td>
                            <td><?= $contact_list->meta_keywords; ?></td>
                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'Welcome/Stellarclubs/aboutus?id='.$contact_list->id,'<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr>
                    <?php }

                    ?>



                    </tbody>
                </table>
            </div>
        </div>
        <!-- center section end-->
    </div>
    <!-- wrapper end -->
</body>
<!-- library js -->


</html>

</div>