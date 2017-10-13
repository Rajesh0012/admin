<?php
require APPPATH.'libraries/Authentication.php';

class Subscription extends Authentication {

    function __construct()
    {
        parent::__construct();
             $this->load->model('User_model');
    }

    function fetchSubscription_get()
    {
        $access_token = $this->getAccessToken();
        $this->checkLogin($access_token);
        $data = $this->User_model->get_membership_plan();
        //print_r($data);exit;
        foreach ($data as $key=>$values)
        {
            if (!empty($values['features'])) {
                $exp = explode(':::', $values['features']);
                unset($data[$key]['features']);
                $i = 0;
                foreach ($exp as $strvalues) {
                    $temp_var = explode(";;;", $strvalues);

                    $data[$key]['features'][$i]['feature'] = $temp_var['0'];
                    $data[$key]['features'][$i]['feature_type'] = $temp_var['1'];
                    $i++;

                }
            } else {
                $data[$key]['features'] = array();
            }

        }




       //print_r($data);
       $this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' =>$data));

    }
}