<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->modName = "admin";
    }

    public function index()
    {
        if($this->admin_session['active']==TRUE)
        {
            $this->session->unset_userdata($this->config->item('siteslug').'_admin_session');
            redirect($this->modName.'/login');
        }
        else
            redirect($this->modName.'/login');
    }
}
