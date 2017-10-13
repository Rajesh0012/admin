<?php
require_once('Authentication.php');

class Cms_pageControlller extends Authentication
{

    function __construct()
    {

        parent::__construct();

    }

    function cms_pages(){

        Authentication::is_logged_in();

        $results=[];
        if(!empty($_GET['id']))
        {
            $id=$_GET['id'];
            $eid=$this->security->xss_clean($id);
        }
        if(!empty($eid))
        {
            $results['editdata']=$this->Qepr_user_model->All_list($eid);
            $results['eid']=str_replace('-','&nbsp',$eid);


        }
        //generate a csrf token for security check form request and form token is valid or not then process further
        $results['csrfname']=$this->security->get_csrf_token_name();
        $results['csrfvalue']=$this->security->get_csrf_hash();
        $postdata=$this->input->post();

        $filterdata=$this->security->xss_clean($postdata);

        //check form request method is post or not
        if($this->input->server('REQUEST_METHOD')==='POST')
        {
            $config = array(

                array(
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required|min_length[5]',

                )

            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == true)
            {
                if(empty($eid))
                {
                    $this->Qepr_user_model->multi_insert($filterdata);
                    $results['msg']=$this->lang->line('form_success');

                }else{
                    if($this->Qepr_user_model->put_where_edit($filterdata))
                    {
                        $this->session->set_flashdata('updated',$this->lang->line('update_success'));
                        redirect(site_url().'admin/Cms_pageControlller/page_list');

                    }else{
                        $this->session->set_flashdata('updated',$this->lang->line('update_failled'));
                    }

                }
            }else{

                $results['msg']=$this->lang->line('form_failled');
            }
        }

        Authentication::html('editpages',$results);

    }

    function page_list()
    {

        Authentication::is_logged_in();

        $data['list']=$this->Qepr_user_model->All_list();


        Authentication::html('page-list',$data);



    }


    function put_edit_where($data)
    {

        $data['$values']=$this->Qepr_user_model->put_where_edit();
        $this->load->views($data);
    }



}