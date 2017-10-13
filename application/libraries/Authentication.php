<?php
/**
 * Custom Authentication and error handler Controller.
 * 
 * @package         Libraries
 * @category        Libraries
 * @author          AppInventiv
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Authentication extends REST_Controller {
    
    protected $loginUser;
    /**
     * Constructor for the AutheticationLib
     *
     * @access public
     * @param string $config (do not change)
     * @return void , EXIT_USER_INPUT on error
     */
    public function __construct($config = 'rest') {
		
        parent::__construct($config);
        $this->lang->load('api', "english");
    }
    
    /**
     * @name checkLogin
     * @description checkes for login with accesstoken from header.
     * @param string $accessToken
     * @return int or error array
     */
    public function checkLogin($accessToken) {
        
        if (!empty($accessToken)) {
            $this->load->model('User_model');
            $params = array();
            $loginUserData = $this->User_model->getLoginDetail(array("access_token" => $accessToken));
            if (!empty($loginUserData)) {
                if ($loginUserData['status'] != USER_ACTIVE) {
                    $this->response(['code' => USER_STATUS_NOT_ACTIVE,'message'=>$this->lang->line('USER_STATUS_NOT_ACTIVE'), 'error' => TRUE]);
                }
                return $loginUserData['user_id'];
            }
        }
        $this->response(['code' => INVALID_ACCESS_TOKEN,'message'=>$this->lang->line('INVALID_ACCESS_TOKEN'), 'error' => TRUE]);
    }
    
    /**
     * @name getAccessToken
     * @description get token value from header.
     * @return string
     */
    public function getAccessToken(){
        if (!empty($this->input->request_headers()['Uaccesstoken'])) {
            return $this->input->request_headers()['Uaccesstoken'];
        } else{
            $this->response(['code' => HEADER_MISSING,'message'=>$this->lang->line('HEADER_MISSING'), 'error' => TRUE]);
        }
    }
    
    public function checkRequiredParams($param = array()) {
        if (isset($param) && is_array($param) && count($param)) {
            foreach ($param as $par) {
                if (empty($par)) {
                    return 0;
                }
            }
        }
        return 1;
    }
    
    /**
     * @name validator
     * @description validates input array that value is set. Optionally checks
     *              for empty case. 
     * @param array $param
     * @param array $data
     * @param boolean $checkEmpty
     * @return boolean
     */
    public function paramValidator($param , $checkEmpty = false) {
        if (!empty($param) && is_array($param)) {
			foreach ($param as $key => $value) {
				if (!isset($param[$key]) || empty($param[$key])) {
					return false;
				}
			}
		} else {
			return false;
		}
        return true;
    }
}
