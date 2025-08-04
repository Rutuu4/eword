<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Login_control extends REST_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->table_user = 'user';
    }

    function login_post()
    {
        $data = $this->post(); 
        
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        
        if ($this->form_validation->run() == false) {
            $response['message'] = strip_tags(validation_errors());
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
        } 
        else 
        {
            $params = array(
                    'table'=>TBL_REGISTRATION,
                    'where'=>array('username' => "'".$data['username']."'"),
                    'compare_type' => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (!empty($chkUser)) {
                
                if ($chkUser[0]['status'] == 2 or $chkUser[0]['status'] == 1) {

                    if ($chkUser[0]['password'] == $data['password']) {


                        if(!empty($data['udid']))
                        {

                            $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                            $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                            $udata['udid']  = !empty($data['udid'])?$data['udid']:'';


                            $where = array('id' => $chkUser[0]['id']);
                            $this->General_model->update(TBL_REGISTRATION, $udata, $where);

                            $response['message']         = "logged In Successfully";
                            $response['code']       = REST_Controller::HTTP_OK;
                            unset($chkUser[0]['password']);
                            $response['data']   = !empty($chkUser)?$chkUser:array();

                        }
                        else
                        {
                            $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                            //$udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';


                            $where = array('id' => $chkUser[0]['id']);
                            $this->General_model->update(TBL_REGISTRATION, $udata, $where);

                            $response['message']         = "logged In Successfully";
                            $response['code']       = REST_Controller::HTTP_OK;
                            unset($chkUser[0]['password']);
                            $response['data']   = !empty($chkUser)?$chkUser:array();

                        }


                    } else 
                    {

                        $response['message'] = 'invalid Password';
                        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    }
                }
                else 
                {
                    if($chkUser[0]['status'] == 2)
                    {
                        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                        $response['message'] = 'Dear User Your Account is Deactivated By Engrow Team for More Details Call us on 7777777777';
                    }
                    else
                    {
                        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                        $response['message'] = 'Check your ID or Password';
                    }
                }
            }
            else {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'User Not Registered';
            }
        }
        $this->response($response, 200);
    }

        function login_chk_post()
    {
        $data = $this->post(); 
        
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        
        if ($this->form_validation->run() == false) {
            $response['message'] = strip_tags(validation_errors());
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
        } 
        else 
        {
            $params = array(
                    'table'=>TBL_REGISTRATION,
                    'where'=>array('username' => "'".$data['username']."'",'status' => 1),
                    'compare_type' => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (!empty($chkUser)) 
            {
                            $response['message']         = "logged In Successfully";
                            $response['code']       = REST_Controller::HTTP_OK;
            }
            else 
            {
                $response['code']    = 200;
                $response['message'] = 'User Not Registered';
            }
        }
        $this->response($response, 200);
    }

     public function user_details_post() 
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_details') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']     = $this->form_validation->error_array();
        }
        else
        {
            
            $user_id=$data['user_id'];
            $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (!empty($chkUser))
            { 
                
                  if($data['udid']==$chkUser[0]['udid'])
                  {
                
                
                            $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                            $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                            
                            if(empty($chkUser[0]['udid']))
                            {
                                $udata['udid']  = !empty($data['udid'])?$data['udid']:'';
                            }
                            
                            $where = array('id' => $user_id);
                            $this->General_model->update(TBL_REGISTRATION, $udata, $where);
                            
                
                             $wherestring    = "registration.id='$user_id' GROUP BY registration.id";
                             $fields         = ['registration.*','IFNULL(m_main_courses.name, "") AS main_course_name','IFNULL(GROUP_CONCAT(DISTINCT courses_details.name SEPARATOR ", "), "") AS interesting_courese_list_name','IFNULL(GROUP_CONCAT(DISTINCT m_city.name SEPARATOR ", "), "") AS interesting_city_list_name','IFNULL(chat_course.name, "") AS chat_course_name','IFNULL(m_exrta_course.name, "") AS exrta_course_name'];
                            $params = array(
                                    'table'         => TBL_REGISTRATION.' as registration',
                                    'fields'        => $fields,
                                    'wherestring'   => !empty($wherestring)?$wherestring:'',
                                    'compare_type' => '=',
                                    'join_type'     => 'left',
                                    'join_tables'   => array(
                                                    TBL_MAIN_COURSES.' as m_main_courses' => 'm_main_courses.id = registration.interesting_main_course_id',
                                                    TBL_COURSES_DETAILS.' as courses_details'  => "FIND_IN_SET(courses_details.id, registration.course_details_ids) > 0",
                                                    TBL_CITY.' as m_city'  => "FIND_IN_SET(m_city.id, registration.interesting_city_ids) > 0",
                                                    TBL_CHAT_COURSE.' as chat_course' => 'chat_course.id = registration.chat_course_id',
                                                    TBL_EXRTA_COURSE.' as m_exrta_course' => 'm_exrta_course.id = registration.interesting_exrta_course_id'
                
                                                ),
                                );
                            $chkUser = $this->General_model->get_query_data($params);
                            //prd($chkUser);
                            
                            if (!empty($chkUser))
                            {
                
                                    $user_id=$data['user_id'];
                                    $today_date=date('Y-m-d');
                                    $fields         = ['package_subscription_order.subscription_expire_date'];
                                    $wherestring    = "package_subscription_order.status=1 and package_subscription_order.user_id='$user_id' and package_subscription_order.subscription_expire_date>'$today_date' order by package_subscription_order.subscription_expire_date DESC";           
                                    $params = array(
                                        'table'         => TBL_PACKAGE_SUBSCRIPTION_ORDER.' as package_subscription_order',
                                        'fields'        => $fields,
                                        'wherestring'   => !empty($wherestring)?$wherestring:''
                                    );
                                    $chkUser[0]['package_details'] = $this->General_model->get_query_data($params);  
                
                
                                    if(!empty($chkUser[0]['package_details']))
                                    {
                                        $chkUser[0]['is_subscription_active']='1';
                                    }
                                    else
                                    {
                                        $chkUser[0]['is_subscription_active']='0';
                                    }
                
                                    $response['message']    = $this->lang->line('success');
                                    $response['code']       = REST_Controller::HTTP_OK;
                                    $response['data']       = $chkUser[0];
                
                            }
                            else 
                            {
                                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                                $response['message'] = $this->lang->line('no_record_found');
                            }
                            
                  }
                  else
                  {
                        $response['code']    = 201;
                        $response['message'] = 'You are not authorised to access this application';
                         
                      
                  }
                  
                  
                  
                  
                
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
                
                
            }
            
            
           
  
        }
        $this->response($response, 200);
    }

    public function edit_user_post() 
    {   
        $data = $this->post();
        if ($this->form_validation->run('edit_profile') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {

            $id                         = $data['user_id'];
            $iData['fullname']             = !empty($data['fullname'])?$data['fullname']:'';
            $iData['city']             = !empty($data['city'])?$data['city']:'';
            $iData['cmobile']             = !empty($data['cmobile'])?$data['cmobile']:'';
            $iData['email']             = !empty($data['email'])?$data['email']:'';
            $iData['device_type']             = !empty($data['device_type'])?$data['device_type']:'';
            $iData['device_token']             = !empty($data['device_token'])?$data['device_token']:'';

            $iData['interesting_city_ids']             = !empty($data['interesting_city_ids'])?$data['interesting_city_ids']:'';
            $iData['interesting_main_course_id']       = !empty($data['interesting_main_course_id'])?$data['interesting_main_course_id']:'';
              $iData['interesting_exrta_course_id']       = !empty($data['interesting_exrta_course_id'])?$data['interesting_exrta_course_id']:'';
            $iData['course_details_ids']               = !empty($data['course_details_ids'])?$data['course_details_ids']:'';
            $iData['current_study']               = !empty($data['current_study'])?$data['current_study']:'';
            $iData['college_university_name']               = !empty($data['college_university_name'])?$data['college_university_name']:'';
            $iData['study_city']               = !empty($data['study_city'])?$data['study_city']:'';
            $iData['study_city_id']               = !empty($data['study_city_id'])?$data['study_city_id']:'';


            $where = array('id' => $id);
            $this->General_model->update(TBL_REGISTRATION, $iData, $where);
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
        }
       
        $this->response($response, 200);
    }
    public function register_user_post() 
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_register') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {
             $params = array(
                'table'         => TBL_REGISTRATION,
                'where'         => array(
                                        'username'  => "'".trim($data['username'])."'",),
                'compare_type'  => '=',
            );

            $user_register_check = $this->General_model->get_query_data($params);
            $count=count($user_register_check);

            if($count>0)
            {
                      $response['message']    = "Username Already Existing";
                      $response['code']    = REST_Controller::HTTP_BAD_REQUEST;

            }
            else
            {
                            $iData['username']             = !empty($data['username'])?$data['username']:'';
                            $iData['password']             = !empty($data['password'])?$data['password']:'';
                            $iData['fullname']             = !empty($data['fullname'])?$data['fullname']:'';
                            $iData['city']             = !empty($data['city'])?$data['city']:'';
                            $iData['cmobile']             = !empty($data['username'])?$data['username']:'';
                            $iData['email']             = !empty($data['email'])?$data['email']:'';
                            $iData['device_type']             = !empty($data['device_type'])?$data['device_type']:'';
                            $iData['device_token']             = !empty($data['device_token'])?$data['device_token']:'';
                            $iData['datee']             =date('Y-m-d H:i:s');
                            $iData['status']=1;

                            $inserted_data = $this->General_model->insert(TBL_REGISTRATION, $iData);
                            if (!empty($inserted_data))
                                {
                                    $response['message']    = $this->lang->line('success');
                                    $response['code']       = REST_Controller::HTTP_OK;
                                    $response['data']       = array('register_id'=>$inserted_data);
                                }
                                else
                                {
                                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                                    $response['message'] = $this->lang->line('warning');
                                }
            }


 
        }
       
        $this->response($response, 200);
    }
    function change_password_post()
    {
        $data = $this->post();
        
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->set_rules('current_password', 'current_password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'new_password', 'trim|required');
        
        if ($this->form_validation->run() == false) {
            $response['message'] = strip_tags(validation_errors());
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
        } else {
            $params = array(
                    'table'=>TBL_REGISTRATION,
                    'where'=>array('id' => "'".$data['user_id']."'"),
                    'compare_type' => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (!empty($chkUser)) {
                
                if ($chkUser[0]['status'] == 1) {

                    if ($chkUser[0]['password'] == $data['current_password']) {

                        $udata['password']   = !empty($data['new_password'])?$data['new_password']:'';

                        $where = array('id' => $chkUser[0]['id']);
                         $this->General_model->update(TBL_REGISTRATION, $udata, $where);

                        $response['message']         = "Password Update Successfully";
                        $response['code']       = REST_Controller::HTTP_OK;
                    } else {

                        $response['message'] = "Current Password Invalid";
                        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    }
                }
                else {
                    if($chkUser[0]['status'] == 0)
                    {
                        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                        $response['message'] = 'Account Not Activated';
                    }
                    else
                    {
                        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                        $response['message'] = $this->lang->line('user_not_registered');
                    }
                }
            }
            else {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'User Not Registered';
            }
        }
        $this->response($response, 200);
    }



          public function success_payment_post() 
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_details') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {
            $cdata['fees_amount']          = !empty($data['fees_amount'])?trim($data['fees_amount']):'';
            $cdata['txnid']             = !empty($data['txnid'])?trim($data['txnid']):'';
            $cdata['payment_gateway_history']             = !empty($data['payment_gateway_history'])?trim($data['payment_gateway_history']):'';
            $cdata['courses_details_id']             = !empty($data['courses_details_id'])?trim($data['courses_details_id']):'';
            $cdata['payment_gateway_success_status']=1;
            $cdata['payment_method']=1;
            $cdata['payment_gateway_company_id']=1;
            
             if(!empty($data['courses_details_id']))
            {
                $join_table = [
                        TBL_DURATION.' as duration'       => 'duration.id = main_courses.duration_id',
                      ];
                $params = array(
                    'table'         => TBL_MAIN_COURSES.' as main_courses',
                    'fields'        => ['main_courses.subject_ids','duration.days AS package_days'],
                    'where'         => array('main_courses.id' => $data['courses_details_id']),
                    'compare_type'  => '=',
                    'join_tables'   => $join_table,
                    'join_type'     => 'LEFT',
                );
                $chkrecord = $this->General_model->get_query_data($params);
                if(!empty($chkrecord))
                {
                    $package_days=$chkrecord[0]['package_days'];
                    $date = strtotime("+".$package_days." day");
                    $expire_date=date('Y-m-d', $date); 
                    $cdata['package_expire_date']=$expire_date;
                    
                     $cdata['subject_ids']             = $chkrecord[0]['subject_ids'];
                }
                
            }
            
            $where = array('id' => $data['user_id']);
            $this->General_model->update(TBL_REGISTRATION, $cdata, $where);
           


            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
        }
        $this->response($response, 200);
    }
    public function user_login_with_otp_post()  
    {   
        $data = $this->post();
        if (empty($data['username']))
        {
            $response['message'] = 'Mobile number is required';
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {


        $params = array(
                'table'         => TBL_REGISTRATION,
                'where'         => array(
                                        'username'  => "'".trim($data['username'])."'",
                                    ),
                'compare_type'  => '=',
            );
            $inquiry_status = $this->General_model->get_query_data($params);
            if(!empty($inquiry_status))
            {
                     if($data['username']=='9054490807' or $data['username']=='9909888769')
                    {
                        $otp='1234';
                    }
                    else
                    {
                         $otp        = mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(1,9);
                         $cmobile=$data['username'];
                        include("sms-send-register-otp.php");
                    }
                    
                        if(!empty($data['udid']))
                        {

                            $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                            $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                            $udata['udid']  = !empty($data['udid'])?$data['udid']:'';
                            $where = array('id' => $inquiry_status[0]['id']);
                            $this->General_model->update(TBL_REGISTRATION, $udata, $where);

                        }


                   
                    unset($inquiry_status[0]['password']);
                    $response['message']        = $this->lang->line('success');
                    $response['code']           = 201;          
                    $response['otp']            = $otp;        
                    $response['data']           = $inquiry_status[0];
            }
            else
            {
                    $iData['udid']             = !empty($data['udid'])?$data['udid']:'';
                    $login_id=$inquiry_status[0]['id'];
                    $where = array('id' => $login_id);
                    $this->General_model->update(TBL_REGISTRATION, $iData, $where);
                 
                    if($data['username']=='7990328784')
                    {
                        $otp='1234';
                    }
                    else
                    {
                         $otp        = mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(1,9);
                         $cmobile=$data['username'];
                         include("sms-send-register-otp.php");
                    }
                     
                     
                    

                        $response['message']        = $this->lang->line('success');
                        $response['code']           = REST_Controller::HTTP_OK; 
                        $response['otp']            = $otp;     

            }   

        }
        $this->response($response, 200);
    }

     public function verify_otp_user_post()  
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_login_with_otp') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {   
            $params = array(
                'table'         => TBL_REGISTRATION,
                'where'         => array(
                                        'username'  => "'".trim($data['username'])."'",
                                    ),
                'compare_type'  => '=',
            );
            $inquiry_status = $this->General_model->get_query_data($params);
            if(!empty($inquiry_status))
            {

                    unset($inquiry_status[0]['password']);
                    $response['message']        = $this->lang->line('success');
                    $response['code']           = 201;                 
                    $response['data']           = $inquiry_status[0];
            }
            else
            {

                        do
                        {
                            $insert_refer_code=substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),0,10);
                            $wherestring = "register.refer_code='".$insert_refer_code."'";
                            $fields         = ['register.id'];
                            $cntParams = array(
                                     'table'         => TBL_REGISTRATION.' as register',
                                     'fields'        => $fields,
                                    'wherestring'   => !empty($wherestring)?$wherestring:'',
                                    "totalrow"      => '1',
                                );
                            $refercount = $this->General_model->get_query_data($cntParams);
                            if($refercount>= 1) 
                            { 
                              $int_exists = true;  
                            } 
                            else 
                            {
                              $int_exists = false; 
                            }

                        }
                        while($int_exists);



                        $iData['username']             = !empty($data['username'])?$data['username']:'';
                        $iData['password']             = !empty($data['password'])?$data['password']:'';
                        $iData['device_type']             = !empty($data['device_type'])?$data['device_type']:'';
                        $iData['device_token']             = !empty($data['device_token'])?$data['device_token']:'';
                        $iData['udid']             = !empty($data['udid'])?$data['udid']:'';
                        $iData['cmobile']             = !empty($data['username'])?$data['username']:'';
                        $iData['datee']             =date('Y-m-d H:i:s');
                        $iData['status']=1;
                        $iData['country_id']=1;
                        $iData['refer_code']=$insert_refer_code;

                        if(!empty($data['refer_code']))
                        {  
                                $refer_code=$data['refer_code']; 

                                $wherestring = "register.username='".$refer_code."'";
                                $fields         = ['id','username'];
                                $cntParams = array(
                                'table'         => TBL_REGISTRATION.' as register',
                                'fields'        => $fields,
                                'wherestring'   => !empty($wherestring)?$wherestring:'',
                                );
                                $referuser = $this->General_model->get_query_data($cntParams);

                                if(!empty($referuser))
                                {
                                        $refer_username=$referuser[0]['username'];
                                        $refer_user_id=$referuser[0]['id'];
                                        $iData['refer_username']             =!empty($refer_username)?$refer_username:'';
                                        $iData['refer_user_id']             = !empty($refer_user_id)?$refer_user_id:'';
                                }
                        }
                        
                        $insert_id = $this->General_model->insert(TBL_REGISTRATION, $iData);
                        
                        $inquiry_status[0]['id']=number_format($insert_id);

                        $cmobile=$data['username'];

                        $response['message']        = $this->lang->line('success');
                        $response['code']           = REST_Controller::HTTP_OK; 
                        $response['data']           = $inquiry_status[0];
            }   
        }
        $this->response($response, 200);
    }
        public function resend_otp_post()  
    {   
        $data = $this->post();
        if (empty($data['username']))
        {
            $response['message'] = 'Mobile number is required';
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {   
                    $cmobile=$data['username'];
                    if($data['username']=='7990328784')
                    {
                        $otp='1234';
                    }
                    else
                    {
                         $otp        = mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(1,9);
                         include("sms-send-register-otp.php");
                    }
                    
                    
                    
                    $response['message']    = $this->lang->line('success');
                    $response['code']       = REST_Controller::HTTP_OK;
                    $response['data']       = array('otp'=>$otp);

        }
        $this->response($response, 200);
    }
    public function delete_register_user_post()  
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_details') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {   

            $fields         = ['register.id'];
            $params = array(
               'table'         => TBL_REGISTRATION.' as register',
                'where'         => array(
                                        'register.id'  => "'".trim($data['user_id'])."'",
                                    ),
               'fields'        => $fields,
               'compare_type'  => '=',
            );
            $login_details = $this->General_model->get_query_data($params);
            if(!empty($login_details))
            {

                    $this->General_model->delete(TBL_REGISTRATION,array('id' => $data['user_id']));
                    $response['message']    = $this->lang->line('success');
                    $response['code']       = REST_Controller::HTTP_OK;

 
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
        }
        $this->response($response, 200);
    }
    public function my_refer_user_post()  
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_details') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {   

            $fields         = ['register.username,register.datee,register.fullname'];
            $params = array(
               'table'         => TBL_REGISTRATION.' as register',
                'where'         => array(
                                        'register.refer_user_id'  => "'".trim($data['user_id'])."'",
                                    ),
               'fields'        => $fields,
               'compare_type'  => '=',
            );
            $login_details = $this->General_model->get_query_data($params);
            $row_count = count($login_details);
            if(!empty($login_details))
            {

                    $response['message']    = $this->lang->line('success');
                    $response['code']       = REST_Controller::HTTP_OK;
                    $response['totol_refer'] = "$row_count";
                    $response['data']           = $login_details;

 
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
                $response['totol_refer'] = "$row_count";
            }
        }
        $this->response($response, 200);
    }
    public function my_device_logout_post()  
    {   
        $data = $this->post();
        if ($this->form_validation->run('user_details') == FALSE)
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {   

            $fields         = ['register.id'];
            $params = array(
               'table'         => TBL_REGISTRATION.' as register',
                'where'         => array(
                                        'register.id'  => "'".trim($data['user_id'])."'",
                                    ),
               'fields'        => $fields,
               'compare_type'  => '=',
            );
            $login_details = $this->General_model->get_query_data($params);
            if(!empty($login_details))
            {
                          $user_id=$data['user_id'];
                    	  $iData['device_type']             = '';
                          $iData['device_token']             = '';
                          $where = array('id' => $user_id);
                          $this->General_model->update(TBL_REGISTRATION, $iData, $where);
                    
                        $response['message']    = $this->lang->line('success');
                        $response['code']       = REST_Controller::HTTP_OK;

 
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
        }
        $this->response($response, 200);
    }

}