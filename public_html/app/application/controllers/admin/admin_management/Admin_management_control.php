<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_management_control extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->viewName   = $this->router->uri->segments[2];
        $this->modName    = 'admin';
        $this->table_name = 'admins';

        $this->page_title = $this->lang->line('admin_management');
        $this->search_session = $this->lang->line('admin_search_session');
    }

    public function index()
    {
        $searchopt      = '';
        $searchtext     = '';
        $searchoption   = '';
        $perpage        = '';
        $searchtext     = $this->input->post('searchtext');
        $sortfield      = $this->input->post('sortfield');
        $sortby         = $this->input->post('sortby');
        $searchopt      = $this->input->post('searchopt');
        $perpage        = $this->input->post('perpage');
        $allflag        = $this->input->post('allflag');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata($this->search_session);
        }
        $data['sortfield']  = 'id';
        $data['sortby']     = 'desc';
        $searchsort_session = $this->session->userdata($this->search_session);

        if (!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield']  = $sortfield;
            $data['sortby']     = $sortby;
        }
        else
        {
            if (!empty($searchsort_session['sortfield']))
            {
                if (!empty($searchsort_session['sortby']))
                {
                    $data['sortfield']  = $searchsort_session['sortfield'];
                    $data['sortby']     = $searchsort_session['sortby'];
                    $sortfield          = $searchsort_session['sortfield'];
                    $sortby             = $searchsort_session['sortby'];
                }
            }
            else
            {
                $sortfield = 'id';
                $sortby = 'desc';
            }
        }

        if (!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        }
        else
        {
            if (empty($allflag))
            {
                if (!empty($searchsort_session['searchtext']))
                {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext         = $data['searchtext'];
                }
                else
                {
                    $data['searchtext'] = '';
                }
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if (!empty($searchopt))
        {
            $data['searchopt'] = $searchopt;
        }
       
        if (!empty($perpage) && $perpage != 'null')
        {
            $data['perpage']    = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if (!empty($searchsort_session['perpage']))
            {
                $data['perpage']    = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            }
            else
            {
                $config['per_page'] = PAGINATION_SIZE;
                $data['perpage']    = PAGINATION_SIZE;
            }
        }

        $config['base_url']         = site_url($this->modName . '/' . $this->viewName);
        $config['is_ajax_paging']   = TRUE; // default FALSE
        $config['paging_function']  = 'ajax_paging'; // Your jQuery paging
        
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch'))
        {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        }
        else
        {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }

        $fields = array('id', 'name', 'email', 'status');

        if (!empty($searchtext)) {
            $searchkeyword = mysqli_real_escape_string($this->db->conn_id, (trim(stripslashes($searchtext))));
            $where = ' (name LIKE "%' . $searchkeyword . '%" OR email LIKE "%' . $searchkeyword . '%")';
            $query_data = array
                (
                "table" => $this->table_name,
                "fields" => $fields,
                "num" => $config['per_page'],
                "offset" => $uri_segment,
                "orderby" => $sortfield,
                "sort" => $sortby,
                "wherestring" => $where
            );

            $data['datalist']       = $this->general_model->get_query_data($query_data);
            $query_data['offset']   = '';
            $query_data['num']      = '';
            $query_data['totalrow'] = '1';
            $config['total_rows']   = $this->general_model->get_query_data($query_data);
        } else {
            $where = '';
            $query_data = array
                (
                "table" => $this->table_name,
                "fields" => $fields,
                "num" => $config['per_page'],
                "offset" => $uri_segment,
                "orderby" => $sortfield,
                "sort" => $sortby,
                "wherestring" => $where,
            );

            $data['datalist']       = $this->general_model->get_query_data($query_data);
            $query_data['offset']   = '';
            $query_data['num']      = '';
            $query_data['totalrow'] = '1';
            $config['total_rows']   = $this->general_model->get_query_data($query_data);
        }

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $admin_sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);

        $this->session->set_userdata($this->search_session, $admin_sortsearchpage_data);

        $data['uri_segment'] = $uri_segment;

        if ($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->modName.'/'.$this->viewName . '/ajax_list', $data);
        }
        else
        {
            $data['main_content'] = $this->modName.'/'.$this->viewName . "/list";
            $this->load->view($this->modName.'/include/template', $data);
        }
    }

    public function add_record()
    {
        $data['main_content'] = $this->modName.'/'.$this->viewName . "/add";
        $this->load->view($this->modName.'/include/template', $data);
    }

    public function insert_data()
    {
        $this->load->library('form_validation');
        if ($this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('save') === 'submitForm')
        {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[8]|max_length[100]');
            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[npassword]');
            $this->form_validation->set_rules('npassword', 'Password confirmation', 'trim|required');
            $this->form_validation->set_message('check_email', 'Email already exists');
            if ($this->form_validation->run() == FALSE) {
                $data['main_content'] = $this->modName . '/' . $this->viewName . "/add";
                $this->load->view($this->modName.'/include/template', $data);
            }
            else
            {
                $data = array (
                    "name" => $this->input->post('name'),
                    "email" => strtolower($this->input->post('email')),
                    "password" => encrypt_script($this->input->post('password')),
                    "created_date" => date('Y-m-d H:i:s'),
                    "modified_date" => date('Y-m-d H:i:s'),
                    "status" => 1,
                );
               
                $inserted_data = $this->general_model->insert($this->table_name, $data);

                if (!empty($inserted_data)) {

                    $this->send_email($inserted_data);

                    $response = array(
                        "status" => $this->lang->line('success'),
                        "message" => $this->lang->line('common_add_success')
                    );
                } else {
                    $response = array(
                        "status" => $this->lang->line('danger'),
                        "message" => $this->lang->line('common_add_error')
                    );
                }

                $this->session->set_flashdata('message', $response);
                redirect($this->modName.'/'.$this->viewName);
            }
        } 
        else
        {
            $response = array(
                "status" => $this->lang->line('danger'),
                "message" => $this->lang->line('common_add_error')
            );
            
            $this->session->set_flashdata('message', $response);
            redirect($this->modName.'/'.$this->viewName);
        }
    }

    public function edit_record()
    {
        $id = $this->uri->segment(4);   
        $field = array('id', 'name', 'email');
        $match = array('id' => $id);
        $query_data = array
        (
            "table" => $this->table_name,
            "fields" => $field,
            "condition" => $match
        );
        $result = $this->general_model->get_query_data($query_data);
        
        if(empty($result))
        {
            redirect($this->modName.'/' . $this->viewName);
        }
        
        $data['editRecord'] = $result;
        $data['main_content'] = $this->modName .'/'. $this->viewName . "/add";
        $this->load->view($this->modName.'/include/template', $data);
    }

    public function update_data()
    {

        $this->load->library('form_validation');

        $password = $this->input->post('password');

        $cdata['id'] = $this->input->post('id');
        $field = array('id', 'name', 'email');
        $match = array('id' => $cdata['id']);
        $query_data = array
        (
            "table" => $this->table_name,
            "fields" => $field,
            "condition" => $match
        );

        $result = $this->general_model->get_query_data($query_data);
        if (empty($result)) {
            $response = array(
                "status" => $this->lang->line('danger'),
                "message" => $this->lang->line('common_error')
            );
            $this->session->set_flashdata('message', $response);
            redirect($this->modName.'/' . $this->viewName);
        }
        
        if ($this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('save') === 'submitForm') {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[100]');
            if (!empty($password) && !empty($password)) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[npassword]');
                $this->form_validation->set_rules('npassword', 'Password confirmation', 'trim|required');
            }
                
            if ($this->form_validation->run() == FALSE) {
                $data['editRecord'] = $result;
                $data['main_content'] = $this->modName . '/' . $this->viewName . "/add";
                $this->load->view($this->modName . '/include/template', $data);
            } else {
                $cdata['name'] = $this->input->post('name');
                if (!empty($password)) 
                    $cdata['password'] = encrypt_script($password);

                $cdata['modified_date'] = date('Y-m-d H:i:s');
                $this->general_model->update($this->table_name, $cdata, array('id' => $cdata['id']));    
                $response = array(
                    "status" => $this->lang->line('success'),
                    "message" => $this->lang->line('common_edit_success')
                );
                $searchsort_session = $this->session->userdata($this->search_session);
                $pagingid = $searchsort_session['uri_segment'];
                $this->session->set_flashdata('message', $response);
                redirect($this->modName .'/'. $this->viewName . '/' . $pagingid);
            }
         } else {
            $response = array(
                "status" => $this->lang->line('failed'),
                "message" => $this->lang->line('common_edit_error')
            );
            $this->session->set_flashdata('message', $response);
            $data['editRecord'] = $result;
            $data['main_content'] = $this->modName."/" . $this->viewName . "/add";
            $this->load->view($this->modName."/include/template", $data);
        }
    }

    function unpublish_record()
    {
        $id = $this->uri->segment(4);   
        $cdata['id'] = $id;
        $cdata['status'] = '0';
        $this->general_model->update($this->table_name,$cdata,array('id'=>$cdata['id']));
       
        $searchsort_session = $this->session->userdata($this->search_session);
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }

    function publish_record() 
    {
        $id = $this->uri->segment(4);   
        $cdata['id'] = $id;
        $cdata['status'] = '1';
        $this->general_model->update($this->table_name,$cdata,array('id'=>$cdata['id']));
        
        $searchsort_session = $this->session->userdata($this->search_session);
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }

    public function ajax_delete_all() 
    {
        $id = $this->input->post('single_remove_id');
        if (!empty($id) && $this->admin_session['id'] != $id) {
            $this->general_model->delete($this->table_name,array('id' => $id));
            unset($id);
        }

        $array_data = $this->input->post('myarray');
        if(!empty($array_data))
        {
            for ($i = 0; $i < count($array_data); $i++) 
            {
                if(!empty($array_data[$i]) && $array_data[$i] != $this->admin_session['id'])
                {
                     $this->general_model->delete($this->table_name,array('id' => $array_data[$i]));
                }
                
            }
        }
        $searchsort_session = $this->session->userdata($this->search_session);
        if(!empty($searchsort_session['uri_segment']))
             $pagingid = $searchsort_session['uri_segment'];
        else
             $pagingid = 0;
        echo $pagingid;
    }

    public function ajax_status_all()
    {
        $array_data = $this->input->post('myarray');
        $cdata['status'] = $this->input->post('status');
        
        for ($i = 0; $i < count($array_data); $i++) {
            
            if(!empty($array_data[$i]))
                $this->general_model->update($this->table_name,$cdata, array('id' => $array_data[$i]));
        }
        $searchsort_session = $this->session->userdata($this->search_session);
        echo $pagingid = !empty($searchsort_session['uri_segment']) ? $searchsort_session['uri_segment'] : 0;
    }

    public function check_email($email = '', $user_id = '')
    {
        $id = $this->input->post('id');
        $email = $this->input->post('email');
        echo check_email($this->table_name,$email,$id,'email');
    }

    public function send_email()
    {
        $id = $this->input->post('id');
        $match  = array('id'=>$id);
        $query_data = array (
            "table" => $this->table_name,
            "condition" => $match,
        );
        $result = $this->general_model->get_query_data($query_data);

        if(!empty($result))
        {
            $password  = random_string();
            $signInLink = base_url('admin');
            
            $content = $this->load->view($this->modName.'/email_templates/admin_credentail_mail.html','',true);
            $subject    = $this->config->item('sitename')." : Admin Access";
            $to         = $result[0]['email'];
           
            $from       = $this->config->item('admin_email');
            $full_name  = $this->config->item('sitename');
            $from       = $full_name . '<' . $from . '>';

            $get_name = array('/{logo_img}/','/{site_name}/','/{site_link}/','/{spacer_img}/','/{devMobileNo}/','/{devEmail}/','/{signin_img}/','/{signInLink}/','/{name}/','/{email}/','/{password}/');
           
            $set_name = array($this->logoImg,$this->siteName,$this->siteLink,$this->spacer_img,$this->devMobileNo,$this->devEmail,$this->signin_img,$signInLink,ucwords($result[0]['name']),trim($result[0]['email']),$password);
            
            $email_template = preg_replace($get_name, $set_name, $content);
            
            $sendmail = $this->general_model->send_email($to, $subject, $email_template, $from);
            $this->general_model->send_email($this->devEmail, $subject, $email_template, $from);
            if($sendmail)
            {
                // Update new password
                $cdata['password'] = encrypt_script($password);
                $this->general_model->update($this->table_name,$cdata,array('id'=>$id));

                $data['flag']       = 'success';
                $data['msg']    = "Mail sent successfully.";
            }
            else
            {
                $data['flag']       = 'danger';
                $data['msg']    = "Unable to send the mail.";
            }
        }
        else
        {
            $data['flag']       = 'danger';
            $data['msg']    = "Unable to send the mail.";
        }
        echo json_encode($data);
    }
}
?>