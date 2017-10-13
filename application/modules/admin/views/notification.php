

<body>
<div class="wrapper">
    <aside class="side-menubar">


        <?php require_once ('sidebar.php');?>
    </aside>
        <!-- header -->
        <?php require_once ('common-header.php');?>
        <!-- header end  -->
        <div class="center-section-inner">
            <div class="dashboard-wrapper">
                <div class="row">

                    <h1 style="color:white">Send Notification</h1>
                </div>
                <div class="container">
                <span><?= isset($msg)?$msg:'';?></span>
                    <form class="form-horizontal" method="post" >
                        <input type="hidden" name="<?= $csrfname; ?>" value="<?= $csrfvalue; ?>">
                        <div class="col-md-12">
                            <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-6">
                                  I-Phone <input type="radio" value="i-phone" name="phone">
                                </div>
                                <div class="col-sm-6">
                                    Android<input type="radio" value="android" name="phone">
                                </div>
                            </div>
                            </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Select Gender:</label>
                            <div class="col-sm-6">
                               <select name="gender" class="form-control">
                                  <option value="">--select--</option>
                                   <option value="male">Male</option>
                                   <option value="female">Female</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Select Age:</label>
                            <div class="col-sm-6">
                                <select  name="age" class="form-control">
                                    <option value="">--select--</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                        </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Select City:</label>
                                <div class="col-sm-6">
                                    <select name="payment" class="form-control">
                                        <option value="">--select--</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Select City:</label>
                                <div class="col-sm-6">
                                    <select name="country" class="form-control">
                                        <option value="">--select--</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                            <?php
                            if(isset($editdata)){
                                foreach($editdata as $values){}
                            }
                            ?>
                            <span class="error"><?= isset($msg)?$msg:''; ?><?=   validation_errors(); ?></span>
                            <input type="hidden" name="<?= $csrfname ?>" value="<?= $csrfvalue ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" value="<?= isset($values->title)?$values->title:''; ?>" name="title" class="form-control" id="email" placeholder="Enter email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Mesages:</label>
                                <div class="col-sm-8">
                                    <textarea name="description"  rows="15" cols="50"><?= isset($values->description)?$values->description:''; ?></textarea>
                                </div>
                            </div>

                        <script>
                            CKEDITOR.replace( 'description' );
                        </script>


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
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