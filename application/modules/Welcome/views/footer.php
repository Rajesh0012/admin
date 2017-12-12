



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
    $('#date_from').datepicker({
        format: 'mm/dd/yyyy',

    });
</script>
<script>
    $('#date_to').datepicker({
        format: 'mm/dd/yyyy',

    });


</script>
<script>
    $(function () {
        $('#slidetoogle').click(function () {
            $('#filter_form').slideToggle('slow');
        })
    })

</script>
<script>

 function searchh(str) {


     $.ajax({

         type:'GET',

         url:"http://localhost/qepr_admin/Welcome/Qepr_admin/searchname/",

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
            window.location.href="<?php echo site_url();?>Welcome/Qepr_admin/logout";
        }
        return false;
    }

</script>
</html>
