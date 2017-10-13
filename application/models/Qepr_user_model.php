<?php

class Qepr_user_model extends CI_Model
{

    protected $prefix='qe';



    function multi_insert($data)
    {
        if(!empty($data)){
        if(in_array('aboutUs',$data))
        {
            $table='aboutUs';
        }
        if(isset($data['contactUs'])){

            $table='contactUs';

        }
        if(array_key_exists('table',$data)){


            $subscription=array(

            'subscription_name'=>$data['subscription_name'],
            'subscription_validity'=>$data['subscription_validity'],
            'subscription_type'=>$data['subscription_type'],
            'price'=>$data['price'],
            'date_created'=>date('Y-m-d h:i:s')
                );
            $this->db->insert($this->prefix.'_subscription',$subscription);
            $insert_id=$this->db->insert_id();

         foreach ($data['features'] as $key=>$values)
         {

            $arr=array(
                'subscription_id'=>$insert_id,
                     'features'=>$values,
                 'features_type'=>1
                     );
             $this->db->insert($this->prefix.'_subscription_fetaures',$arr);

         }
       /* foreach ($data['features_type'] as $fkey=>$fvalues)
        {

            $arr=array(
                'subscription_id'=>$insert_id,
                'features'=>$fvalues,
                'features_type'=>2
                     );
             $this->db->insert($this->prefix.'_subscription_fetaures',$arr);

         }*/



        }}
            return false;



    }

    function All_list($colomn = '')
    {
        if(empty($colomn))
        {
            $arr=array(

                'erm_and_condition'=>'term_and_condition',
                'privacy_policy'=>'privacy_policy',
                'contact_us'=>'contact_us',
                'faq'=>'faq',
                'about_us'=>'about_us',
                'return_policy'=>'return_policy'
            );
        }else{
            $arr=$colomn;
        }
        $table=$this->prefix.'_cms_pages';
        $this->db->select($arr);
        $this->db->from($table);

        $data=$this->db->get();
        return $data->result();


    }

    function put_where_edit($data)
    {
        $table=$this->prefix.'_cms_pages';
        $id=$data['thiscolomn'];
        unset($data['thiscolomn']);
        if($this->db->update($table,array($id=>$data['description'])))
        {
            return true;
        }else{
            return false;
        }



    }

    function get_logindetails($username = '')
    {
        if(!empty($username))
        {
            $table=$this->prefix.'_user';

            $this->db->select('email,password,name');
            $this->db->from($table);
            $this->db->where('email',$username);
            $data=$this->db->get();
            if(count($data->row())>0){

                return $data->row();

            }else{
                return false;
            }

        }
            return false;


    }

    function get_age($dob='')
    {
        if(!empty($dob))
        {

            $this->db->select('username,password,display_name');
            $this->db->from('login_credenatials');
            $this->db->where('username',$dob);
            $data=$this->db->get();
            return $data->row();

        }
    }

    function get_where_reset($email = '')
    {
        if(!empty($email))
        {

            $this->db->select('email,password,verify_otp,forgot_token');
            $this->db->from($this->prefix.'_user');
            $this->db->where('email',$email);
            $data=$this->db->get();
            return $data->row();

        }
        return false;
    }

    function update_passwordandOtp($email = '',$otpValue = 'NULL' ,$updatepassword = '',$otp_token = '')
    {

            if(!empty($updatepassword))
            {
                $arr=array('verify_otp'=>$otpValue,

                    'password'=>$updatepassword);
            }else{
                $arr=array('verify_otp'=>$otpValue);
            }

        if(!empty($arr))
        {
            $str=strtotime(date("Y-m-d H:i:s")." +24 minutes");
            $newDate = date("Y-m-d H:i:s",$str);

            $otp_token_and_date=array(
                'forgot_token'=>$otp_token,
                'updated'=>$newDate

            );

            $this->db->set($arr);
            $this->db->set($otp_token_and_date);
            $this->db->where('email',$email);
            $this->db->update($this->prefix.'_user');

            return true;

        }
        return false;

    }

    function check_otp_expiration($email)
    {
        if(!empty($email)){

            $this->db->select('verify_otp');
            $this->db->from($this->prefix.'_user');
            $this->db->where('email',$email);
            $this->db->where("NOW() <= updated");
            $data = $this->db->get();

            return $data->row();

        }
        return false;
    }

    function count_user($filterdata = array())
    {

        $table=$this->prefix.'_user';
        if(!empty($table))
        {
            if (!empty($filterdata) > 0)
            {


                $this->db->select("count(id) as id");


                if($filterdata['to_date'] !== '' && $filterdata['from_date'] !== '' )
                {

                    $from_unix=strtotime($filterdata['from_date']);
                    $converted_date_from=date('Y-m-d',$from_unix);
                    $to_unix=strtotime($filterdata['to_date']);
                    $converted_date_to=date('Y-m-d',$to_unix);
                    $this->db->where("created between '$converted_date_from' and '$converted_date_to' ");
                }
                if($filterdata['Status'] !== 'NULL'){

                    $this->db->where(array(

                        'subscription_id' => $filterdata['Status']));
                }if ($filterdata['User_type'] !== 'NULL'){

                $this->db->where(array('status' => $filterdata['User_type']));

            }

            }

                $this->db->where(array('role_id' => USER_ROLE));

                $this->db->from($table);





            $count = $this->db->get();

            return $count->result();
        }
          return false;


    }

    function get_users($limit,$offset,$filterdata = array())
    {


        $table = $this->prefix . '_user';

        if (!empty($table))
        {
            $this->db->select('id,status,name,email,mobile_number,created,subscription_id');
            if (count($filterdata) >0)
            {

               if($filterdata['to_date'] !== '' && $filterdata['from_date'] !== '' )
               {

                   $from_unix=strtotime($filterdata['from_date']);
                   $converted_date_from=date('Y-m-d',$from_unix);
                   $to_unix=strtotime($filterdata['to_date']);
                    $converted_date_to=date('Y-m-d',$to_unix);
                   $this->db->where("created between '$converted_date_from' and '$converted_date_to' ");
               }


                if($filterdata['Status'] !== 'NULL'){

                    $this->db->where(array(

                        'subscription_id' => $filterdata['Status']));
                }if ($filterdata['User_type'] !== 'NULL'){

                    $this->db->where(array('status' => $filterdata['User_type']));

                }

            }

                $this->db->from($table);
                $this->db->where(array('role_id' => USER_ROLE));


            $this->db->limit($limit,$offset);

            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {


                return $query->result();
            }

            return false;
        }

        }

    function view_user($id)
        {
            $table = $this->prefix . '_user';

            if (!empty($table))
            {
                $this->db->select('status,subscription_id,fb_id,twitter_id,name,email,mobile_number,created,subscription_id');
                $this->db->where(array('id'=>$id,'role_id'=>USER_ROLE));
                 $query = $this->db->get($table);
              return $query->result();

            }
            return false;

        }

    function getsearch_name($like = '')
    {

        $sno=0;
        $table = $this->prefix . '_user';
        if(empty($like)){

            return false;
        }
            if (!empty($table))
        {
            $this->db->select('id,status,name,email,mobile_number,created,subscription_id');
            $this->db->where('role_id', USER_ROLE);
             $this->db->like('name',$like);
            $query = $this->db->get($table);
            if ($query->num_rows() > 0)
            {
            foreach($query->result() as $users) :?>

                <tr>

                    <td><?= ++$sno; ?></td>
                    <td><?php echo anchor(site_url().'admin/Users_Controller/view_user_details/'.$users->id,isset($users->name)?$users->name:'') ?></td>
                    <td><?= isset($users->email)?$users->email:''; ?></td>
                    <td><?= isset($users->mobile_number)?$users->mobile_number:''; ?></td>
                    <td><?= isset($users->created)?$users->created:''; ?></td>
                    <td  ><?php if(isset($users->status))
                        {if($users->status == USER_ACTIVE ) {
                                echo '<span style="color:green">Active</span>';} elseif($users->status == USER_BLOCK) {
                                echo '<span style="color:red">BLocked</span>';}else{ echo 'deleted';}
                        } ?></td>
                    <td><?php if(isset($users->subscription_id)){ if(trim($users->subscription_id) == USER_NOT_SUBSCRIBED){ echo 'No Subscription';}else{ echo '<span style="color:green">Subscribed</span>';}} ?></td>
                    <td><?= $sno%2 === 0 ?'yes':'No'; ?></td>
                    <td>
                        <ul class="table-nav-list">
                            <li><?= anchor(site_url().'admin/Qepr_admin/get_user/','<i class="fa fa-pencil"></i>','class="edit"') ?></li>
                        </ul>
                    </td>
                </tr>

            <?php endforeach;


            }
            else{

                return '<tr ><td colspan="9"><h1 style="color:red">Not Found!</h1></td></tr>';

            }
            return false;
        }

    }

    function block_user($data)
    {

        if(!empty($data))
        {
            $data['unblock']=isset($data['unblock'])?$data['unblock']:'';
            $data['block']=isset($data['block'])?$data['block']:'';
            if($data['unblock'] === 'unblock'){

                $status=1;
                $this->session->set_flashdata('blocked','Now User Is Active');

            }
            if($data['block'] === 'block'){
                $status=2;
                $this->session->set_flashdata('blocked','User Has Been Blocked');
            }
            $this->db->set('status',$status);
            $this->db->where('id',$data['id']);
            $this->db->update('qe_user');

        }
        return false;


    }

    public function subscription_list($id = '')
    {

        $this->db->select('sb.id,subscription_name,subscription_validity,subscription_type,price,subscription_id,GROUP_CONCAT(features, ";;;", features_type SEPARATOR ":::") as features',false);
        $this->db->from($this->prefix.'_subscription sb');
        $this->db->join($this->prefix.'_subscription_fetaures sf','sb.id=sf.subscription_id','left');
        $this->db->group_by('sb.id');
        if(!empty($id)) { $this->db->where('sb.id',$id);}
        $data=$this->db->get();
        return $data->result_array();

     }

    public function delete_subscription_plan($id = '')
    {

        if(!empty($id)){


            $this->db->delete($this->prefix.'_subscription_fetaures', array('subscription_id' => $id));
            $this->db->delete( $this->prefix.'_subscription', array('id' => $id));
             return true;
       }
        return false;


    }
    public function product_list($id = '')
    {


        $this->db->select('prd.id,name,price,description,membership_type,GROUP_CONCAT(images SEPARATOR "|=|")as image,GROUP_CONCAT(pmg.id SEPARATOR "|+|")as img_id,product_type,updated');
        $this->db->from($this->prefix.'_products as prd');
        $this->db->join($this->prefix.'_product_images as pmg','prd.id=pmg.product_id','left');
        $this->db->Group_by('prd.id');
        if(!empty($id)){  $this->db->where('prd.id',$id);}
        $data= $this->db->get();
        return $data->result();

    }

    public function edit_products($data,$id)
    {


        if(!empty($id)){

            $Prod_arr=array(
            'name'=>$data['name'],
            'price'=>$data['price'],
            'description'=>$data['description'],
            'membership_type'=>$data['membership_type'],
            'product_type'=>$data['product_type'],
             'updated'=>date('Y-m-d h:i:s')
                );


            $this->db->set($Prod_arr);
            $this->db->where('id',$id);
            $this->db->update($this->prefix.'_products');



                return true;

        }
            return false;


    }

    function add_product_image($id,$img,$delete)
    {

        if(!empty($img)){

            if($delete === 'del'){
                $this->db->delete($this->prefix.'_product_images',array('product_id'=>$id));

            }


                $this->db->insert($this->prefix.'_product_images',array('product_id'=>$id,'images'=>$img));



            return true;
        }
        return false;

    }

    public function edit_subscription($data,$id)
    {
        if(!empty($data)) {
            $sub_arr = array(
                'subscription_name' => $data['subscription_name'],
                'subscription_validity' => $data['subscription_validity'],
                'subscription_type' => $data['subscription_type'],
                'price' => $data['price']
            );
            $this->db->set($sub_arr);
            $this->db->where('id', $id);
            $this->db->update($this->prefix . '_subscription');

            $this->db->delete($this->prefix . '_subscription_fetaures', array('subscription_id' => $id));


           /* features_type have been disabled beacause of no more need
            *
            * disbaled by rajesh maurya suggested by manu jain
            *
            * this features_type me be used in future accoding to necessary
            * */


            /* foreach ($data['features_type'] as $values) {

                $fetarr = array(
                    'subscription_id' => $id,
                    'features' => $values,
                    'features_type' => 2
                );
                $this->db->insert($this->prefix . '_subscription_fetaures', $fetarr);
            }*/


            foreach ($data['features'] as $fvalues) {

                $farr = array(
                    'subscription_id' => $id,
                    'features' => $fvalues,
                    'features_type' => 1
                );
                $this->db->insert($this->prefix . '_subscription_fetaures', $farr);
            }

            return true;

            }
            return false;

          }


            public function change_email($data =  '')
            {


                if (!empty($data)) {

                    $this->db->select('email');
                    $this->db->from($this->prefix . '_user');
                    $this->db->where('email', $data['old_email']);

                    $data = $this->db->get();

                     return $data->result();

                 }
                 return false;
            }

            public function set_mail($data){


                if (!empty($data['email'])) {


                    $this->db->set('email', $data['email']);
                    $this->db->where('email', $data['old_email']);
                    $this->db->update($this->prefix . '_user');
                    return true;


                    }

              return false;

             }

             public function change_name($data,$email)
             {


                 if (!empty($data['name'])) {

                     $this->db->set('name', $data['name']);
                     $this->db->where('email', $email);
                     $this->db->update($this->prefix . '_user');
                     return true;
                 }

                return false;

             }


}

