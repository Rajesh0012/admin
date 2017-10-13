<?php

require_once('Authentication.php');

class Users_Controller extends Authentication{


    public function __construct()
    {


        parent::__construct();

    }


    /*
 * get_user function get all user list form database
 * and also have ci pagination to show and it serach the
 *  form databases using get_users model function
 * get user function have $config['per_page'], $data['page'], $cleaneddata
 * parameter frst parameter for limit data per page (page) parameter for
 * to decide which row load to next page
 * $cleandata aremade for safe search from database $cleandata using ci filter
 * function to sanitize
 *
 *
 * */
    function get_user()
    {
        Authentication::is_logged_in();
        $data=array();
        $cleaneddata=[];
        $this->load->library('pagination');

        $data['csrfname']=$this->security->get_csrf_token_name();
        $data['csrfvalue']=$this->security->get_csrf_hash();

        if($this->input->server('REQUEST_METHOD') === 'POST') {

            $postdata = $this->input->post();

            $config = array(
                array(
                    'field' => 'from_date',
                    'label' => 'From Date',
                    'rules' => 'trim|required|min_length[3]'
                ),
                array(
                    'field' => 'to_date',
                    'label' => 'From Date',
                    'rules' => 'trim|required|min_length[3]'
                ),
                array(
                    'field' => 'Status',
                    'label' => 'Status',
                    'rules' => 'trim|required|min_length[3]'
                ),
                array(
                    'field' => 'user_type',
                    'label' => 'From Date',
                    'rules' => 'trim|required|min_length[3]'
                ),


            );
            $this->form_validation->set_rules($config);

            $cleaneddata = $this->security->xss_clean($postdata);

        }


        $total_row = $this->Qepr_user_model->count_user($cleaneddata);
        foreach ($total_row as $totalpage)
        {

            $paged = $totalpage->id;
        }

        $config = array();
        $data['available_users'] = $paged;

        $config['base_url'] = site_url() . '/admin/Users_Controller/get_user';
        $config["total_rows"] = $paged;
        $config['per_page'] = 5;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $paged;
        $config['cur_tag_open'] = '&nbsp;<a>';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = '>';
        $config['prev_link'] = '<';


        $this->pagination->initialize($config);
        if ($this->uri->segment(4)) {

            $data['page'] = (($this->uri->segment(4) - 1) * (5));

        } else {

            $data['page'] = 0;
        }

        $data['userlist'] = $this->Qepr_user_model->get_users($config['per_page'], $data['page'], $cleaneddata);
        $str_links = $this->pagination->create_links();
        $data['links'] = explode('&nbsp;', $str_links);

        // View data according to array.

        Authentication::html('userlist', $data);


    }

    public function view_user_details()
    {

        /* check user is logged in or not*/
        Authentication::is_logged_in();
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data=$this->input->post();


            $this->Qepr_user_model->block_user($data);

        }

        $this_id=$this->uri->segment(4);

        /* get user details on the basis of id*/
        $data['userlist']=$this->Qepr_user_model->view_user($this_id);

        Authentication::html('view-details',$data);

    }

    public function searchname()
    {
        Authentication::is_logged_in();
        $thisname='';

        if(isset($_GET['name'])){
            $thisname=$_GET['name'];
        }
        /* clean query string data coming form ajax request */
        $searchname=$this->security->xss_clean($thisname);

        /* go to view on search by name */
        echo  $this->Qepr_user_model->getsearch_name($searchname);

    }



}