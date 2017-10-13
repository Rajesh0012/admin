<?php

class User_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->library('session');
    }
    
    public function getLoginDetail($condition = array()) {
		$this->db->select('u.id as user_id, access_token, email, name, mobile_number as phone_number, country_code, created, status, twitter_id, fb_id, phone_verify, 0 as subscription, IFNULL(pin, "") as pin', false);
		$this->db->from('qe_user as u');
        $this->db->where('u.status !=', USER_DELETED);
        
        if (isset($condition['access_token']) && !empty($condition['access_token'])) {
			$this->db->where('u.access_token', $condition['access_token']);
		}
		
        if (isset($condition['user_id']) && !empty($condition['user_id'])) {
			$this->db->select("IFNULL(password, '') as password");
			$this->db->where('u.id', $condition['user_id']);
		}
        
        /*if (isset($condition['email']) && !empty($condition['email']) && isset($condition['fb_id']) && !empty($condition['fb_id'])) {
			$this->db->select("IFNULL(fb_id, '') as fb_id");
			$this->db->where('(u.email = "'.$condition['email'].'" or fb_id = "'.$condition['fb_id'].'")');
			return $this->db->get()->result_array();
		}*/
		
        if (isset($condition['phone_number']) && !empty($condition['phone_number'])) {
			$this->db->select("IFNULL(password, '') as password");
			$this->db->where('mobile_number', $condition['phone_number'], false);
		}
		
        if (isset($condition['country_code']) && !empty($condition['country_code'])) {
			$this->db->where('country_code', $condition['country_code'], false);
		}
		
        if (isset($condition['forgot_token']) && !empty($condition['forgot_token'])) {
			$this->db->where('forgot_token', $condition['forgot_token']);
		}
		
        if (isset($condition['device_token']) && !empty($condition['device_token'])) {
			$this->db->where('device_token', $condition['device_token']);
		}
		
		if (isset($condition['fb_id']) && !empty($condition['fb_id'])) {
			$this->db->where('fb_id', $condition['fb_id']);
		}
		
		if (isset($condition['twitter_id']) && !empty($condition['twitter_id'])) {
			$this->db->where('twitter_id', $condition['twitter_id']);
		}
		
		if (isset($condition['verify_otp']) && !empty($condition['verify_otp'])) {
			$this->db->where('verify_otp', $condition['verify_otp']);
		}
		
		if (isset($condition['email']) && !empty($condition['email'])) {
			$this->db->select("IFNULL(password, '') as password");
			$this->db->where('u.email', $condition['email']);
		}
        return $this->db->get()->row_array();
	}

	public function get_membership_plan()
    {

        $this->db->select('s.id as subscription_id,subscription_name,subscription_validity,subscription_type,price, GROUP_CONCAT(features, ";;;", features_type SEPARATOR ":::") as features', false);
        $this->db->from('qe_subscription s');
        $this->db->join('qe_subscription_fetaures sf', 's.id = sf.subscription_id', 'LEFT');
        $this->db->group_by('s.id');
        $data=$this->db->get();

        return $data->result_array();

    }

}
