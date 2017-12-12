<?php if($this->uri->segment(3) === 'get_user'): ?>
<div class="filter-side-wrapper" id="filter-side-wrapper">
    <h1>Filters </h1>
    <form id="filter" method="post">
        <input type="hidden" value="<?= $csrfvalue ?>" name="<?= $csrfname;  ?>">
        <label class="input-label mt15">Registered On :</label>
        <input type="text" name="from_date" id="datepicker_1" class="inputfield" placeholder="From">
        <input type="text" name="to_date" id="datepicker_2" class="inputfield" placeholder="To">

        <label class="input-label mt15">Number of Purchases :</label>
        <select name="Number_of_Purchases" class="selectpicker form-control">
            <option value="">--Select--</option>
            <option value="0-5">0 - 5 </option>
            <option value="5-10">5 -10</option>
        </select>

        <label class="input-label mt15">Status :</label>
        <select name="Status" class="selectpicker form-control" />
        <option value="" >--Select--</option>
        <option value="1">Subscribed</option>
        <option value="0">Unsubscribed</option>
        </select>
        <label class="input-label mt15">User Type :</label>
        <select name="User_type" class="selectpicker form-control" />
        <option >Select</option>
        <option value="2">Blocked</option>
        <option value="1">Unblocked</option>
        </select>

        <div class="button-wrapper text-center">
            <input type="submit" value="Filter" class="custom-btn save" />
            <input type="button" value="Reset" class="custom-btn cancel" />
        </div>
    </form>
</div>
<?php endif;?>
<!-- center section -->
<div class="center-section">
    <!-- header -->
    <div class="header-section">
        <a href="javascript:void(0);" class="toggle-btn"> <span></span><span></span><span></span></a>
        <div class="row">
            <div class="col-sm-3 col-md-4">
                <select onchange="logout(this.value)" class="custom-select selectpicker">
                    <option  value="0">action </option>
                    <option value="1">Logout</option>
                    <!-- <option value="2">demo 2</option>-->
                </select>
            </div>
            <?php if($this->uri->segment(3) === 'get_user'): ?>
            <div class="col-sm-6 col-md-4">
                <div class="search-wrapper">
                    <div class="input-wrap">
                        <input onkeyup="searchh(this.value)" type="search" value="" id="searchh" class="search inputfield" placeholder="Search by Name">
                        <a href="javascript:void(0);" class="custom-btn"><img src="<?= site_url();?>assets/images/search.png" alt=""></a>
                        <div class="search-cross"><img src="<?= site_url();?>assets/images/cross.png" alt=""></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-md-4">
                <div class="filter-button"> <img src="<?= site_url();?>assets/images/filter.png" alt=""></div>
            </div>
            <?php endif; ?>
        </div>

    </div>
