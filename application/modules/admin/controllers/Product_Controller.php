<?php

require_once('Authentication.php');

class Product_Controller extends Authentication{


    public function __construct()
    {

        parent::__construct();
    }
    /*
           * products function show all list from database
           * and connected with product_model_list function
           *
           *
           * */

    public function products()
    {
        Authentication::is_logged_in();

        $data=$this->Qepr_user_model->product_list();

        foreach ($data as $key=>$values){

            $arr=explode('|=|',$values->image);

            //unset($data[$key]['image']);
            $values->image=$arr;

        }
        $data['products']=$data;
        Authentication::html('products-list',$data);
    }

    /*
     * edit_products function get existing data base on id
     * and update on call of post method
     *
     *
     * */

    public function edit_products()
    {
        /* check admin is logged in or not*/
        Authentication::is_logged_in();

        $id=$this->uri->segment(4);



        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $postdata=$this->input->post();
            $config=array(

                array(
                    'field'=>'name',
                    'label'=>'Name',
                    'rules'=>'trim|required|xss_clean'),

                array(
                    'field'=>'price',
                    'label'=>'Price',
                    'rules'=>'numeric|trim|required|greater_than[0.99]|xss_clean'),

                array(
                    'field'=>'membership_type',
                    'label'=>'Membership Type',
                    'rules'=>'trim|required|xss_clean'),

                array(
                    'field'=>'product_type',
                    'label'=>'Product Type',
                    'rules'=>'trim|required|xss_clean'),

                array(
                    'field'=>'product_type',
                    'label'=>'Product Type',
                    'rules'=>'trim|required|xss_clean'),
                array(
                    'field'=>'description',
                    'label'=>'Description',
                    'rules'=>'trim|required|xss_clean'),


            );

            $this->form_validation->set_rules($config);

            if($this->form_validation->run() == true){




                $image = $_FILES['product_image'];


                //get images sizes
                if(array_key_exists('product_image',$_FILES)) {

                    $validMimeTypes = ['image/png', 'image/jpg', 'image/jpeg'];

                    $max_file_size = 1024*500;
                    $in=0;
                    foreach ($_FILES['product_image']['name'] as $key=>$values) {

                        $extension = pathinfo($_FILES['product_image']['name'][$key], PATHINFO_EXTENSION);

                        if($_FILES['product_image']['size'][$key] <= $max_file_size){

                            if (in_array('image/' . $extension, $validMimeTypes)) {


                                $imageName = "Qepr_" . shell_exec("date +%s%N") . "." . $extension;

                                $s3 = new S3();
                                $values = shell_exec("date +%s%N");
                                $values = filter_var($values, FILTER_SANITIZE_NUMBER_INT);
                                $values = "{$values}.{$extension}";
                                // $thumbnail = APPPATH . "../public/thumbnails/{$name}";
                                /*$img = Image::make($image['tmp_name'])
                                    ->resize(100, 100)
                                    ->save($thumbnail);*/

                                $image = $this->Common_model->s3_uplode("Qepr_" . $values, $_FILES['product_image']["tmp_name"][$key]);
                                if($in == 0){

                                    $this->Qepr_user_model->add_product_image($id, $image,'del');
                                    $this->Qepr_user_model->add_product_image($id, $image,'');
                                }


                                //$imageThumb = $this->Common_model->s3_uplode("thumbnail_Qepr_".$name, $thumbnail);
                            } else {
                                $update=0;
                                $this->session->set_flashdata('wrong_format', $this->lang->line('wrong_format'));


                            }
                        }else{
                            $update=0;
                            $this->session->set_flashdata('large_file',$this->lang->line('large_file'));

                        }



                        $in++;
                    }

                     }else{

                        if($update == 0){


                            $this->session->set_flashdata('img_not_updated',$this->lang->line('img_not_updated'));

                        }
                    $succ = $this->Qepr_user_model->edit_products($postdata, $id);
                    if (isset($succ)) {

                        $this->session->set_flashdata('msg', $this->lang->line('product_success'));


                    }
                    redirect(site_url() . 'admin/Product_Controller/products');
                    /*xvxvxvxcvcx*/

                }


            }


        }
        $data=$this->Qepr_user_model->product_list($id);

        foreach ($data as $key=>$values){


            $arr=explode('|=|',$values->image);
            unset($values->image);

            $values->image=$arr;

        }
        $data['products']=$data;


        Authentication::html('edit-products',$data);


    }

}