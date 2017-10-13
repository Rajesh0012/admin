<?php

require_once('Authentication.php');

class Subscription_Controller extends Authentication{


    public function __construct()
    {
        parent::__construct();
    }


    /*add subscription  details form add_subscription function*/
    public function add_subscription()
    {
        $data='';

        $id = $this->uri->segment(4);

        if(!empty($id))
        {

            $data=$this->Qepr_user_model->subscription_list($id);

            foreach ($data as $key => $values) {
                if (!empty($values['features'])) {

                    /*::: is seprator for removing ::: from string values*/
                    $exp = explode(':::', $values['features']);
                    unset($data[$key]['features']);
                    $i = 0;
                    foreach ($exp as $strvalues) {

                        /*;;; is seprator removing ;;; from string values*/
                        $temp_var = explode(";;;", $strvalues);

                        /*set all variable again for feature to show it properly in html view*/
                        $data[$key]['features'][$i]['feature'] = $temp_var['0'];
                        $data[$key]['features'][$i]['feature_type'] = $temp_var['1'];
                        $i++;

                    }
                } else {
                    $data[$key]['features'] = array();
                }

            }

        }

        if(!empty($data)){
            $data['subscription_list']=$data;

        }else{
            $data['subscription_list']=array();

        }

        /*check logged in or not*/
        Authentication::is_logged_in();

        /*validation rules*/
        $config=array(
            array(

                'field'=>'subscription_name',
                'label'=>'Subscription Name',
                'rules'=>'trim|required|min_length[3]|max_length[100]|xss_clean'
            ),
            array(

                'field'=>'subscription_validity',
                'label'=>'Subscription Validity',
                'rules'=>'trim|required|xss_clean'
            ),
            array(

                'field'=>'subscription_type',
                'label'=>'Subscription Type',
                'rules'=>'trim|required|xss_clean'
            ),

            array(

                'field'=>'price',
                'label'=>'Price',
                'rules'=>'trim|numeric|max_length[5]|required|xss_clean|greater_than[0.99]'
            ),
            array(

                'field'=>'features[]',
                'label'=>'Features',
                'rules'=>'trim|required|xss_clean'
            )
        );

        /*check validation*/
        $this->form_validation->set_rules($config);


        /*check post method is called or not*/
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $formdata=$this->input->post();

            $this->session->set_userdata('tempdata',$formdata);
            /*if validation is correct then proceed further*/
            if($this->form_validation->run() == true){

                print_r($formdata);

                /*check price is numeric or not if not return false message*/
                if(is_numeric($formdata['price'])){

                    if(!empty($id)){

                        $success=$this->Qepr_user_model->edit_subscription($formdata,$id);

                        if(isset($success)){

                            //$data['msg']=$this->lang->line('form_success');
                            $this->session->set_flashdata('subscription_updated',$this->lang->line('subscription_updated'));
                            redirect(site_url().'admin/Subscription_Controller/subscription_list');


                        }

                    }else{

                        $success=$this->Qepr_user_model->multi_insert($formdata);

                        if(isset($success)){
                            redirect(site_url().'admin/Subscription_Controller/subscription_list');
                            $data['msg']=$this->lang->line('form_success');

                            unset($data);
                            $data=[];

                        }
                    }


                }else{

                    $data['msg']=$this->lang->line('invalid_format');

                }


            }

            //$data['subscription_list']=$formdata;
        }

        /*html view from here*/
        Authentication::html('add-subscription',$data);
    }

    /* get subscription list form subscription_list function*/
    public function subscription_list(){



        $data=array();

        Authentication::is_logged_in();

        /* subscription_list get data from database */
        $data=$this->Qepr_user_model->subscription_list();

        /*extract all key and values from databse and explode to manage proper in lsit view*/
        foreach ($data as $key => $values) {
            if (!empty($values['features'])) {

                /*::: is seprator for removing ::: from string values*/
                $exp = explode(':::', $values['features']);
                unset($data[$key]['features']);
                $i = 0;
                foreach ($exp as $strvalues) {

                    /*;;; is seprator removing ;;; from string values*/
                    $temp_var = explode(";;;", $strvalues);

                    /*set all variable again for feature to show it properly in html view*/
                    $data[$key]['features'][$i]['feature'] = $temp_var['0'];
                    $data[$key]['features'][$i]['feature_type'] = $temp_var['1'];
                    $i++;

                }
            } else {
                $data[$key]['features'] = array();
            }

        }


        $data['subscription_list']=$data;
        Authentication::html('subscription-list',$data);
    }


    /*
     *
     * delete function made for delete subscription plan from database
     * on the basis of id
     *
     * */

    public function delete(){

        Authentication::is_logged_in();
        /*get ci query string */

        $id=$this->uri->segment(4);

        /*check if not empty data in id then enter*/
        if(!empty($id)){

            /*delete from database form this function delete_subscription_plan */

            $confirm=$this->Qepr_user_model->delete_subscription_plan($id);
            if($confirm){

                /*if deleted then set message to confirm id is delete or not*/

                $this->session->set_flashdata('del_msg',$this->lang->line('subscription_deleted'));
                redirect(site_url().'admin/Subscription_Controller/subscription_list');

            }else{

                /*if Not deleted then set message to confirm id is  not deleted*/
                $this->session->set_flashdata('del_msg',$this->lang->line('subscription_deleted_failled'));
                redirect(site_url().'admin/Subscription_Controller/subscription_list');

            }
        }


    }

}