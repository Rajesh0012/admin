<?php

class Common_model extends CI_Model {

    public $finalrole = array();

    public function __construct() {
        $this->load->database();
        $this->load->library('session');
    }

    /**
     * Fetch data from any table based on different conditions
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	array
     * @return	bool
     */
    public function fetch_data($table, $fields = '*', $conditions = array(), $returnRow = false) {
        //Preparing query
        $this->db->select($fields);
        $this->db->from($table);

        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        $query = $this->db->get();
         /*if($table == 'user'){
          print_r($query);die;
          } */
        //Return
        return $returnRow ? $query->row_array() : $query->result_array();
    }
    
    
    
    
    
    
    public function verifEmail($userInfo) {
        if (!empty($userInfo['adminuseremail']) && !empty($userInfo['adminuseremail'])) {
            $subject = "E4CC Forgot Password";
            $resetLink = base_url() . 'Admin/resetpassword?token=' . base64_encode($userInfo['adminuserid']) . '&e_id=' . base64_encode($userInfo['adminuseremail']);

            $message = 'Hello ' . '! <br><br>
                   Please click the link for verifying your emailId in App.<br>
                   <a href=' . $resetLink . '>Click here</a> .<br><br>
                   Thank you,<br>
                   Team E4CC App';
            $send = $this->sendmail($userInfo['adminuseremail'], $subject, $message);
            return $send;
        } else {
            return false;
        }
    }
    
    
    
    
    
    

    /**
     * Insert data in DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    public function insert_single($table, $data = array()) {
        //Check if any data to insert
        if (count($data) < 1) {
            return false;
        }

        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Insert batch data
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	array
     * @param	bool
     * @return	bool
     */
    public function insert_batch($table, $defaultArray, $dynamicArray = array(), $updatedTime = false) {
        //Check if default array has values
        if (count($dynamicArray) < 1) {
            return false;
        }

        //If updatedTime is true
        if ($updatedTime) {
            $defaultArray['UpdatedTime'] = time();
        }

        //Iterate it
        foreach ($dynamicArray as $val) {
            $updates[] = array_merge($defaultArray, $val);
        }
        return $this->db->insert_batch($table, $updates);
    }

    /**
     * Delete data from DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    public function delete_data($table, $conditions = array()) {
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->delete($table);
    }

    /**
     * Handle different conditions of query
     *
     * @access	public
     * @param	array
     * @return	bool
     */
    private function condition_handler($conditions) {
        //Where
        if (array_key_exists('where', $conditions)) {

            //Iterate all where's
            foreach ($conditions['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

        //Where OR
        if (array_key_exists('or_where', $conditions)) {

            //Iterate all where or's
            foreach ($conditions['or_where'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
        }

        //Where In
        if (array_key_exists('where_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_in'] as $key => $val) {
                $this->db->where_in($key, $val,false);
            }
        }

        //Where Not In
        if (array_key_exists('where_not_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_not_in'] as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        //Having
        if (array_key_exists('having', $conditions)) {
            $this->db->having($conditions['having']);
        }

        //Group By
        if (array_key_exists('group_by', $conditions)) {
            $this->db->group_by($conditions['group_by']);
        }

        //Order By
        if (array_key_exists('order_by', $conditions)) {

            //Iterate all order by's
            foreach ($conditions['order_by'] as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }

        //Order By
        if (array_key_exists('like', $conditions)) {

            //Iterate all likes
            foreach ($conditions['like'] as $key => $val) {
                $this->db->like($key, $val);
            }
        }

        //Limit
        if (array_key_exists('limit', $conditions)) {

            //If offset is there too?
            if (count($conditions['limit']) == 1) {
                $this->db->limit($conditions['limit'][0]);
            } else {
                $this->db->limit($conditions['limit'][0], $conditions['limit'][1]);
            }
        }
    }

    /**
     * Update Batch
     *
     * @access	public
     * @param	string
     * @param	array
     * @return	boolean
     */
    public function update_batch_data($table, $data, $key) {
        
        return $this->db->update_batch($table, $data, $key);
    }

    /**
     * Update details in DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	array
     * @return	string
     */
    public function update_single($table, $updates, $conditions = array()) {
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->update($table, $updates);
    }

    /**
     * Count all records
     *
     * @access	public
     * @param	string
     * @return	array
     */
    public function fetch_count($table, $conditions = array()) {
        $this->db->from($table);
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->count_all_results();
    }

    /**
     * For sending mail
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	string
     * @param	boolean
     * @return	array
     */
    public function sendmail($email, $subject, $message, $single = true) {
        if ($single == true) {
            $this->load->library('email');
        }

        $this->config->load('email');
        $this->email->from($this->config->item('from'), $this->config->item('from_name'));
        $this->email->reply_to($this->config->item('repy_to'), $this->config->item('reply_to_name'));
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        return $this->email->send() ? true : false;
    }

//	public function sendmailnew($email,$subject,$message=false, $single = true,$param=false,$templet=false)
//    {
//            if ($single == true) {
//                    $this->load->library('email');
//            }
//
//            $this->config->load('email');
//            $this->email->from($this->config->item('from'), $this->config->item('from_name'));
//            $this->email->reply_to($this->config->item('repy_to'), $this->config->item('reply_to_name'));
//            $this->email->to($email);
//            $this->email->subject($subject);
//            if($param&&$templet){
//            $body = $this->load->view('mail/'.$templet,$param,TRUE);
//            $this->email->message($body);
//            }else{
//            $this->email->message($message);
//            }
//            return $this->email->send() ? true : false;
//    }

    function mcrypt_data($input) {
        /* Return mcrypted data */
        $key1 = "ShareSpark";
        $key2 = "Org";
        $key = $key1 . $key2;
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $input, MCRYPT_MODE_CBC, md5(md5($key))));
        //var_dump($encrypted);
        return $encrypted;
    }

    function demcrypt_data($input) {
        /* Return De-mcrypted data */
        $key1 = "ShareSpark";
        $key2 = "Org";
        $key = $key1 . $key2;
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decrypted;
    }

    function bcrypt_data($input) {
        $salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
        $hash = crypt($input, '$2a$12$' . $salt);
        return $hash;
    }

    public function simplify_array($array, $key) {
        $returnArray = array();
        foreach ($array as $val) {
            $returnArray[] = $val[$key];
        }
        return $returnArray;
    }

    //Validate date
    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
   
    
	function load_views($customView, $data = array()){
		$data['admin_notification'] = $this->fetchAdminNotification();    
		$this->load->view('common/header', $data);        
		$this->load->view($customView, $data);         
		$this->load->view('common/footer', $data);   
	}
	
	function fetchAdminNotification() {
		$this->db->select("message, data");
		$this->db->from('e_notification');
		$this->db->where('admin_is_read', ADMIN_NOTIFICATION_UNREAD);
		$this->db->where('notification_for', ADMIN);
		return $this->db->get()->result_array();
	}
    
    
    
    
    
    /**
     * Handle Pagination
     *
     * @access	public
     */
    public function handlePagination($totalRows) {

        //Load Pagination Library
        $this->load->config('pagination');
        $this->load->library('pagination');

        //First validate if there are any rows
        if ($totalRows > 0) {

            //Basic Pagination Config
            $finalSegment = $this->uri->segment(2);
            $config['per_page'] = $this->config->item('per_page_' . $finalSegment);
            $showMore = $this->input->get('show_more');
            $pageNumber = (!empty($showMore) and is_numeric($showMore)) ? $showMore - 1 : 0;
            $start = $config['per_page'] * $pageNumber;
            $config['total_rows'] = $totalRows;

            //Handle get params
            $additionalParams = '';
            $get = count($_GET) > 0 ? $_GET : array();
            $pageNumberKey = $this->config->item('query_string_segment');
            if (array_key_exists($pageNumberKey, $get)) {
                unset($get[$pageNumberKey]);
            }
            if (count($get) > 0) {
                $additionalParams = http_build_query($get);
            }
            $config['base_url'] = base_url() . 'index.php/view/' . $finalSegment . '?' . $additionalParams;
            $config['full_tag_open'] = '<div class="row"><div class="col-sm-5"><div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing ' . ($start + 1) . ' to ' . ($start + $config['per_page']) . ' of ' . $totalRows . ' entries</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="example2_paginate"><ul class="pagination">';
            $this->pagination->initialize($config);

            return array(
                'totalRecords' => $config['total_rows'],
                'startCount' => $start
            );
        } else {
            return array(
                'totalRecords' => 0,
                'startCount' => 0
            );
        }
    }

    /**
     * Logout User
     *
     * @access	public
     */
    public function logout1() {
        $array_items = array('adminUserId', 'adminUserName', 'adminUserEmail');
        $this->session->unset_userdata($array_items);
        // $this->session->unset($_SESSION['user_id']);
        $this->session->sess_destroy();
        session_destroy();

        //echo '<pre>'; print_r($_SESSION); die;
        redirect(base_url() . 'admin/login');
    }

    public function randomstring($length) {
        return $a = mt_rand(1000, 9999);
    }

    public function sendSMS($toArray, $text, $values = array()) {

        /* Send SMS using PHP */

        //Your authentication key
        $authKey = "112806AdtmkKVJ57333318";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = "919015347316";

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "777777";

        //Your message to send, Add URL encoding here.
        $message = urlencode("Test message");

        //Define route
        $route = "default";
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url = "https://control.msg91.com/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        echo $output;
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

	function sendIphonePushMessage($deviceToken, $payload)
    {

            $date = @strtotime(date('Y-m-d'));
            $data['aps'] = $payload;
            $apnsHost = 'gateway.sandbox.push.apple.com';
            //$apnsHost = 'gateway.push.apple.com';
            $apnsPort = '2195';

            //$apnsCert = getcwd().'/public/ckpem/ROVO_dev.pem'; // this is for development mode (development mode)
            $apnsCert = getcwd().'/public/ckpem/pushcertdevelopment.pem'; // this is for production mode (distribution mode)
            //$passphrase = '1234';

            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
            //stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            //$fp = stream_socket_client( $apnsHost, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            $fp = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            //var_dump($fp); die;
            if (!$fp)
                return false;


            $sec_payload = json_encode($data);
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($sec_payload)) . $sec_payload;
            // Send it to the server
            $result = @fwrite($fp, $msg, strlen($msg));
            if($result){
                    //echo "true";
                    return true;
            }else {
                //print $deviceToken.'=========';
                    //echo "false";
                    return false;
            }
            fclose($fp);
	}


    public function andriodPush($deviceToken, $payload) {

        ini_set('display_errors', '1');
        $registrationIDs = array($deviceToken);

        $apiKey = 'AIzaSyDhFhYukI2Uj1RdiD-cAOBiXjc6cG4thpU'; //Please change API Key
        $url = 'https://android.googleapis.com/gcm/send';
        $push_data['payload'] = $payload;
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => $push_data,
        );
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        $u = curl_setopt($ch, CURLOPT_URL, $url);
        $p = curl_setopt($ch, CURLOPT_POST, true);
        $f = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $h = curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $t = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $c = curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $j = curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $jsonn = json_encode($fields);
        $result = curl_exec($ch);
        curl_close($ch);
        //echo "<pre>"; print_r($result); die;
        return $result;
    }

    public function checkParameters($arrdata) {
        foreach ($arrdata as $key => $ar) {
            if ($ar[$key] == '') {

                return false;
            }
        }
    }

    //to validate email
    public function validate_email($e) {
        return (bool) preg_match("`^[a-z0-9!#$%&'*+\/=?^_\`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_\`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$`i", trim($e));
    }

    public function encrypt($text, $salt, $isBaseEncode = true) {
        if ($isBaseEncode) {
            return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        } else {
            return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }
    }

    public function sendMailToUser($email, $message, $subject = 'No Subject', $from = FROM, $replyTo = NO_REPLY) {
        $extraKey = '-f' . $replyTo;

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . ' <' . $replyTo . '>' . "\r\n";

        if (is_array($message)) {
            $message = json_encode($message);
        }

        return mail($email, $subject, $message, $headers, $extraKey);

		/*$config = Array(
						'protocol' => 'smtp',
						'smtp_host' => 'mail.applaurels.com',
						'smtp_port' => 25,
						'smtp_user' => 'noreply@applaurels.com',
						'smtp_pass' => 'noreply@321',
						'mailtype'  => 'html',
						'charset'   => 'iso-8859-1'
					);
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		// Sender email address
		$this->email->from(NO_REPLY, FROM);
		// Receiver email address
		$this->email->to($email);
		// Subject of email
		$this->email->subject($subject);
		// Message in email
		$this->email->message($message);

		$result = $this->email->send();*/






    }


    /**
        * @function        getUserInfoByTable
        * @author          Pramod
        * @description     get user details
        * @param           $where
        * @data            18-11-2016
        * @return          boolean
        */
        public function getUserInfoByTable($table,$Id,$idColumn)
        {
            $this->db->select ('t.userId,t.fullName,t.email,u.deviceType,u.deviceToken,u.notificationSetting')
                        ->from($table.' as t')
                        ->join('user as u', 'u.userId = t.userId', 'LEFT');


            if($Id)
            {
                $this->db->where('t.'.$idColumn, $Id);
            }

            $query = $this->db->get();
            //print_r($query);die;
            return $query->row_array();

        }


    /**
     * @name  fetch_using_join
     * @description fetch data from join
     * @param string $select
     * @param string $from
     * @param string $joinCondition
     * @param string $joinType
     * @param string $where
     * @return arrray
     */
    public function fetch_using_join($select ,$from ,$join, $where , $asArray = NULL , $offset=NULL , $orderBy = NULL){

        $this->db->select($select,FALSE);
        $this->db->from($from);
        //print_r($join);exit;
        for ($i = 0; $i < count($join); $i++) {
            $this->db->join($join[$i]["table"] , $join[$i]["condition"] , $join[$i]["type"]);
        }
        if (count($where) > 0) {
            $this->condition_handler($where);
        }
        if (isset($orderBy['order']) && $orderBy !== NULL) {
            $this->db->order_by($orderBy["order"], $orderBy["sort"]);
        }

        if ($offset!==NULL) {
            $this->db->limit(PAGINATION_LIMIT, $offset);
        }
        $query = $this->db->get();
        return ($asArray!==NULL) ? $query->row() : $query->result_array();
    }
    
    /**
     * @name rawquery
     * @access public
     * @description  Performs raw query. Optionally gives in array or object format
     * @return array/object
     */
    public function rawquery( $data , $resultArray = NULL ){
        $query = $this->db->query($data);
        return ($resultArray!==NULL) ? $query->result_array() : $query->row() ;
    }

    /**
     * @name uploadfile
     * @param type $filename
     * @param type $filearr
     * @param type $restype
     * @param type $foldername
     * @return boolean
     */
    public function uploadfile($filename='', $filearr, $restype = 'name',$foldername='' , $allowedType = NULL){

        if (!is_dir(COMMON_UPLOAD_PATH . '/' . $foldername)) {
            mkdir(COMMON_UPLOAD_PATH . '/' . $foldername);
            chmod(COMMON_UPLOAD_PATH . '/' . $foldername, 0755);
        }

        if ($filearr[$filename]['name'] != '') {
            $config['upload_path'] = COMMON_UPLOAD_PATH.$foldername;
            if (!empty($allowedType)) {
                $config['allowed_types'] = $allowedType;
            }else{
                $config['allowed_types'] = '*';
            }
            $new_name = date('Y/m/d').'_'.time().'_'.$filearr[$filename]['name'];
            $config['file_name'] = $new_name;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload($filename)){
                $res = $this->upload->data();
                if($restype == 'name'){
                    unset($foldername);
                    return $res['file_name'];
                }elseif($restype == 'url'){
                    return COMMON_FILE_URL.$foldername.'/'.$res['file_name'];
                }
            } else {
                return false;
            }

        }
    }
    /**
         * @name createvideothumb
         * @param type $vidurl
         * @param type $restype
         * @param type $foldername
         * @return string
         */
    public function createvideothumb($vidurl, $restype = 'name', $foldername){

         $newthumbnail = time().'_video_thumbnail.jpg';
         $thumbnail = COMMON_UPLOAD_PATH.$foldername.'/'.$newthumbnail;

         // shell command [highly simplified, please don't run it plain on your script!]
         shell_exec("ffmpeg -i $vidurl -deinterlace -an -ss 11 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");

         if($restype == 'name'){
            return $newthumbnail;
         }else if($restype == 'url'){
            return COMMON_FILE_URL.$foldername.'/'.$newthumbnail;
         }
    }

    /**
     * @name createImagethumb
     * @param type $filename
     * @param type $restype
     * @param type $foldername
     * @return string
     */
    public function createImagethumb($filename, $restype = 'name',$foldername){

         $newthumbnail = date('Y/m/d').time().'_image_thumbnail.jpg';
         $thumbnail = COMMON_UPLOAD_PATH.$foldername.'/'.$newthumbnail;

            $config_manip = array(
                'image_library' => 'gd2',
                'source_image' => COMMON_UPLOAD_PATH.$foldername.'/'.$filename,
                'new_image' => $thumbnail,
                'maintain_ratio' => False,
                'create_thumb' => False,
                'width' => 100,
                'height' => 100
            );
            $this->load->library('image_lib');
            $this->image_lib->initialize($config_manip);
            //$this->load->library('image_lib', $config_manip);

           if($this->image_lib->resize()){
                return $newthumbnail;
           }
           $this->image_lib->clear();
    }



    /**
     * @name  insertAll
     * @description function for insert_batch
     * @param string $table
     * @param array $data
     * @return boolean
     */
    public function insertAll($table, $data){
        return $this->db->insert_batch($table, $data);
    }

    public function sendFCMNotification($devices,$message) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
            'registration_ids' => $devices,
            'data' => $message,
        );
        $data =json_encode($fields);
        $headers = array (
                'Authorization: key=' . "AAAAF7Ip-2I:APA91bEWcPV7JebecqFGRvUVitLJbIgc96qVkjoregT45P116DvYuLi0Q6ELiekaP9trQHe5wLmmB7rTnl_bRS9VnmAEreDkOARFG2-cNHvxMmLzlTfseN6g1InO0ck_SDYu_PRAu5oY",
                'Content-Type: application/json'
        );

        $ch = curl_init ();
        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);
        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);
        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //finally executing the curl request
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        //Now close the connection
        curl_close($ch);
        //and return the result


        return $result;
    }

    /**
         *
         * @param type $to
         * @param type $body
         */

    public function sendsmsbytwillio($To,$message){
        $To=$To;
        $from = "+12016764982
";
        $id = "AC1bf83dd5e59115e430838752ff9682b7";
        $token = "83f14f7095c6fb56a16d51f058f09125";
        $y = exec("curl 'https://api.twilio.com/2010-04-01/Accounts/$id/Messages.json' -X POST \--data-urlencode 'To=+$To' \--data-urlencode 'From=+$from' \--data-urlencode 'Body=$message' \-u $id:$token");
        //echo json_encode($y);
       }

       
       
       
       
       public function upload($tmppath,$uploadpath,$filename){
			
           		$name = explode('.',$filename);
			$ext = array_pop($name); 
			$filename = 'upload_'.uniqid(). strtotime("now") . '.'. $ext;
			//$target_path = getcwd().$thumb_path;
			$uploadpath = getcwd().$uploadpath;
			//echo $path;die;
			if (!file_exists($uploadpath)) {
				mkdir($path,0755);
			}
					
			$st = move_uploaded_file($tmppath, $uploadpath.$filename);
			if($st){
				return $filename;
			}else{
				return false;
			}	

	}
        
    public function sendmailnew($email,$subject,$message=false, $single = true,$param=false,$templet=false)
	{
		if ($single == true) {
				$this->load->library('email');
		}
		
		$this->config->load('email');
		$this->email->from($this->config->item('from'), $this->config->item('from_name'));
		//$this->email->reply_to($this->config->item('repy_to'), $this->config->item('reply_to_name'));
		$this->email->to($email);
		$this->email->subject($subject);
		if ($param&&$templet) {
			$body = $this->load->view('mail/'.$templet,$param,TRUE);
			$this->email->message($body);
		} else {
			$this->email->message($message);
		}
		return $this->email->send() ? true : false;
    }
    
    public function s3_uplode($filename, $temp_name) {
        $name = explode('.', $filename);
        $ext = array_pop($name);
        $name = 'agelgaz-' . hash('sha1', shell_exec("date +%s%N")) . '.' . $ext;

        $imgdata = $temp_name;
        $s3 = new S3(ACCESS_KEY, SECRET_KEY);
        $uri = AWS_URI.$name;
        $bucket = BUCKET_NAME;
        $result = $s3->putObjectFile($imgdata, $bucket, $uri, S3::ACL_PUBLIC_READ);
        $url = 'https://s3.amazonaws.com/'.BUCKET_NAME.'/' . $name;
        return $url;
	}
	
	public function s3_resource_transfer_script() {
		$this->db->select ('file_upload_path, thumb_path, id');
		$this->db->from('e_post');
		$query = $this->db->get();
		$result = $query->result_array();
        $s3 = new S3(ACCESS_KEY, SECRET_KEY);
        $s31 = new S31(ACCESS_KEY, SECRET_KEY);
		for ($i = 0;$i < count($result);$i++) {
			if (strpos($result[$i]['file_upload_path'], 'e4cc-img')) {
				continue;
				
			}
			$id = substr($result[$i]['file_upload_path'], strrpos($result[$i]['file_upload_path'], '/') + 1);
			$thumbnail = BASE_PATH."public/thumb/".$id;
			$s31->getObject('appinventiv-generic', 'web/'.$id, $thumbnail);
			$s3->putObjectFile($thumbnail, BUCKET_NAME, AWS_URI.$id, S3::ACL_PUBLIC_READ);
			$thumb = "";
			$thumb_url = '';
			if (!empty($result[$i]['thumb_path'])) {
				$id1 = substr($result[$i]['thumb_path'], strrpos($result[$i]['thumb_path'], '/') + 1);
				$thumbnail = BASE_PATH."public/thumb/".$id1;
				$s31->getObject('appinventiv-generic', 'web/'.$id1, $thumbnail);
				$thumb = $s3->putObjectFile($thumbnail, BUCKET_NAME, AWS_URI.$id1, S3::ACL_PUBLIC_READ);
				$thumb_url = 'https://s3.amazonaws.com/'.BUCKET_NAME.'/'.$id1;
			}
			$this->update_single('e_post', array("file_upload_path" => 'https://s3.amazonaws.com/'.BUCKET_NAME.'/'.$id, 'thumb_path' => $thumb_url), array("where" => array("id" => $result[$i]['id'])));
		}
	}

}
