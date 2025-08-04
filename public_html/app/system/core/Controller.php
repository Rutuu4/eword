<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {
    
    
    public $benchmark;    
public $config;
public $log;    
public $hooks;  
public $utf8;    
public $uri;
public $router;    
public $exceptions;    
public $output;    
public $security;
public $input;    
public $lang;  
public $load;  

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();

		$this->load->model('general_model');

        //Setting the admin setting in the config
        /*$this->site_info = $this->general_model->get_query_data(array('table'=>'settings_master'));
		if(!empty($this->site_info))
		{
			$this->config->set_item('sitename', $this->site_info[0]['sitename']);
			$this->config->set_item('address', $this->site_info[0]['address']);
			$this->config->set_item('contact_number', $this->site_info[0]['contact_number']);
			$this->config->set_item('contact_email', $this->site_info[0]['contact_email']);
			$this->config->set_item('admin_email', $this->site_info[0]['admin_email']);
			$this->config->set_item('smtp_host', $this->site_info[0]['smtp_host']);
			$this->config->set_item('smtp_user', $this->site_info[0]['smtp_user']);
			$this->config->set_item('smtp_pass', decrypt_script($this->site_info[0]['smtp_pass']));
			$this->config->set_item('protocol', $this->site_info[0]['protocol']);
			$this->config->set_item('smtp_port', $this->site_info[0]['smtp_port']);
			$this->config->set_item('smtp_timeout', $this->site_info[0]['smtp_timeout']);
        }*/

        /// For emails
        $this->logoImg          = base_url('images/emails/logo.png');
        $this->siteName         = $this->config->item('sitename');
        $this->siteLink         = base_url();
        $this->spacer_img       = base_url('images/emails/spacer.png');
        $this->devMobileNo      = $this->config->item('devMobileNo');
        $this->devEmail         = $this->config->item('devEmail');
        $this->signin_img		= base_url('images/emails/signin_btn.png');

		$this->admin_session = $this->session->userdata($this->config->item('siteslug').'_admin_session');
		$this->employer_session = $this->session->userdata($this->config->item('siteslug').'_employer_session');
		$this->recruiter_session = $this->session->userdata($this->config->item('siteslug').'_recruiter_session');
		$this->candidate_session = $this->session->userdata($this->config->item('siteslug').'_candidate_session');

		if(!empty($this->recruiter_session))
		{
			$recruiter_info = $this->general_model->get_query_data(array('table'=>'recruiters','fields'=>array('linkedin_url','bio','admin_status','status'),'condition'=>array('id'=>$this->recruiter_session['id'])));
			if(empty($recruiter_info[0]['admin_status']) && (empty($recruiter_info[0]['linkedin_url']) || empty($recruiter_info[0]['bio'])))
				$this->incomplete_recruiter_info = 1;
			elseif(empty($recruiter_info[0]['admin_status']) && (!empty($recruiter_info[0]['linkedin_url']) && !empty($recruiter_info[0]['bio'])))
				$this->incomplete_recruiter_info = 2;
			else
				$this->incomplete_recruiter_info = 0;
		}

		$this->flashdata = $this->session->flashdata('message');
		
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

}
