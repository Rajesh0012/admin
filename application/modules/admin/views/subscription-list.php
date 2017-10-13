
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
        <h1 class="heading">Subscription List</h1>
        <h1><?php echo $this->session->flashdata('subscription_updated'); ?></h1>
        <h1><?php echo $this->session->flashdata('del_msg'); ?></h1>
        <h1 class="heading pull-right"><?php echo anchor(site_url().'admin/Subscription_Controller/add_subscription','Add Subscription'); ?></h1>
        <div class="clearfix"></div>
        <div class="tabel-wrapper clearfix">

         <table id="myTable" class="table outlet-table table-striped table-hover">
                <thead>
                <tr>

                <th width="120">S No.</th>
                <th onclick="w3.sortHTML('#myTable','.item', 'td:nth-child(2)')"> Subscription Name </th>
                <th onclick="w3.sortHTML('#myTable','.item', 'td:nth-child(5)')" > Subscription Validity </th>
                <th> Subscription Type </th>
                <th>Price </th>

                <th>Features</th>
               <!-- <th > Features Type </th>-->
                <th > Edit </th>
                <th > Action </th>
                </tr>
                </thead>
                <tbody id="searchdata">

                 <?php if(!empty($subscription_list)) :?>
                <?php $i=1;     foreach($subscription_list as $list): ?>
                   <tr class="item">

                  <td><?= $i++; ?></td>
                    <td><?= isset($list['subscription_name'])?$list['subscription_name']:''; ?></td>
                    <td><?php if($list['subscription_validity'] == SUBSCRIPTION_VALADITY_WEEKLY){ echo 'Weekly Plan';}elseif($list['subscription_validity'] == SUBSCRIPTION_VALADITY_MONTHLY){ echo "Monthly Plan";}elseif($list['subscription_validity'] == SUBSCRIPTION_VALADITY_QUARTERLY){ echo "Quarterly Plan";}elseif($list['subscription_validity'] == SUBSCRIPTION_VALADITY_HALFYEARLY){ echo "Six Month Plan";}elseif($list['subscription_validity'] == SUBSCRIPTION_VALADITY_YEARLY){ echo "Annual Plan";}else{ echo "N/A";} ?></td>
                    <td ><?php if($list['subscription_type'] == MEMBERSHIP_TYPE_PRO){ echo 'PRO Membership';}elseif($list['subscription_type'] == MEMBERSHIP_TYPE_PLATINUM) {echo 'PLATINUM Membership';}else{ echo ' NON PREMIUM MEMBERSHIP';}?></td>
                    <td><?= isset($list['price'])?$list['price']:''; ?></td>
                    <td>
                    <?php  foreach ($list['features'] as $key=>$values): ?>
                       <?php  if($values['feature_type'] == 1){ echo $values['feature'].':'; } ?>
                    <?php endforeach; ?>

                    </td>
                       <!--<td>
                           <?php /*foreach ($list['features'] as $key=>$values): */?>
                               <?php /* if($values['feature_type'] == 2){ echo $values['feature'];} */?>
                           <?php /*endforeach; */?>

                       </td>-->


                    <td>
                        <ul class="table-nav-list">
                            <li><?= anchor(site_url().'admin/Subscription_Controller/add_subscription/'.$list['id'],'<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                        </ul>
                    </td>
                    <td>
                        <ul class="table-nav-list">
                            <li><?= anchor(site_url().'admin/Subscription_Controller/delete/'.$list['id'],'<i class="fa fa-trash-o"></i>','class="edit"') ?></li>
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