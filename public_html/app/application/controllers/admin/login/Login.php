<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->modName = "admin";
        $this->data = array();
        $this->table_name = 'admins';
    }

    public function index() 
    {
        if ($this->admin_session['active'] === TRUE) 
        {

            if($this->input->get('redirect')){
                redirect(urldecode($this->input->get('redirect')));
            }
            else{
                redirect($this->modName.'/dashboard');                        
            }
        }
        else 
        {
            $this->do_login();
        }
    }
    
    public function do_login() 
    {
        $email = $this->input->post('email');
        $password = encrypt_script($this->input->post('password'));
        $forgot_password = $this->input->post('forgot_email');
        if ($forgot_password) 
        {
            $this->forgetpw_action();
        }
        else 
        {
            if ($email && $password) 
            {
                $email1 = '';
                $password1 = '';

                $field = array('id', 'name', 'email', 'status');
                $match = array('email' => $email, 'password' => $password);
                $query_data = array(
                    'table' => $this->table_name,
                    'fields' => $field,
                    'condition' => $match,
                );
                $udata = $this->general_model->get_query_data($query_data);

                if (count($udata) > 0) 
                {
                    if ($udata[0]['status'] == 1) 
                    {
                        $newdata = array(
                            'name' => $udata[0]['name'],
                            'id' => $udata[0]['id'],
                            'useremail' => $udata[0]['email'],
                            'active' => TRUE);
                        
                        $this->session->set_userdata($this->config->item('siteslug').'_admin_session', $newdata);

                        $response = array(
                            "status" => "success",
                            "message" => $this->lang->line('loggedin')
                        );
                        $this->session->set_flashdata('message', $response);

                        if($this->input->get('redirect')){
                            redirect(urldecode($this->input->get('redirect')));
                        }
                        else{
                            redirect($this->modName.'/dashboard');                        
                        }
                    }
                    else 
                    {
                        $response = array(
                            "status" => "danger",
                            "message" => $this->lang->line('inactive_account')
                        );
                        $this->session->set_flashdata('message', $response);
                        $this->load->view($this->modName.'/login/login');
                    }                    
                }
                else 
                {
                    $field = array('id');
                    $match = array('email'=>$email);
                    $query_data = array(
                        'table' => $this->table_name,
                        'fields' => $field,
                        'condition' => $match,
                    );
                    $dcdata = $this->general_model->get_query_data($query_data);
                    if(!empty($dcdata))
                    {
                        $response = array(
                            "status" => "danger",
                            "message" => $this->lang->line('invalid_credentails')
                        );
                    }
                    else
                    {
                        $response = array(
                            "status" => "danger",
                            "message" => $this->lang->line('no_account_found')
                        );
                    }
                    $this->session->set_flashdata('message', $response);
                    $this->load->view($this->modName.'/login/login');
                }
            }
            else 
            {
                $this->load->view($this->modName.'/login/login');
            }
        }
    }

    public function forgetpw_action() {
        $email = $this->input->post('forgot_email');

        $fields = array('id', 'name', 'email', 'password');
        $match = array('email' => $email);
        $query_data = array(
            'table' => $this->table_name,
            'fields' => $field,
            'condition' => $match,
        );
        $result = $this->general_model->get_query_data($query_data);

        if ((count($result)) > 0) {
            $name = $result[0]['name'];
            $email = $result[0]['email'];
            
            $password = decrypt_script($result[0]['password']);
            $encBlastId = urlencode(base64_encode($result[0]['id']));
            // Email Start

            $loginLink = $this->config->item('base_url') . 'reset_password/reset_password_template/' . $encBlastId;
            
            $pass_variable_activation = array('name' => $name, 'email' => $email, 'password' => $password, 'loginLink' => $loginLink);
            $data['actdata'] = $pass_variable_activation;
            
            $activation_tmpl = $this->load->view('reset_password/reset_password_link/list', $data, true);

            $email = $this->input->post('forgot_email');
            $sub = $this->config->item('sitename') . " : Admin Forgot Password";

            $from = $this->config->item('admin_email');
           $sendmail = $this->general_model->send_email($email, $sub, $activation_tmpl, $from);
            $msg = "Mail Sent Successfully";
        } else {
            $msg = "No Such User Found";
        }
        $newdata = array('msg' => $msg);
        $data['msg'] = $msg;
        $this->load->view($this->modName.'/login/login', $data);
    }

}
