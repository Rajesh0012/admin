<?php
interface interface_skeleton
{

     function is_logged_in();

     function html($htmlview,$results);


}

class Authentication extends MX_Controller implements interface_skeleton
{

function __construct()
{
    $this->load->model('Qepr_user_model');
     $libarr=array(

        'session'=>'session',
        'form_validation'=>'form_validation'
         );

    $this->load->library($libarr);

    $helparr=array(
        'form'=>'form',
        'url'=>'url',
        'text'=>'text',
        'security'=>'security'
    );
    $this->load->library(['S3']);
    $this->load->model("Common_model");
    $this->load->library('encryption');
    $this->load->helper($helparr);
    $this->load->language('common');



    parent::__construct();
}

 function is_logged_in()
{
    if(!$this->session->has_userdata('username'))
    {
        redirect(site_url().'admin/admin_Controller');
    }


}
function html($htmlview,$results = '')
{

if(!empty($htmlview) || !empty($results))
{
    $this->load->view('header');
    $this->load->view($htmlview,$results);
    $this->load->view('footer');
    return;

}
return false;

}


}
?>