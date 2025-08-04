<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_password_control extends CI_Controller
{	
    function __construct()
    {	
        parent::__construct();
        $this->viewName   = $this->router->uri->segments[2];
        $this->modName    = 'admin';
        // Set Page Title 
        $this->page_title = $this->lang->line('change_password');
    }
    
    public function index()
    {	
		$data['main_content'] = $this->modName.'/'.$this->viewName."/change_password";
        $this->load->view($this->modName.'/include/template', $data);
    }

    public function admin_change_password()
    {
		$id = $this->admin_session['id'];
		$password = encrypt_script($this->input->post('oldpassword'));
		
		$fields = array('id','email');
		$match = array('id'=>$id, 'password'=>$password);
		$query_data = array(
            'table' => 'admin_users',
            'fields' => $field,
            'condition' => $match,
        );
		$result = $this->general_model->get_query_data($query_data);
	
		if(!empty($result) && count($result)>0)
		{
			$cdata['id'] = $id;
			$cdata['password'] = encrypt_script($this->input->post('password'));
            $match = array('id'=>$id);
			$update = $this->general_model->update('admin_users',$cdata,$match);
			$response = array(
	            "status" => "success",
	            "message" => "Password changed successfully."
	        );
	        $this->session->set_flashdata('message', $response);
			redirect($this->modName.'/'.$this->viewName);		
		}
		else
		{
			$response = array(
	            "status" => "danger",
	            "message" => "The current password entered is incorrect."
	        );
	        $this->session->set_flashdata('message', $response);
			redirect($this->modName.'/'.$this->viewName);
		}
       
    }
   
}