<?php

require_once('Authentication.php');

class Admin_Controller extends Authentication
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
                    $password = $filtevalues['password'];

                    //get credential values form database
                    $data = $this->Qepr_user_model->get_logindetails($username);
                    if (!empty(trim($username)) && !empty(trim($password))) {

                        if(empty($data->email) && empty($data->password)){
                            $data = (object)array('username' => '',
                                'password' => '',
                                'email' => ''
                            );
                        }


                        //if no data found from databse it will return an object with blank value
                        //--- that prenvent from undefined object valriables
                        if (count($data) > 0) {


                            $results['msg'] = $this->lang->line('checkuserpass');




                        //check credentails values from input and exist in datbase are correct
                        if (strcmp($username, $data->email) == 0 && password_verify($password,$data->password) == 1) {



                            //Login here
                            $email=$this->encryption->encrypt($data->email);
                            $this->session->set_userdata([
                                'username' => $email,
                                'name' => $data->name

                            ]);

                          redirect(site_url() . 'admin/admin_Controller/dashboard');

                        } else {
                            $results['msg'] = $this->lang->line('checkuserpass');
                        }
                    }else{


                            $results['msg'] = $this->lang->line('checkuserpass');

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
            $this->session->sess_destroy();

            $this->session->set_flashdata('Logout_Success','Logout Successfully!');
        }

         redirect(site_url().'/admin/Admin_Controller');
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
        if($this->input->server('REQUEST_METHOD') === 'POST')
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
                    $this->Qepr_user_model->update_passwordandOtp($data->email,$otp_value,'',$otp_token);
                    $this->session->set_userdata(
                        array(
                            'otp_token'=>$otp_token,
                            'email'=>$this->encryption->encrypt($data->email)

                        ));

                    //send otp mail to user
                    $this->email->from('qepr@qepr.com', 'qepr');
                    $this->email->to("$data->email");
                    // $this->email->cc('another@another-example.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject('Reset Password');
                    $this->email->message("Your Reset Password Opt Is:$otp_value");

                    $this->email->send();
                    $this->session->set_flashdata('msg','otp Sent Your E-mail Address');
                    redirect(site_url().'admin/admin_Controller/save_password');

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

           return redirect(site_url().'admin/admin_Controller/senemaildOtp');

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
                 $protectedmail=$this->encryption->decrypt($email);
                 $data=$this->Qepr_user_model->get_where_reset($protectedmail);

                 $expiration=$this->Qepr_user_model->check_otp_expiration($protectedmail);

                 if(count($expiration)<1)
                 {
                     $results['msg']=$this->lang->line('expired');

                     Authentication::html('save_password', $results);
                        return false;
                 }
                             // check given email are exit or not
                if(count($data)>0)
                {
                     if( $data->forgot_token === $this->session->userdata['otp_token'])
                     {


                         if($data->verify_otp === $cleandata['otp'])
                            {

                                 //password succfessfully reset here


                                 $this->Qepr_user_model->update_passwordandOtp($data->email,'0',password_hash($cleandata['passconf'],PASSWORD_BCRYPT),'NULL');

                                 $this->session->unset_userdata(array('email','otp_token'));

                                 $this->session->set_flashdata('msg',$this->lang->line('Success'));

                                 redirect(site_url().'/admin/Admin_Controller');

                            }else{

                                 $results['msg']=$this->lang->line('Enter_correct_otp');

                            }
                     }else{

                            $results['msg']=$this->lang->line('unable_to_process');
                        }
                }else{

                        $results['msg']=$this->lang->line('unable_to_process');
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
            ));


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
                $email=$this->session->userdata('username');

                $protectedmail=$this->encryption->decrypt($email);

                     $data=$this->Qepr_user_model->get_where_reset($protectedmail);

                        // check given email are exit or not
                        if(count($data)>0)
                        {

                            if(password_verify($cleaneddata['old_password'],$data->password) == 1)
                            {

                                //password succfessfully reset here
                                if($this->Qepr_user_model->update_passwordandOtp($protectedmail,'0',password_hash($cleaneddata['passconf'], PASSWORD_BCRYPT),'NULL'))
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

   public function change_email()
        {


            /* check admin is logged in or not*/
            Authentication::is_logged_in();

            $data=[];

            $data['csrfname']=$this->security->get_csrf_token_name();
            $data['csrfvalue']=$this->security->get_csrf_hash();

            if($this->input->server('REQUEST_METHOD') === 'POST')
            {
                $postdata=$this->input->post();
                $config=array(

                    array(
                        'field'=>'old_email',
                        'label'=>'Email',
                        'rules'=>'trim|required|valid_email|xss_clean'),

                    array(
                        'field'=>'email',
                        'label'=>'Email',
                        'rules'=>'trim|required|valid_email|xss_clean'),

                    array(
                        'field'=>'emailconf',
                        'label'=>'Confirm Email',
                        'rules'=>'trim|required|xss_clean|valid_email|matches[email]')

                         );

                $this->form_validation->set_rules($config);

                if($this->form_validation->run() == true)
                {


                $check_existent=$this->Qepr_user_model->change_email($postdata);

                if(count($check_existent)>0){

                 foreach ($check_existent as $key=>$values){


                     if($postdata['old_email'] == $values->email  ){

                         $succ= $this->Qepr_user_model->set_mail($postdata);
                         if($succ){


                             $data['msg']=$this->lang->line('email_changed');
                         }else{

                             $data['msg']=$this->lang->line('email_not_changed');
                         }
                     }
                     else{
                         $data['msg']=$this->lang->line('email_not_exist');

                     }


                 }


                 }
                else{
                    $data['msg']=$this->lang->line('email_not_exist');

                }

                }

            }

            Authentication::html('change-email',$data);

        }

            public function change_name(){

                $data=[];

                $data['csrfname']=$this->security->get_csrf_token_name();
                $data['csrfvalue']=$this->security->get_csrf_hash();

                Authentication::is_logged_in();


                if($this->input->server('REQUEST_METHOD') === 'POST')
                {
                    $postdata=$this->input->post();
                    $config=array(

                        array(
                            'field'=>'name',
                            'label'=>'Name',
                            'rules'=>'trim|required|xss_clean|min_length[3]'));

                        $this->form_validation->set_rules($config);

                    if($this->form_validation->run() == true)
                    {
                        $protectedemail=$this->encryption->decrypt($this->session->userdata('username'));

                        $succ=$this->Qepr_user_model->name($postdata,$protectedemail);
                        if($succ){
                        $data['msg']=$this->lang->line('name_changed');

                        }else{

                            $data['msg']=$this->lang->line('name_Not_changed');

                        }
                    }

                    }

                Authentication::html('changed-name',$data);

            }

}