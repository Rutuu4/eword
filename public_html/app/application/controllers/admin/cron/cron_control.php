<?php 
/*
    @Description: cron controller
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 11-01-2016
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cron_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->helper('push_notification_helper');
        
		$this->obj = $this->general_model;
    }
	

   	public function index()
    {	 

    }


    /**************************************************************************************
    @Description    : Function for send voucher code for nakshi.com when IMF user booked ticket
    @Author         : Mit Makwana
    @Output         : 
    @Date           : 07-07-2016
    ****************************************************************************************/

    public function reminder_notification_list()
    {
        
            $table              = "customer_inquiry_followup_record AS cifr ";
            $fields             = array("cifr.id,cifr.followupdatee,cifr.uid,TIMESTAMPDIFF(MINUTE,'".date('Y-m-d h:i:s')."',followupdatee) AS time_difference,cifr.remark_parameter,cifr.inquiryfollowup_id");
            
            
            $having_str        = "time_difference >= 1 AND time_difference <= 5000";
            
            $params = array(
                    'table'=>$table,
                    'fields'=>$fields,
                    'having_str' => $having_str,
                );
            $inquiryList = $this->General_model->get_query_data($params);
            /*pr($this->db->last_query());
            pr($inquiryList);*/

            if(!empty($inquiryList))
            {
                $message  = 'test';
                foreach ($inquiryList as $key => $value) {
                    check_user_device($value['uid'],$message,'1',$value['inquiryfollowup_id']);
                }
            }
        
    }
}
