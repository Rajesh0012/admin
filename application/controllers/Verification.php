<?php

class Verification extends MX_Controller {
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Common_model');
	}
	
	public function email_verify($token = '')
	{
		if(!empty($token)) {
			$user_detail = $this->User_model->getLoginDetail(array("email_verification_token" => $token));
			if (!empty($user_detail)) {
				if (strtotime(date('Y-m-d H:i:s')) <= strtotime($user_detail['email_verification_expiry'])) {
					$this->Common_model->update_single('qe_user', array("email_verification_expiry" => NULL, 'email_verification_token' => NULL, "email_verify" => EMAIL_VERIFIED), array("where" => array('id' => $user_detail['user_id'])));
					echo "Email Verified";
				}
			} else {
				echo "Link Expired";
			}
		} else {
			echo "Wrong Request";
		}
	}
}
