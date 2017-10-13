
<body>
<div class="wrapper">
    <aside class="side-menubar">


       <?php require_once ('sidebar.php');?>
    </aside>
    <!-- side-menubar end -->
<?php require_once ('common-header.php');?>
    <script src="https://www.w3schools.com/lib/w3.js"></script>
        <!-- header end  -->
        <div class="center-section-inner">
            <h1 class="heading">User Management</h1>
            <div class="clearfix"></div>
            <div class="tabel-wrapper clearfix">
                <div><?php echo form_error();  ?></div>
                <div>Total Available Users:<?= isset($available_users)?$available_users:''; ?></div>
                <table id="myTable" class="table outlet-table table-striped table-hover">
                    <thead>
                    <tr>

                        <th width="120">S No.</th>
                        <th onclick="w3.sortHTML('#myTable','.item', 'td:nth-child(2)')"> Name </th>
                        <th> Email </th>
                        <th> Phone Number </th>
                        <th onclick="w3.sortHTML('#myTable','.item', 'td:nth-child(5)')" > Registered on </th>
                        <th> Status </th>
                        <th> Total Purchase </th>
                        <th width="80"> Subscribed </th>
                        <th width="80"> Locate </th>
                    </tr>
                    </thead>
                    <tbody id="searchdata">

                    <?php if(!empty($userlist)) :?>
                        <?php  $sno=$page ;  foreach($userlist as $users): ?>
                        <tr class="item">

                            <td><?= ++$sno; ?></td>
                            <td><?php echo anchor(site_url().'admin/Users_Controller/view_user_details/'.$users->id,isset($users->name)?$users->name:'') ?></td>
                            <td><?= isset($users->email)?$users->email:''; ?></td>
                            <td><?= isset($users->mobile_number)?$users->mobile_number:''; ?></td>
                            <td  ><?= isset($users->created)?$users->created:''; ?></td>
                            <td  ><?php if(isset($users->status))
                            {
                                if($users->status == USER_ACTIVE )
                                {
                                    echo '<span style="color:green">Active</span>';
                                } elseif($users->status == USER_BLOCK) {
                                    echo '<span style="color:red">BLocked</span>';
                                }else{ echo 'deleted';}
                            } ?></td>
                            <td><?= isset($users->subscription_id)?$users->subscription_id:''; ?></td>
                            <td><?php if(isset($users->subscription_id)){ if(trim($users->subscription_id) == USER_NOT_SUBSCRIBED){ echo 'No Subscription';}else{ echo '<span style="color:green">Subscribed</span>';}} ?></td>
                            <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'admin/Users_Controller/get_user/','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">No Records Found</td>
                            </tr>
                            <?php endif; ?>


                    </tbody>
                </table>
            </div>
            <!-- pagination -->

            <ul class="pagination-section clearfix">



                    <!-- Show pagination links -->
                <?php if(isset($links)):?>
                    <?php foreach ($links as $link) : ?>
                        <?php echo "<li>". $link."</li>"; ?>
                    <?php endforeach; ?>
                <?php endif; ?>



             </ul>
            <!-- pagination end -->

        </div>
    </div>
    <!-- center section end-->
</div>
<!-- wrapper end -->
</body>
<!-- library js -->


</html>

</div>