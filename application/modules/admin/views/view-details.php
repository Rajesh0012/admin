
<body>
<div class="wrapper">
    <aside class="side-menubar">


        <?php require_once ('sidebar.php');?>
    </aside>
    <!-- side-menubar end -->
    <?php require_once ('common-header.php');?>
    <!-- header end  -->
    <div class="center-section-inner">
        <h1 class="heading">User Management</h1>
        <h1 style="color:red" ><?= !empty($this->session->flashdata('blocked'))?$this->session->flashdata('blocked'):'' ?></h1>
        <h1 class="heading pull-right"><?= anchor(site_url().'admin/Users_Controller/get_user','Back');?></h1>
        <div class="clearfix"></div>
        <div class="tabel-wrapper clearfix">
            <table class="table outlet-table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="3"> User Details </th>
            </tr>
            </thead>
            <tbody>

            <?php foreach($userlist as $users): ?>

                <tr>
                    <th> &nbsp </th>
                    <th> Name </th>
                    <td><?= isset($users->name)?$users->name:''; ?></td>
                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th> Email </th>
                    <td><?= isset($users->email)?$users->email:''; ?></td>
                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th> Phone Number </th>
                    <td><?= isset($users->mobile_number)?$users->mobile_number:''; ?></td>

                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th> Alternet Number </th>
                    <td><?= isset($users->mobile_number)?$users->mobile_number:''; ?></td>

                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th> Facebook ID </th>
                    <td><?= isset($users->mobile_number)?$users->mobile_number:''; ?></td>

                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th> Subscription Plan </th>
                    <td>Pro Membership</td>

                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th> Registered on </th>
                    <td><?= isset($users->created)?$users->created:''; ?></td>

                </tr>
                <tr>
                    <th> &nbsp </th>
                    <th > Subscribed On </th>
                    <td><?= isset($users->created)?$users->created:''; ?></td>


                </tr>

                <tr style="border: 0px">
                    <td align="left"> Version </td>
                    <th > &nbsp</th>
                    <td align="right">Android</td>


                </tr>
                <tr >

                    <td colspan="9">
                        <form id="block_form"  method="post">
                            <input type="hidden" value="<?= $this->security->get_csrf_hash(); ?>" name="<?= $this->security->get_csrf_token_name();  ?>" >
                            <input type="hidden" value="<?= $this->uri->segment(4); ?>" name="id">
                            <?php if($users->status==2): ?>
                                <input type="hidden" value="unblock" name="unblock">
                                <input onclick="unblock_confirmbox()" type="button"  class="btn btn-primary" value="UnBlock">
                                <?php else: ?>
                                <input type="hidden"  value="block" name="block">
                            <input onclick="block_confirmbox()" type="button" class="btn btn-primary" value="Block">
                            <?php endif ?>
                        </form>
                    </td>


                </tr>
            <?php endforeach; ?>
            </tbody>

            </table>
            <table class="table outlet-table table-striped table-hover">
                <thead>
                <tr>
                    <th colspan="9"> Total Purchases </th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($userlist as $users): ?>

                    <tr class="success">
                        <th> &nbsp </th>
                        <th> Purchased Product </th>
                        <td><?= isset($users->name)?$users->name:''; ?></td>
                    </tr>
                    <tr class="success">
                        <th> &nbsp </th>
                        <th> Purchased On </th>
                        <td ><?= isset($users->created)?$users->created:''; ?></td>
                    </tr>
                    <tr class="danger">
                        <th> &nbsp </th>
                        <th> Purchased Product </th>
                        <td><?= isset($users->name)?$users->name:''; ?></td>

                    </tr>
                    <tr class="danger">
                        <th> &nbsp </th>
                        <th> Tag Number </th>
                        <td>12dfsfgf23</td>
                    </tr>
                    <tr class="danger">
                        <th> &nbsp </th>
                        <th> Purchased On </th>
                        <td><?= isset($users->created)?$users->created:''; ?></td>


                    </tr>
                    <tr class="warning">
                        <th> &nbsp </th>
                        <th> Purchased Product </th>
                        <td><?= isset($users->name)?$users->name:''; ?></td>

                    </tr>
                    <tr class="warning">
                        <th> &nbsp </th>
                        <th> Tag Number </th>
                        <td>12dfsfgf23</td>

                    </tr>
                    <tr class="warning">
                        <th> &nbsp </th>
                        <th> Tag Color </th>
                        <td>Green</td>


                    </tr>
                    <tr class="warning">
                        <th> &nbsp </th>
                        <th> Purchased On </th>
                        <td><?= isset($users->created)?$users->created:''; ?></td>


                    </tr>

                <?php endforeach; ?>
                </tbody>

            </table>
        </div>
        <!-- pagination -->


        <!-- pagination end -->

    </div>
</div>
<!-- center section end-->
</div>
<!-- wrapper end -->
</body>
<!-- library js -->


<script>
    $(function () {
        $('#slidetoogle').click(function () {
            $('#filter_form').slideToggle('dl');
        })
    })
function block(){

        if(confirm('are sure want to Block this User')){
            return true;
        }else{
            return false;
        }


}
</script>

</html>

</div>


