



<script src="<?= site_url();?>assets/js/jquery.min.js"></script>
<script src="<?= site_url();?>assets/js/bootstrap.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="<?= site_url();?>assets/js/bootstrap-select.js"></script>
<script src="<?= site_url();?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= site_url();?>assets/js/exporting.js"></script>
<script src="<?= site_url();?>assets/js/highcharts.js"></script>
<script src="<?= site_url();?>/assets/js/jquery_validation.js"></script>

<!-- custom -->
<script src="<?= site_url();?>assets/js/custom.js"></script>
<script src="<?= site_url();?>assets/js/profile.js"></script>
<script src="<?= site_url();?>assets/js/custom-datepicker.js"></script>
<script src="<?= site_url();?>assets/js/custom-select.js"></script>
<script src="<?= site_url();?>assets/js/custom-highchart.js"></script>


<script>

 function searchh(str) {


     $.ajax({

         type:'GET',

         url:"http://localhost/qepr_admin/admin/Users_Controller/searchname/",

         data:{name:str},

         success: function(data){

             $("#searchdata").html(data);
             $('.pagination-section').hide();
         },

     });




    }

</script>
<script>
    function logout(str) {
        if(str === '1'){
            window.location.href="<?php echo site_url();?>admin/Admin_Controller/logout";
        }
        return false;
    }

</script>
<script>
var i=1;
    $(function () {
        $('#add').click(function(){
if(i>=25){
    alert('we allowed max 25 features only');
    return false;
}
            $('<div id="remov'+ ++i +'" class="form-group"> <label class="control-label col-sm-2">Features#'+ i +':</label><div class="col-sm-6"><input type="text" class="form-control" name="features[]"></div><a id="'+ i +'" onclick="removebox(this.id)" href="javacript:void(0)">Remove-</a></div> </div>').appendTo('#append');
        })



    })

   function removebox(str) {

            $('#remov'+str).remove();
            i--;

  }


</script>
<script>
    var fti=1;
    $(function () {
        $('#ftypeadd').click(function(){
            if(fti>=10){
                alert('we allowed max 10 features Type only');
                return false;
            }
            $('<div id="ftremov'+ ++fti +'" class="form-group"> <label class="control-label col-sm-2">Features Type#'+ fti +':</label><div class="col-sm-6"><input type="text" class="form-control" name="features_type[]"></div><a id="'+ fti +'" onclick="removeftbox(this.id)" href="javacript:void(0)">Remove-</a></div> </div>').appendTo('#ftype');
        })



    })

    function removeftbox(str) {

        $('#ftremov'+str).remove();
        fti--;

    }


</script>
<script>

    function block_confirmbox() {

        if(confirm('Are Sure Want To Block This User')){

            $('#block_form').submit();
        }

        else {
           return false;
       }
    }

    function unblock_confirmbox() {
        if(confirm('Are Sure Want To UnbLock This User')){

            $('#block_form').submit();
        }

        else {
            return false;
        }

    }

</script>
<script>
    $("#product_image").on("change", function() {
        if($("#product_image")[0].files.length > 2); {
            alert("You can select only 2 images");
        } else {
            $("#product_form").submit();
        }
    });
</script>

</html>

