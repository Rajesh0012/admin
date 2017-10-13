<?php

require APPPATH.'libraries/Authentication.php';
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="10.10.10.107",
 *     basePath="/qepr/api",
 *     @SWG\Info(
 *         version="1.0",
 *         title="Qepr",
 *         description="",
 *         @SWG\Contact(
 *             email="manu.jain@appinventiv.com"
 *         )
 *     ),
 * @SWG\SecurityScheme(
 *   securityDefinition="basicAuth",
 *   type="basic"
 * )
 * ),
 */
class Users extends Authentication {
	
	public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url', 'email'));
        
        $this->load->model('Common_model');
        $this->load->language('api');
        
        $this->load->model('User_model');
        $this->load->database();
    }
	
	/**
     * @SWG\Post(path="/users/login/",
     *   tags={"User"},
     *   summary="Login User into system",
     *   description="Either need to send fb_id or need to send twitter_id or need to email and password",
     *   operationId="login",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="fb_id",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="twitter_id",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="credentials_type",
     *     in="formData",
     *     description="",
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="Validate success"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=439, description="Facebook Id not registered"),
     *   @SWG\Response(response=440, description="Email not registered"),
     *   @SWG\Response(response=442, description="User Blocked"),
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	
	public function login_post() 
    {
		$postDataArr = $this->post();
		if (isset($postDataArr['fb_id']) && !empty(trim($postDataArr['fb_id']))) {
			$fb_id = ((isset($postDataArr['fb_id']) && !empty(trim($postDataArr['fb_id'])) && (strlen($postDataArr['fb_id']) <= FB_ID_LENGTH))?trim($postDataArr['fb_id']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Facebook Id', FB_ID_LENGTH), 'Code' => FIELD_LENGTH)));
			$user_detail = $this->User_model->getLoginDetail(array("fb_id" => $fb_id));
			
			if (empty($user_detail)) {
				$this->response(array('error' => TRUE, 'message' => $this->lang->line('FB_ID_NOT_REGISTERED'), 'code' => FB_ID_NOT_REGISTERED));
			}
		} elseif(isset($postDataArr['twitter_id']) && !empty(trim($postDataArr['twitter_id']))) {
			$twitter_id = ((isset($postDataArr['twitter_id']) && !empty(trim($postDataArr['twitter_id'])) && (strlen($postDataArr['twitter_id']) <= FB_ID_LENGTH))?trim($postDataArr['twitter_id']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Twitter Id', FB_ID_LENGTH), 'Code' => FIELD_LENGTH)));
			$user_detail = $this->User_model->getLoginDetail(array("twitter_id" => $twitter_id));
			
			if (empty($user_detail)) {
				$this->response(array('error' => TRUE, 'message' => $this->lang->line('TWITTER_ID_NOT_REGISTERED'), 'code' => TWITTER_ID_NOT_REGISTERED));
			}
		} else {
			if (isset($postDataArr['password']) && !empty(trim($postDataArr['password'])) && isset($postDataArr['credentials_type']) && !empty(trim($postDataArr['credentials_type'])) && ((isset($postDataArr['email']) && !empty(trim($postDataArr['email'])) && $postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL) || ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_PHONE && isset($postDataArr['phone_number']) && !empty(trim($postDataArr['phone_number'])) && isset($postDataArr['country_code']) && !empty(trim($postDataArr['country_code']))))) {
				
				$password = ((strlen($postDataArr['password']) <= PASSWORD_LENGTH)?trim($postDataArr['password']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Password', PASSWORD_LENGTH), 'Code' => FIELD_LENGTH)));
				
				if ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL)	{
									
					$email = ((strlen($postDataArr['email']) <= EMAIL_LENGTH) && valid_email($postDataArr['email']))?trim($postDataArr['email']):$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_ERROR'), 'Code' => EMAIL_ERROR));
					$user_detail = $this->User_model->getLoginDetail(array("email" => $email));
					
				} elseif ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_PHONE) {
					
					$phone_number = ((strlen($postDataArr['phone_number']) <= PHONE_LENGTH))?trim($postDataArr['phone_number']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Phone Number', PHONE_LENGTH), 'code' => FIELD_LENGTH));
		
					$country_code = ((strlen($postDataArr['country_code']) <= COUNTRY_CODE_LENGTH))?trim($postDataArr['country_code']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Country Code', COUNTRY_CODE_LENGTH), 'code' => FIELD_LENGTH));
					
					$user_detail = $this->User_model->getLoginDetail(array("phone_number" => $phone_number, "country_code" => $country_code));
				}
				
				if (empty($user_detail)) {
					
					if ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL)	{
						
						$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_NOT_REGISTERED'), 'code' => EMAIL_NOT_REGISTERED));
						
					} elseif ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_PHONE) {
						
						$this->response(array('error' => TRUE, 'message' => $this->lang->line('PHONE_NOT_REGISTERED'), 'code' => PHONE_NOT_REGISTERED));
					}
					
				} elseif(!password_verify($password, $user_detail['password'])) {
					
					$this->response(array('error' => TRUE, 'message' => $this->lang->line('WRONG_PASSWORD'), 'code' => WRONG_PASSWORD));
				}
				unset($user_detail['password']);
			} else {
				$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
			}
		}
		
		if ($user_detail['status'] == USER_BLOCK) {
			$this->response(array('error' => TRUE, 'message' => $this->lang->line('USER_BLOCKED'), 'code' => USER_BLOCKED));
		}
		
		$access_token = bin2hex(openssl_random_pseudo_bytes(16));
		$this->Common_model->update_single('qe_user', array("access_token" => $access_token), array("where" => array('id' => $user_detail['user_id'])));
		$user_detail['access_token'] = $access_token;
		if (isset($user_detail['pin'])) {
			unset($user_detail['pin']);
		}
		if (isset($user_detail['password'])) {
			unset($user_detail['password']);
		}
		$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $user_detail));
	}
	
	/**
     * @SWG\Post(path="/users/logout/",
     *   tags={"User"},
     *   summary="Logout user from system",
     *   description="",
     *   operationId="logout",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Uaccesstoken",
     *     in="header",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Logout success"),
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	
	public function logout_post() 
	{
		$access_token = $this->getAccessToken();
        $userId = $this->checkLogin($access_token);
		$this->Common_model->update_single('qe_user', array("access_token" => NULL, "device_type" => NULL, 'device_token' => NULL), array("where" => array('id' => $userId)));
		$this->response(array('error' => '', 'code' => SUCCESS));
	}
	
	/**
     * @SWG\Post(path="/users/signUp/",
     *   tags={"User"},
     *   summary="Signup user into the system",
     *   description="",
     *   operationId="signUp",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="country_code",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone_number",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="fb_id",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="twitter_id",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="login success"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=433, description="Either Field is empty or length of value is not correct"),
     *   @SWG\Response(response=443, description="Unauthorized"),     
     *   @SWG\Response(response=435, description="Invalid Email"),
     *   @SWG\Response(response=437, description="Facebook ID already registered"),
     *   @SWG\Response(response=438, description="Email Already registered"),     
     *   @SWG\Response(response=434, description="Please enter the email address on which you have recieved the Referral Code."),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function signUp_post() 
    {
		$postDataArr = $this->post();
		
		if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		$required_params = array();
		
		$required_params['name'] = (isset($postDataArr['name']) && !empty(trim($postDataArr['name'])))?trim($postDataArr['name']):'';
		$required_params['email'] = (isset($postDataArr['email']) && !empty(trim($postDataArr['email'])))?trim($postDataArr['email']):'';
		$required_params['phone_number'] = (isset($postDataArr['phone_number']) && !empty(trim($postDataArr['phone_number'])))?trim($postDataArr['phone_number']):'';
		$required_params['country_code'] = (isset($postDataArr['country_code']) && !empty(trim($postDataArr['country_code'])))?trim($postDataArr['country_code']):'';
		
		$twitter_id = (isset($postDataArr['twitter_id']) && !empty(trim($postDataArr['twitter_id'])))?trim($postDataArr['twitter_id']):'';
		$fb_id = (isset($postDataArr['fb_id']) && !empty(trim($postDataArr['fb_id'])))?trim($postDataArr['fb_id']):'';
		$password = (isset($postDataArr['password']) && !empty(trim($postDataArr['password'])))?trim($postDataArr['password']):'';
		
		if (!$this->paramValidator($required_params)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		if (empty($password)) {
			if (empty($twitter_id) && empty($fb_id)) {
				$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
			}
		}
		
		$hashToStoreInDb = NULL;
		
		$name = ((strlen($required_params['name']) <= NAME_LENGTH))?$required_params['name']:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Name', NAME_LENGTH), 'code' => FIELD_LENGTH));
		
		$email = ((strlen($required_params['email']) <= EMAIL_LENGTH) && valid_email($required_params['email']))?$required_params['email']:$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_ERROR'), 'code' => EMAIL_ERROR));
		
		$phone_number = ((strlen($required_params['phone_number']) <= PHONE_LENGTH))?$required_params['phone_number']:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Phone Number', PHONE_LENGTH), 'code' => FIELD_LENGTH));
		
		$country_code = ((strlen($required_params['country_code']) <= COUNTRY_CODE_LENGTH))?$required_params['country_code']:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Country Code', COUNTRY_CODE_LENGTH), 'code' => FIELD_LENGTH));
		
		if (!empty($fb_id)) {
			$fb_id = (((strlen($fb_id) <= FB_ID_LENGTH))?$fb_id:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Facebook Id', FB_ID_LENGTH), 'code' => FIELD_LENGTH)));
			
			$user_detail = $this->User_model->getLoginDetail(array("fb_id" => $fb_id));
			
			if (!empty($user_detail)) {
				$access_token = bin2hex(openssl_random_pseudo_bytes(16));
				$this->Common_model->update_single('qe_user', array("access_token" => $access_token), array("where" => array('id' => $user_detail['user_id'])));
				$user_detail['access_token'] = $access_token;
				if (isset($user_detail['pin'])) {
					unset($user_detail['pin']);
				}
				if (isset($user_detail['password'])) {
					unset($user_detail['password']);
				}
				$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $user_detail));
			} else {
				$user_detail_user = $this->User_model->getLoginDetail(array("phone_number" => $phone_number, "email" => $email));
				if (!empty($user_detail_user)) {
					if (empty($user_detail_user['fb_id'])) {
						$access_token = bin2hex(openssl_random_pseudo_bytes(16));
						$this->Common_model->update_single('qe_user', array("access_token" => $access_token, 'fb_id' => $fb_id), array("where" => array('id' => $user_detail_user['user_id'])));
						$user_detail_user['access_token'] = $access_token;
						unset($user_detail_user['password']);
						if (isset($user_detail['pin'])) {
							unset($user_detail['pin']);
						}
						if (isset($user_detail['password'])) {
							unset($user_detail['password']);
						}
						$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $user_detail_user));
					} else {
						$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_ALREADY_REGISTERED'), 'code' => EMAIL_ALREADY_REGISTERED));
					}
				}
			}
		} elseif (!empty($twitter_id)) {
			$twitter_id = (((strlen($twitter_id) <= FB_ID_LENGTH))?$twitter_id:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Twitter id', FB_ID_LENGTH), 'code' => FIELD_LENGTH)));
			
			$user_detail = $this->User_model->getLoginDetail(array("twitter_id" => $twitter_id));
			if (!empty($user_detail)) {
				$access_token = bin2hex(openssl_random_pseudo_bytes(16));
				$this->Common_model->update_single('qe_user', array("access_token" => $access_token), array("where" => array('id' => $user_detail['user_id'])));
				$user_detail['access_token'] = $access_token;
				if (isset($user_detail['pin'])) {
					unset($user_detail['pin']);
				}
				if (isset($user_detail['password'])) {
					unset($user_detail['password']);
				}
				$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $user_detail));
			} else {
				$user_detail_user = $this->User_model->getLoginDetail(array("phone_number" => $phone_number, "email" => $email));
				if (!empty($user_detail_user)) {
					if (empty($user_detail_user['twitter_id'])) {
						$access_token = bin2hex(openssl_random_pseudo_bytes(16));
						$this->Common_model->update_single('qe_user', array("access_token" => $access_token, 'twitter_id' => $twitter_id), array("where" => array('id' => $user_detail_user['user_id'])));
						$user_detail_user['access_token'] = $access_token;
						unset($user_detail_user['password']);
						if (isset($user_detail['pin'])) {
							unset($user_detail['pin']);
						}
						if (isset($user_detail['password'])) {
							unset($user_detail['password']);
						}
						$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $user_detail_user));
					} else {
						$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_ALREADY_REGISTERED'), 'code' => EMAIL_ALREADY_REGISTERED));
					}
				}
			}
		} else {
			$password = ((strlen($password) <= PASSWORD_LENGTH)?$password:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Password', PASSWORD_LENGTH), 'code' => FIELD_LENGTH)));
			$hashToStoreInDb = password_hash($password, PASSWORD_BCRYPT);
			
		}
		
		$user_detail = $this->User_model->getLoginDetail(array("email" =>$email));
		if(!empty($user_detail)) {
			$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_ALREADY_REGISTERED'), 'code' => EMAIL_ALREADY_REGISTERED));
		}
		
		$user_detail = $this->User_model->getLoginDetail(array("phone_number" => $phone_number));
		if(!empty($user_detail)) {
			$this->response(array('error' => TRUE, 'message' => $this->lang->line('PHONE_NUMBER_ALREADY_REGISTERED'), 'code' => PHONE_NUMBER_ALREADY_REGISTERED));
		}
		
		$access_token = bin2hex(openssl_random_pseudo_bytes(16));
		$user_data['role_id'] = USER_ROLE;
		$user_data['name'] = $name;
		$user_data['password'] = $hashToStoreInDb;
		$user_data['email'] = $email;
		$user_data['access_token'] = $access_token;
		$user_data['mobile_number'] = $phone_number;
		$user_data['country_code'] = $country_code;
		$user_data['fb_id'] = $fb_id;
		$user_data['twitter_id'] = $twitter_id;
		$user_data['status'] = USER_ACTIVE;
		$user_data['phone_verify'] = PHONE_UNVERIFIED;
		$user_data['created'] = date('Y-m-d H:i:s');
		//Temporary Code, Later implement Twilio for the same
		$user_data['verify_otp'] = '1234';//rand(1001, 9999);
		$user_other_data['user_id'] = $this->Common_model->insert_single('qe_user', $user_data);
		$user_detail = $this->User_model->getLoginDetail(array("accesstoken" => $access_token));
		if (isset($user_detail['pin'])) {
			unset($user_detail['pin']);
		}
		if (isset($user_detail['password'])) {
			unset($user_detail['password']);
		}
		$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $user_detail));
	}
	
	/**
     * @SWG\Post(path="/users/deviceId/",
     *   tags={"User"},
     *   summary="Update Device Id and Device Token of user",
     *   description="",
     *   operationId="deviceId",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Uaccesstoken",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_type",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_token",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="success"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function deviceId_post()
	{
		$access_token = $this->getAccessToken();
        $userId = $this->checkLogin($access_token);
        $postDataArr = $this->post();
        
        if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		$device_type = ((isset($postDataArr['device_type']) && !empty($postDataArr['device_type']) && is_numeric($postDataArr['device_type']) && ($postDataArr['device_type'] == DEVICE_TYPE_ANDROID || $postDataArr['device_type'] == DEVICE_TYPE_IPHONE))?$postDataArr['device_type']:$this->response(array('error' => TRUE, 'message' => $this->lang->line('UNKNOWN_DEVICE_TYPE'), 'code' => UNKNOWN_DEVICE_TYPE)));
		
		$device_token = ((isset($postDataArr['device_token']) && !empty($postDataArr['device_token']) && (strlen($postDataArr['device_token']) <= DEVICE_TOKEN_LENGTH))?$postDataArr['device_token']:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Device Token', DEVICE_TOKEN_LENGTH), 'Code' => FIELD_LENGTH)));
		
		try {
			$this->db->trans_begin();
			$this->Common_model->update_single('qe_user', array("device_type" => NULL, 'device_token' => NULL), array("where" => array('device_token' => $device_token)));
			$this->Common_model->update_single('qe_user', array("device_type" => $device_type, 'device_token' => $device_token), array("where" => array('id' => $userId)));
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} catch(Exception $e) {
			$this->response(array('Error' => $this->lang->line('DB_ERROR'), 'Code' => DB_ERROR));
		}
		$this->response(array('Error' => '', 'Code' => SUCCESS));
	}
	
	/**
     * @SWG\Post(path="/users/forgotPassword/",
     *   tags={"User"},
     *   summary="Forgot Passsword Email",
     *   description="",
     *   operationId="forgotPassword",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="credentials_type",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="phone_number",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="country_code",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="success"),
     *   @SWG\Response(response=440, description="Email not registered"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=433, description="Either Field is empty or length of value is not correct"),
     *   @SWG\Response(response=442, description="User Blocked"),     
     *   @SWG\Response(response=213, description="Problem in sending Email"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function forgotPassword_post()
	{
		$postDataArr = $this->post();
		if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		
		if (isset($postDataArr['credentials_type']) && !empty(trim($postDataArr['credentials_type'])) && ((isset($postDataArr['email']) && !empty(trim($postDataArr['email'])) && $postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL) || ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_PHONE && isset($postDataArr['phone_number']) && !empty(trim($postDataArr['phone_number'])) && isset($postDataArr['country_code']) && !empty(trim($postDataArr['country_code']))))) {
			
			if ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL)	{
				
				$email = ((strlen($postDataArr['email']) <= EMAIL_LENGTH) && valid_email($postDataArr['email']))?trim($postDataArr['email']):$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_ERROR'), 'Code' => EMAIL_ERROR));
				
				$user_detail = $this->User_model->getLoginDetail(array("email" => $email));
				
			} elseif($postDataArr['credentials_type'] == CREDENTIALS_TYPE_PHONE) {
				$phone_number = ((strlen($postDataArr['phone_number']) <= PHONE_LENGTH))?trim($postDataArr['phone_number']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Phone Number', PHONE_LENGTH), 'code' => FIELD_LENGTH));
		
				$country_code = ((strlen($postDataArr['country_code']) <= COUNTRY_CODE_LENGTH))?trim($postDataArr['country_code']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Country Code', COUNTRY_CODE_LENGTH), 'code' => FIELD_LENGTH));
				
				$user_detail = $this->User_model->getLoginDetail(array("phone_number" => $phone_number, "country_code" => $country_code));
			}
			
			if (empty($user_detail)) {
					
				if ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL)	{
					
					$this->response(array('error' => TRUE, 'message' => $this->lang->line('EMAIL_NOT_REGISTERED'), 'code' => EMAIL_NOT_REGISTERED));
					
				} elseif ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_PHONE) {
					
					$this->response(array('error' => TRUE, 'message' => $this->lang->line('PHONE_NOT_REGISTERED'), 'code' => PHONE_NOT_REGISTERED));
				}
				
			} else {
				
				if ($user_detail['status'] == USER_BLOCK) {
					
					$this->response(array('Error' => $this->lang->line('USER_BLOCKED'), 'Code' => USER_BLOCKED));
					
				}
				//Later Make it random
				$forgot_token = bin2hex(openssl_random_pseudo_bytes(16));
				$otp = "1234";//rand(1000,9999);
				$this->Common_model->update_single('qe_user', array("verify_otp" => $otp, "forgot_token" => $forgot_token), array("where" => array('id' => $user_detail['user_id'])));
				if ($postDataArr['credentials_type'] == CREDENTIALS_TYPE_EMAIL)	{
					$param['otp'] = $otp;
					$param['name'] = $user_detail['name'];
					try{
						$this->Common_model->sendmailnew($user_detail['email'], 'Reset Password Mail', '', true, $param, 'forgotPassword');
					} catch(exception $e) {
						$this->response(array('Error' => $this->lang->line('EMAIL_ERROR'), 'Code' => EMAIL_ERROR));
					}
				}
				$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => array("token" => $forgot_token)));
			}
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
	}
	
	/**
     * @SWG\Post(path="/users/validateOtp/",
     *   tags={"User"},
     *   summary="Forgot Passsword Validate OTP",
     *   description="",
     *   operationId="validateOtp",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="otp",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="success"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=433, description="Either Field is empty or length of value is not correct"),
     *   @SWG\Response(response=444, description="Invalid OTP"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function validateOtp_post()
	{
		$access_token = $this->getAccessToken();
        $userId = $this->checkLogin($access_token);
		$postDataArr = $this->post();
		if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		if (isset($postDataArr['otp']) && !empty(trim($postDataArr['otp']))) {	
			$otp = (((strlen($postDataArr['otp']) <= OTP_LENGTH) && is_numeric($postDataArr['otp']))?trim($postDataArr['otp']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'OTP', OTP_LENGTH), 'code' => FIELD_LENGTH)));
			
			$user_detail = $this->User_model->getLoginDetail(array("user_id" => $userId, "verify_otp" => $otp));
			
			if (empty($user_detail)) {
				$this->response(array('error' => TRUE, 'message' => $this->lang->line('INVALID_OTP'), 'code' => INVALID_OTP));
			} else {
				//$forgot_token = bin2hex(openssl_random_pseudo_bytes(16));
				$this->Common_model->update_single('qe_user', array("verify_otp" => NULL, "phone_verify" => PHONE_VERIFIED), array("where" => array('phone_number' => $postDataArr['phone_number'])));
				//$this->response(array('error' => FALSE, 'code' => SUCCESS, 'Data' => array("forgot_token" => $forgot_token)));
				$this->response(array('error' => FALSE, 'code' => SUCCESS));
			}
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
	}
	
	/**
     * @SWG\Post(path="/users/forgotChangePassword/",
     *   tags={"User"},
     *   summary="Forgot Change Password",
     *   description="",
     *   operationId="forgotChangePassword",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="token",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="success"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=433, description="Either Field is empty or length of value is not correct"),
     *   @SWG\Response(response=445, description="Invalid Forgot Token"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function forgotChangePassword_post()
	{
		$postDataArr = $this->post();
		if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		if (isset($postDataArr['token']) && !empty(trim($postDataArr['token'])) && isset($postDataArr['password']) && !empty(trim($postDataArr['password']))) {
			$forgot_token = ((strlen($postDataArr['token']) <= FORGOT_TOKEN_LENGTH)?trim($postDataArr['token']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Forgot Token', FORGOT_TOKEN_LENGTH), 'code' => FIELD_LENGTH)));
			
			$password = ((strlen($postDataArr['password']) <= PASSWORD_LENGTH)?trim($postDataArr['password']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Password', PASSWORD_LENGTH), 'code' => FIELD_LENGTH)));
			
			$user_detail = $this->User_model->getLoginDetail(array("forgot_token" => $forgot_token));
			if (empty($user_detail)) {
				$this->response(array('error' => TRUE, 'message' => $this->lang->line('INVALID_FORGOT_TOKEN'), 'code' => INVALID_FORGOT_TOKEN));
			} else {
				$hashToStoreInDb = password_hash($password, PASSWORD_BCRYPT);
				$this->Common_model->update_single('qe_user', array("password" => $hashToStoreInDb, "forgot_token" => NULL), array("where" => array('forgot_token' => $forgot_token)));
				$this->response(array('error' => FALSE, 'code' => SUCCESS));
			}
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
	}
	
	/**
     * @SWG\Post(path="/users/validateForgotOtp/",
     *   tags={"User"},
     *   summary="Validate Forgot Otp",
     *   description="",
     *   operationId="validateForgotOtp",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="token",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="otp",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="success"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=433, description="Either Field is empty or length of value is not correct"),
     *   @SWG\Response(response=445, description="Invalid Forgot Token"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function validateForgotOtp_post()
	{
		$postDataArr = $this->post();
		if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		if (isset($postDataArr['token']) && !empty(trim($postDataArr['token'])) && isset($postDataArr['otp']) && !empty(trim($postDataArr['otp']))) {
			
			$forgot_token = ((strlen($postDataArr['token']) <= FORGOT_TOKEN_LENGTH)?trim($postDataArr['token']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Forgot Token', FORGOT_TOKEN_LENGTH), 'code' => FIELD_LENGTH)));
			
			$otp = (((strlen($postDataArr['otp']) <= OTP_LENGTH) && is_numeric($postDataArr['otp']))?trim($postDataArr['otp']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'OTP', OTP_LENGTH), 'code' => FIELD_LENGTH)));
			
			$user_detail = $this->User_model->getLoginDetail(array("forgot_token" => $forgot_token, "verify_otp" => $otp));
			
			if (empty($user_detail)) {
				
				$this->response(array('error' => TRUE, 'message' => $this->lang->line('INVALID_FORGOT_TOKEN'), 'code' => INVALID_FORGOT_TOKEN));
				
			} else {
				
				$forgot_token = bin2hex(openssl_random_pseudo_bytes(16));
				$this->Common_model->update_single('qe_user', array("verify_otp" => NULL, "forgot_token" => $forgot_token), array("where" => array('id' => $user_detail['user_id'])));
				//Security Ques is static FALSE because right now subscription module not integrated
				$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => array("token" => $forgot_token, "security_ques" => FALSE)));
				
			}
			
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
	}
	
	/**
     * @SWG\Get(path="/users/securityQuestion/",
     *   tags={"User"},
     *   summary="Get Security Question",
     *   description="",
     *   operationId="logout",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function securityQuestion_get()
	{
		$question_data = $this->Common_model->fetch_data('qe_questions', 'id as question_id, questions as question', array());
		$this->response(array('error' => FALSE, 'code' => SUCCESS, 'data' => $question_data));
	}
	
	public function validateSecurityQuestion_post()
	{
		
	}
	
	/**
     * @SWG\Post(path="/users/setPin/",
     *   tags={"User"},
     *   summary="Change Password of User",
     *   description="",
     *   operationId="setPin",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Uaccesstoken",
     *     in="header",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="pin",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function setPin_post()
	{
		$access_token = $this->getAccessToken();
        $userId = $this->checkLogin($access_token);
        $postDataArr = $this->post();
        if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		if (isset($postDataArr['token']) && !empty(trim($postDataArr['token'])) && isset($postDataArr['otp']) && !empty(trim($postDataArr['otp']))) {
			
			$pin = (((strlen($postDataArr['pin']) <= PIN_LENGTH) && is_numeric($postDataArr['pin']))?trim($postDataArr['pin']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'pin', PIN_LENGTH), 'code' => FIELD_LENGTH)));
			
			$hashToStoreInDb = password_hash($pin, PASSWORD_BCRYPT);
			$this->Common_model->update_single('qe_user', array("pin" => $hashToStoreInDb), array("where" => array('id' => $userId)));
			$this->response(array('error' => FALSE, 'code' => SUCCESS));
			
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
        
	}
	
	/**
     * @SWG\Post(path="/users/validatePin/",
     *   tags={"User"},
     *   summary="Validate Pin of User",
     *   description="",
     *   operationId="validatePin",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Uaccesstoken",
     *     in="header",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="validate_pin_type",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="pin",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="device_token",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   @SWG\Response(response=432, description="Require parameter missing"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function validatePin_post()
	{
		$postDataArr = $this->post();
		
		if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		if (isset($postDataArr['validate_pin_type']) && !empty($postDataArr['validate_pin_type']) && ($postDataArr['validate_pin_type'] == VALIDATE_PIN_LOGIN || $postDataArr['validate_pin_type'] == VALIDATE_PIN_USER)) {
			
			$pin = (((strlen($postDataArr['pin']) <= PIN_LENGTH) && is_numeric($postDataArr['pin']))?trim($postDataArr['pin']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'pin', PIN_LENGTH), 'code' => FIELD_LENGTH)));
			
			if ($postDataArr['validate_pin_type'] == VALIDATE_PIN_LOGIN) {
				
				if (isset($postDataArr['device_token']) && !empty($postDataArr['device_token'])) {
					
					$device_token = ((strlen($postDataArr['device_token']) <= DEVICE_TOKEN_LENGTH)?$postDataArr['device_token']:$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Device Token', DEVICE_TOKEN_LENGTH), 'Code' => FIELD_LENGTH)));
					
					$user_detail = $this->User_model->getLoginDetail(array("device_token" => $device_token));
					
					if (!password_verify($password, $user_detail['pin'])) {
						$this->response(array('error' => TRUE, 'message' => $this->lang->line('WRONG_PIN'), 'code' => WRONG_PIN));
					} else {
						$this->response(array('error' => FALSE, 'code' => SUCCESS));
					}
					
					
				} else {
					
					$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
					
				}
				
				
			} elseif ($postDataArr['validate_pin_type'] == VALIDATE_PIN_USER) {
				
				$access_token = $this->getAccessToken();
				$userId = $this->checkLogin($access_token);
				$user_detail = $this->User_model->getLoginDetail(array("user_id" => $userId));
				
				if (!password_verify($password, $user_detail['pin'])) {
					$this->response(array('error' => TRUE, 'message' => $this->lang->line('WRONG_PIN'), 'code' => WRONG_PIN));
				} else {
					$this->response(array('error' => FALSE, 'code' => SUCCESS));
				}
				
			}
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
	}
	
	/**
     * @SWG\Post(path="/users/changePassword/",
     *   tags={"User"},
     *   summary="Change Password of User",
     *   description="",
     *   operationId="logout",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Uaccesstoken",
     *     in="header",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="old_password",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="new_password",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Logout success"),
     *   @SWG\Response(response=443, description="Unauthorized"),
     *   @SWG\Response(response=441, description="Wrong Password"),
     *   security={
     *         {
     *             "basicAuth": {"Username: ", "Password: "}
     *         }
     *     },
     * )
     */
	public function changePassword_post()
	{
		$access_token = $this->getAccessToken();
        $userId = $this->checkLogin($access_token);
        $postDataArr = $this->post();
        
        if (empty($postDataArr)) {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
		
		if (isset($postDataArr['old_password']) && !empty(trim($postDataArr['old_password'])) && isset($postDataArr['new_password']) && !empty(trim($postDataArr['new_password']))) {
		
			$old_password = ((strlen($postDataArr['old_password']) <= PASSWORD_LENGTH)?trim($postDataArr['old_password']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'Old Password', PASSWORD_LENGTH), 'code' => FIELD_LENGTH)));
			
			$new_password = (((strlen($postDataArr['new_password']) <= PASSWORD_LENGTH))?trim($postDataArr['new_password']):$this->response(array('error' => TRUE, 'message' => sprintf($this->lang->line('FIELD_LENGTH'), 'New Password', PASSWORD_LENGTH), 'code' => FIELD_LENGTH)));
			
			$user_detail = $this->User_model->getLoginDetail(array("user_id" => $userId));
			
			if (!password_verify($old_password, $user_detail['password'])) {
				$this->response(array('error' => TRUE, 'message' => $this->lang->line('WRONG_PASSWORD'), 'code' => WRONG_PASSWORD));
			}
			
			$hashToStoreInDb = password_hash($new_password, PASSWORD_BCRYPT);
			
			$this->Common_model->update_single('qe_user', array("password" => $hashToStoreInDb), array("where" => array('id' => $userId)));
			$this->response(array('error' => FALSE, 'code' => SUCCESS));
		} else {
			$this->response(array('error' => TRUE, 'code' => MISSING_PARAMETER, 'message' => $this->lang->line('PARAMETER_MISSING')));
		}
	}
}
