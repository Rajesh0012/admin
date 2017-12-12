<?php

require_once('Authentication.php');

class Qepr_admin extends Authentication
{

    function __construct()
    {

        parent::__construct();

     }

    function dashboard()
     {

         Authentication::is_logged_in();

         $results=[];

         Authentication::html('dashboard',$results);

     }

    function index()
    {

        $results=[];

        //generate a csrf token for security check form request and form token is valid or not then process further
        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();

        //check form request method is post or not
        if($this->input->server('REQUEST_METHOD')==='POST')
        {
             //check validation error
            $config = array(
                array(
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'trim|required|min_length[3]|max_length[40]'
                ),
                array(
                        'field' => 'password',
                        'label' => 'password',
                        'rules' => 'trim|required|min_length[5]|max_length[30]'

                    )

                    );
                 //invoke  input validation rules in ci
                $this->form_validation->set_rules($config);

                //check input values are correct or not
                if($this->form_validation->run() == true)
                {

                    //get filtered username and password from input
                    $formdata=$this->input->post();
                    $filtevalues=$this->security->xss_clean($formdata);
                    $username =$filtevalues['username'];
                    $password = md5($filtevalues['password']);

                    //get credential values form database
                    $data = $this->Qepr_user_model->get_logindetails($username);
                    if (!empty(trim($username)) && !empty(trim($password)))
                    {

                        //if no data found from databse it will return an object with blank value
                        //--- that prenvent from undefined object valriables
                        if(count($data)<1)
                        {
                            $data=(object) array('username'=>'',
                                'password'=>'');
                        }

                         //check credentails values from input and exist in datbase are correct
                        if (strcmp($username, $data->email) == 0 && strcmp($password, $data->password) == 0)
                        {

                            //Login here

                            $this->session->set_userdata([
                                'username'=>$data->email,
                                'name'=>$data->name

                                ]);
                            redirect(site_url().'Welcome/Qepr_admin/dashboard');

                        } else {
                            $results['msg']=$this->lang->line('checkuserpass');
                        }
                    }
                }


            }

        Authentication::html('login',$results);

    }

    function logout()
    {
        //check acount is login or not then enter for logout
        if($this->session->has_userdata('username'))
        {

            $this->session->unset_userdata(['username','name']);

            $this->session->set_flashdata('Logout_Success','Logout Successfully!');
        }

         redirect(site_url().'/Welcome/Qepr_admin');
    }



    function cms_pages(){

        Authentication::is_logged_in();

        $results=[];
        if(!empty($_GET['id']))
        {
            $id=$_GET['id'];
            $eid=$this->security->xss_clean($id);
        }
        if(!empty($eid))
        {
            $results['editdata']=$this->Qepr_user_model->All_list($eid);
            $results['eid']=str_replace('-','&nbsp',$eid);


        }
        //generate a csrf token for security check form request and form token is valid or not then process further
        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();
        $postdata=$this->input->post();

        $filterdata=$this->security->xss_clean($postdata);

        //check form request method is post or not
        if($this->input->server('REQUEST_METHOD')==='POST')
        {
            $config = array(

                array(
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required|min_length[5]',

                )

            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == true)
            {
                if(empty($eid))
                {
                    $this->Qepr_user_model->aboutContactUs_insert($filterdata);
                    $results['msg']=$this->lang->line('form_success');

                }else{
                    if($this->Qepr_user_model->put_where_edit($filterdata))
                    {
                        $this->session->set_flashdata('updated',$this->lang->line('update_success'));
                        redirect(site_url().'Welcome/Qepr_admin/page_list');

                    }else{
                        $this->session->set_flashdata('updated',$this->lang->line('update_failled'));
                    }

                }
            }else{

                $results['msg']=$this->lang->line('form_failled');
            }
        }
        Authentication::html('editpages',$results);

    }

    function page_list()
    {

        Authentication::is_logged_in();

        $data['list']=$this->Qepr_user_model->All_list();


        Authentication::html('page-list',$data);



    }


    function put_edit_where($data)
    {

        $data['$values']=$this->Qepr_user_model->put_where_edit();
        $this->load->views($data);
    }

    function age_calculate(){

        $bday=new dateTime('08/06/1992');
        $today=new dateTime(date('m/d/y'));
        $diff=$today->diff($bday);

      echo <<<text
            your age is $diff->y years, $diff->m Months and $diff->d Days;
text;
    }

    function senemaildOtp()
    {
        $data='';
        $results=[];

        // csrf security
        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();

        // check email is valid or not
        $this->form_validation->set_rules('email','email','trim|required|valid_email');

        // check requested method is post or not
        if($this->input->server('REQUEST_METHOD')==='POST')
        {

            //validate data
            if($this->form_validation->run()==true)
            {
                $this->load->library('email');
                $email=$this->input->post('email');
                //clean data
                $cleanmail=$this->security->xss_clean($email);

                $data=$this->Qepr_user_model->get_where_reset($cleanmail);

                //check email are exist or not in database
                if(count($data)>0)
                {

                    //generate random otp values
                    $otp_value=rand(1001,9999);
                    $otp_token=session_id();
                    //store otp in session for further process
                    $this->Qepr_user_model->update_passwordandOtp($data->username,$otp_value,'',$otp_token);
                    $this->session->set_userdata(
                        array(
                            'otp_token'=>$otp_token,
                            'email'=>$data->username

                        ));

                    //send otp mail to user
                    $this->email->from('qepr@qepr.com', 'qepr');
                    $this->email->to("$data->username");
                    // $this->email->cc('another@another-example.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject('Reset Password');
                    $this->email->message("Your Reset Password Opt Is:$otp_value");

                    $this->email->send();
                    $this->session->set_flashdata('msg','otp Sent Your E-mail Address');
                    redirect(site_url().'Welcome/Qepr_admin/save_password');

                }else{
                    $results['msg']=$this->lang->line('notexist');
                }


            }



            }
        Authentication::html('reset_password',$results);


    }

    function save_password()
    {

            // check otp are generated or not
        if (!$this->session->has_userdata('otp_token'))
        {

           return redirect(site_url().'Welcome/Qepr_admin/senemaildOtp');

        }
        $data='';
        $results=[];

        // csrf security
        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();

        // form validation
        $config = array(
            array(
                'field' => 'otp',
                'label' => 'otp',
                'rules' => 'trim|required|min_length[3]'
            ),

            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[3]|max_length[30]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                ),
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password Confirmation',
                'rules' => 'trim|required|matches[password]'
            )

        );


        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $inputdata=$this->input->post();

            //clean input data
            $cleandata=$this->security->xss_clean($inputdata);
            $this->form_validation->set_rules($config);

            // check form data are valid or not
            if ($this->form_validation->run() === true)
            {

                // check again if otp are sent to emai


                 $email=$this->session->userdata('email');
                 $data=$this->Qepr_user_model->get_where_reset($email);

                 $expiration=$this->Qepr_user_model->check_otp_expiration();
                 if(count($expiration)<1)
                 {
                     $results['msg']=$this->lang->line('expired');

                     Authentication::html('save_password', $results);
                        return;
                 }
                             // check given email are exit or not
                if(count($data)>0)
                {
                     if( $data->otp_token === $this->session->userdata['otp_token'])
                     {


                         if($data->otp === $cleandata['otp'])
                            {

                                 //password succfessfully reset here


                                 $this->Qepr_user_model->update_passwordandOtp($data->username,'0',md5($cleandata['passconf']),'NULL');
                                 $this->session->unset_userdata(array('email','otp_token'));

                                 $this->session->set_flashdata('msg',$this->lang->line('Success'));

                                 redirect(site_url().'/Welcome/Qepr_admin');

                            }else{

                                 $results['msg']=$this->lang->line('otp');

                            }
                     }else{

                            $results['msg']=$this->lang->line('sorry');
                        }
                }else{

                        $results['msg']=$this->lang->line('sorry');
                    }




            }


        }

        Authentication::html('save_password', $results);

    }

    function change_password_vai_admin()
    {

        Authentication::is_logged_in();
        $data='';
        $results=[];

        // csrf security
        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();

        // form validation
        $config = array(
            array(
                'field' => 'old_password',
                'label' => 'old Password',
                'rules' => 'trim|required|min_length[3]'
            ),

            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[3]|max_length[30]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                ),
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password Confirmation',
                'rules' => 'trim|required|matches[password]'
            )

        );


        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $inputdata=$this->input->post();

            //clean input data
            $cleaneddata=$this->security->xss_clean($inputdata);
            $this->form_validation->set_rules($config);

            // check form data are valid or not
            if ($this->form_validation->run() === true)
            {

                // check again if otp are sent to email

                     $data=$this->Qepr_user_model->get_where_reset('rajesh.maurya@appinventiv.com');

                        // check given email are exit or not
                        if(count($data)>0)
                        {

                            if(strcmp($data->password,md5($cleaneddata['old_password']))==0)
                            {

                                //password succfessfully reset here
                                if($this->Qepr_user_model->update_passwordandOtp('rajesh.maurya@appinventiv.com',md5($cleaneddata['passconf']),'0','NULL'))
                                {


                                    $results['msg']=$this->lang->line('Success');


                                }else{

                                    $results['msg']=$this->lang->line('failled');

                                }

                            }else{
                                $results['msg']=$this->lang->line('old_notcorrect');

                            }

                        }else{

                            $results['msg']=$this->lang->line('sorry');
                        }


                     }

         }
        Authentication::html('via_admin_change', $results);

     }

    function notification()
    {

        $results=[];
        Authentication::is_logged_in();

        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();
        $data=$this->input->post();
        $cleandata=$this->security->xss_clean($data);

        if($this->input->server('REQUEST_METHOD') === 'POST')
        {

        print_r($cleandata);
        }

                 Authentication::html('notification', $results);



     }

        function add_banner()
        {

            Authentication::is_logged_in();
            Authentication::html('add_banner');


        }

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

            $config['base_url'] = site_url() . '/Welcome/Qepr_admin/get_user';
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

        function view_user_details()
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

        function searchname()
        {
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