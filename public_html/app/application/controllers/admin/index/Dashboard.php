<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{ 
    function __construct()
    {
        parent::__construct();
        $this->modName = "admin";
        check_admin_login();
    }

    public function index()
    {
        // Set Page Title 
        $this->page_title = $this->lang->line('dashboard');
        ($this->admin_session['active'] == true) ? $this->display_dashbord() : redirect($this->modName.'/login');
    }
	
    public function display_dashbord()
    {
        
        $data['msg'] = ($this->uri->segment(3) == 'msg') ? $this->uri->segment(4) : '';
        $data['main_content'] = $this->modName."/home/dashboard";
        $this->load->view($this->modName.'/include/template',$data);
    }
}