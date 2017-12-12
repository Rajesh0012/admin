<?php

class Events extends MX_Controller {
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Common_model');
        //$this->load->model('Event_model');
	}
	
	/*
	 * Below Function is running via cron job, Below function send email, sms, push etc.
	 */
	public function send()
	{
		$event_data = $this->Common_model->fetch_data('qe_events', 'id, type, data, status', array("where" => array("status" => EVENT_STATUS_PENDING)));
		for ($i = 0;$i < count($event_data);$i++) {
			
			if ($event_data[$i]['type'] == EMAIL_EVENT) {
				
				
				
			} elseif ($event_data[$i]['type'] == PHONE_NUMBER_EVENT) {
				
				
				
			} elseif ($event_data[$i]['type'] == PUSH_EVENT) {
				
				
				
			}
		}
	}
}
