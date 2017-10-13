
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
        <h1><?php echo $this->session->flashdata('del_msg'); ?></h1>
        <h1><?php echo $this->session->flashdata('msg'); ?></h1>
        <h1 style="color:red"><?php echo $this->session->flashdata('img_not_updated'); ?></h1>

        <div class="clearfix"></div>
        <div class="tabel-wrapper clearfix">

            <table id="myTable" class="table outlet-table table-striped table-hover">
                <thead>
                <tr>

                    <th width="120">S No.</th>
                    <th onclick="w3.sortHTML('#myTable','.item', 'td:nth-child(2)')"> Name </th>
                    <th onclick="w3.sortHTML('#myTable','.item', 'td:nth-child(5)')" > Price</th>
                    <th> Description</th>

                    <th>Membership Type </th>
                    <th>Product Type </th>
                    <th>Images</th>

                    <th > Action </th>
                </tr>
                </thead>
                <tbody id="searchdata">

                <?php $i=1;  foreach ($products as $key=>$values) :  ?>

                        <tr class="item">

                            <td><?= $i++; ?></td>
                            <td><?= isset($values->name)?$values->name:''; ?></td>
                            <td><?= $values->price; ?></td>
                            <td ><?= $values->description;?></td>
                             <td><?php if($values->membership_type == MEMBERSHIP_TYPE_PLATINUM){ echo 'Non Premium Member';}elseif ($values->membership_type == MEMBERSHIP_TYPE_PRO){echo 'Pro Member';}else{ echo 'Platinum Member';} ?></td>
                            <td><?php if($values->product_type == PRODUCT_KEYFOB){ echo 'Key Fob';} elseif ($values->product_type == PRODUCT_ORIGINAL_TAG){ echo 'Original Tag';}elseif ($values->product_type == PRODUCT_CUSTOM_TAG){ echo 'Custom Tag';}else { echo 'Other';} ?></td>
                            <td>
                                <?php foreach ($values->image as $img) : ?>
                                <img alt="Image NOt Found" width="100px" src="<?= $img;?>" />
                             <?php endforeach; ?>
                              </td>
                             <td>
                                <ul class="table-nav-list">
                                    <li><?= anchor(site_url().'admin/Product_Controller/edit_products/'.$values->id,'<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                                </ul>
                            </td>
                        </tr>

<?php endforeach; ?>




                </tbody>
            </table>
        </div>


    </div>
</div>
<!-- center section end-->
</div>
<!-- wrapper end -->
</body>
<!-- library js -->


</html>

</div>