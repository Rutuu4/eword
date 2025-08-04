<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Chat_control extends REST_Controller {

    function __construct() 
    {
        parent::__construct();
        include(substr($this->config->item('base_path'), 0,FOLDER_LENGHT).'/include/database.php');
        foreach (globalVars() as $key => $value) {
            if(is_array(${$value})){
                for ($i=1; $i <= count(${$value}) ; $i++) { 
                    $final[$value][$i] = ${$value}[$i];
                }
            }else{
                $final[$value] = ${$value};
            }
        }
        $this->globalVars         = $final;
    }

    public function chat_course_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "status=1";

        $params = array(
                'table'         => TBL_CHAT_COURSE.' as chat_course',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
            );
        $prayer_list = $this->General_model->get_query_data($params);
        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    public function chat_room_group_list_post()
    {
        $data = $this->post(); 

          $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        { 

                $chat_course_id=$data['chat_course_id'];
                if($chkUser[0]['is_app_admin']==1)
                {
                     $wherestring    = "chat_room_group.status=1";
                }
                else
                {
                     $wherestring    = "chat_room_group.status=1 and chat_room_group.chat_course_id='$chat_course_id'";
                }
               
                $params = array(
                        'table'         => TBL_CHAT_GROUP.' as chat_room_group',
                        'wherestring'   => !empty($wherestring)?$wherestring:'',
                        'compare_type'  => '=',
                    );
                $prayer_list = $this->General_model->get_query_data($params);
                if (!empty($prayer_list))
                {
                    $response['message']    = $this->lang->line('success');
                    $response['code']       = REST_Controller::HTTP_OK;
                    $response['data']       = $prayer_list;
                }
                else 
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('no_record_found');
                }
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('user_not_registered');

        }
        
        $this->response($response, 200);
    }
    public function group_chat_details_old_post()
    {
        $data = $this->post(); 
        $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        { 

        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * CHAT_MESSAGE_PAGINATION_SIZE;

        if($chkUser[0]['is_app_admin']==1)
        {
            $wherestring    = "chat_room_txn.status=1";
            $wherestring    .= " AND chat_room_txn.chat_room_group_id=".$data['chat_room_group_id']." ";
            $wherestring    .= " ORDER BY chat_room_txn.id DESC";

        }
        else
        {
            $wherestring    = "chat_room_txn.status=1 and chat_room_txn.datetime>$chat_room_group_joining_datetime";
            $wherestring    .= " AND chat_room_txn.chat_room_group_id=".$data['chat_room_group_id']." ";
            $wherestring    .= " ORDER BY chat_room_txn.id DESC";
        }

        

        $fields         = ['chat_room_txn.*,registration_s.fullname AS sender_name,registration_s.username'];
        $params = array(
                'table'         => TBL_CHAT_ROOM_TXN.' as chat_room_txn',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'fields'        => $fields,
                'compare_type'  => '=',
                "num"           => CHAT_MESSAGE_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                      TBL_REGISTRATION.' as registration_s' => 'registration_s.id = chat_room_txn.sender_id',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($aabhar_list);
        $cntParams = array(
                'table'         => TBL_CHAT_ROOM_TXN.' as chat_room_txn',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "totalrow"      => '1', );
        $total_prayer = $this->General_model->get_query_data($cntParams);

        if (!empty($total_prayer)) {
            $total_page = ceil($total_prayer / PRODUCT_PAGINATION_SIZE);
        }

        if (!empty($aabhar_list))
        {

            for($i=0; $i<count($aabhar_list); $i++)
            {
                 $aabhar_list[$i]['image']=check_file_exists($aabhar_list[$i]['image']);
            }


            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['total_page']     = isset($total_page)?$total_page:'1';   
            $response['data']       = $aabhar_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
    }
    else
    {
         $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('user_not_registered');

    }
        
        $this->response($response, 200);
    }

    public function group_chat_details_post()
    {
        $data = $this->post(); 
        $user_id=$data['user_id'];

         $fields         = ['is_app_admin,chat_room_group_joining_datetime,is_chat_block'];
         $params = array(
                    'table'=>TBL_REGISTRATION,
                    'fields'        => $fields, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        { 

             if($chkUser[0]['is_chat_block']==1)
             {

                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'Your chat service is blocked. Reach out to 7777981627 for more information.';


             }
             else
            {


                        if($chkUser[0]['is_app_admin']==1)
                        {
                            $wherestring    = "chat_room_txn.status=1";
                            $wherestring    .= " AND chat_room_txn.chat_room_group_id=".$data['chat_room_group_id']." ";
                            $wherestring    .= " ORDER BY chat_room_txn.id DESC";

                        }
                        else
                        {
                            $chat_room_group_joining_datetime=$chkUser[0]['chat_room_group_joining_datetime'];
                            $wherestring    = "chat_room_txn.status=1 and chat_room_txn.datetime>'$chat_room_group_joining_datetime'";
                            $wherestring    .= " AND chat_room_txn.chat_room_group_id=".$data['chat_room_group_id']." ";
                            $wherestring    .= " ORDER BY chat_room_txn.id DESC";
                        }

                        $fields         = ['chat_room_txn.id,chat_room_txn.message,chat_room_txn.is_image,chat_room_txn.image,chat_room_txn.datetime,chat_room_txn.date,registration_s.fullname AS sender_name,registration_s.username,chat_room_txn.sender_id'];
                        $params = array(
                                'table'         => TBL_CHAT_ROOM_TXN.' as chat_room_txn',
                                'wherestring'   => !empty($wherestring)?$wherestring:'',
                                'fields'        => $fields,
                                'compare_type'  => '=',
                                'join_type'     => 'left',
                                'join_tables'   => array(
                                                      TBL_REGISTRATION.' as registration_s' => 'registration_s.id = chat_room_txn.sender_id',
                                                ),
                            );
                         $aabhar_list = $this->General_model->get_query_data($params);
                        if (!empty($aabhar_list))
                        {

                            for($i=0; $i<count($aabhar_list); $i++)
                            {
                                 $aabhar_list[$i]['image']=check_file_exists($aabhar_list[$i]['image']);
                            }


                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK; 
                            $response['data']       = $aabhar_list;
                        }
                        else 
                        {
                            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                            $response['message'] = $this->lang->line('no_record_found');
                        }
                    }
                    
        }
        else
        {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('user_not_registered');

        }




        
        $this->response($response, 200);
    }
    

    public function send_message_post()
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
                    $insData = array (
                    "sender_id"        => !empty($data['user_id'])?$data['user_id']:'',
                    "message"          => !empty($data['message'])?$data['message']:'',
                     "message"          => !empty($data['message'])?$data['message']:'',
                    "date"             => date('Y-m-d'),
                    "datetime"         => date('Y-m-d H:i:s'),
                    );

                    $iData['sender_id']             = !empty($data['user_id'])?$data['user_id']:'';
                    $iData['chat_room_group_id']             = !empty($data['chat_room_group_id'])?$data['chat_room_group_id']:'';
                    $iData['message']             = !empty($data['message'])?$data['message']:'';
                    $iData['is_image']             = !empty($data['is_image'])?$data['is_image']:'';
                    $iData['date']                 = date('Y-m-d');     
                    $iData['datetime']             = date('Y-m-d H:i:s');

                    if(!empty($_FILES['image']['name']))
                    {
                                    $this->load->library('upload');
                                    $_FILES['file']['name']       = $_FILES['image']['name'];
                                    $_FILES['file']['type']       = $_FILES['image']['type'];
                                    $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'];
                                    $_FILES['file']['error']      = $_FILES['image']['error'];
                                    $_FILES['file']['size']       = $_FILES['image']['size'];
                                    $priNewImg          = explode('.', $_FILES["image"]['name']);
                                    $priExtension       = end($priNewImg);
                                    $uploadPath                   = IMAGE_PATH.'/chat-image/';
                                    $config['file_name']        = 'image-'.time().'.'.$priExtension;
                                    $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                    $config['allowed_types'] = '*'; // Allow all file types
                                    $this->upload->initialize($config);
                                    if($this->upload->do_upload('file'))
                                    {
                                        $preImageData = $this->upload->data();   
                                        $iData['image'] ='chat-image/'.$preImageData['file_name'];

                                        $image_fullpath=$this->config->item('org_base_path').$uploadPath.$preImageData['file_name'];
                                        $this->general_model->main_image_compress($image_fullpath);

                                    }
                    }


               
                $inserted_data = $this->General_model->insert(TBL_CHAT_ROOM_TXN, $iData);
                if (!empty($inserted_data))
                {

                    $response['message']    = $this->lang->line('success');
                    $response['code']       = REST_Controller::HTTP_OK;
                }
                else
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('warning');
                }
            
        }
        $this->response($response, 200);
    }
    public function chat_group_member_details_post()
    {

        $data = $this->post(); 

                $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {  
                        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
                        $per_page   = $page * CHAT_GROUP_MEMBER_PAGINATION_SIZE;

                        $wherestring    = "register.status=1";

                        $wherestring .= " AND register.chat_room_group_id=".$data['chat_room_group_id']."";
                        

                        $searchkeyword  = mysqli_real_escape_string($this->db->conn_id, (trim(stripslashes($data['search_text']))));
                        if(!empty($searchkeyword))
                        {
                                $wherestring    .= " AND (register.fullname like '%".$searchkeyword."%'  or register.username like '%".$searchkeyword."%')";
                        }

                        if($chkUser[0]['is_app_admin']==1)
                        {
                            $wherestring .= " AND register.id!=".$data['user_id']."";
                        }

                        $wherestring .= " Order BY register.fullname ASC";

                        $fields         = ['register.id,register.username,register.fullname,register.is_chat_block'];
                        $params = array(
                                'table'         => TBL_REGISTRATION.' as register',
                                  'fields'        => $fields,
                                'wherestring'   => !empty($wherestring)?$wherestring:'',
                                'compare_type'  => '=',
                                "num"           => CHAT_GROUP_MEMBER_PAGINATION_SIZE,
                                "offset"        => $per_page,
                            );
                        $aabhar_list = $this->General_model->get_query_data($params);
                        //prd($prayer_list);

                        $cntParams = array(
                                'table'         => TBL_REGISTRATION.' as register',
                                'wherestring'   => !empty($wherestring)?$wherestring:'',
                                'compare_type'  => '=',
                                "totalrow"      => '1' );
                        $total_prayer = $this->General_model->get_query_data($cntParams);

                        if (!empty($total_prayer)) 
                        {
                            $total_page = ceil($total_prayer / PRODUCT_PAGINATION_SIZE);
                        }

                        if (!empty($aabhar_list))
                        {
                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK;
                            $response['total_page']     = isset($total_page)?$total_page:'1';   
                            $response['data']       = $aabhar_list;
                        }
                        else 
                        {
                            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                            $response['message'] = $this->lang->line('no_record_found');
                        }


        }
        else
        {
             $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
             $response['message'] = $this->lang->line('user_not_registered');

        }



        
        $this->response($response, 200);
    }
      public function join_chat_group_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           
           if(!empty($data['chat_room_group_id']))
            {
                 $udata['chat_room_group_id']=!empty($data['chat_room_group_id'])?$data['chat_room_group_id']:'';
                 $udata['chat_room_group_joining_datetime']=date("Y-m-d H:i:s");
                 
                 $where = array('id' => $data['user_id']);
                 $this->General_model->update(TBL_REGISTRATION, $udata, $where);

                $response['message']    = $this->lang->line('success');
                $response['code']       = REST_Controller::HTTP_OK;
            }
            else
            { 
                 $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                 $response['message'] = 'chat_room_group_id is not empty';
            }      
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
    public function delete_message_post() 
    {   
        $data = $this->post();

        $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {

            if($chkUser[0]['is_app_admin']==1)
            {
                 $result=$this->general_model->delete(TBL_CHAT_ROOM_TXN,array('id' => $data['mesage_id']));
            }
            else
            {
                 $result=$this->general_model->delete(TBL_CHAT_ROOM_TXN,array('id' => $data['mesage_id'],'sender_id' => $data['user_id']));
            }

               $response['message']    = $this->lang->line('success');
               $response['code']       = REST_Controller::HTTP_OK;

           
            
        }
        else
        {
            $response['message'] = $this->lang->line('no_record_found');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
        }
       
        $this->response($response, 200);
    }
     public function block_member_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           

            $udata['is_chat_block']=1;
            $where = array('id' => $data['member_id']);
            $this->General_model->update(TBL_REGISTRATION, $udata, $where);

            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;

        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
    public function unblock_member_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           

            $udata['is_chat_block']=0;
            $where = array('id' => $data['member_id']);
            $this->General_model->update(TBL_REGISTRATION, $udata, $where);

            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;

        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
    public function change_chat_group_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           

            if($chkUser[0]['is_chnage_group']>1)
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'You have already changed the course of the chat twice; no further changes are possible.';
            }
            else
            {

                    if(!empty($data['chat_course_id']) and $chkUser[0]['chat_course_id']!=$data['chat_course_id'])
                    {
                        $chat_course_id = $data['chat_course_id'];
                        $this->db->select('id');
                        $this->db->from('chat_room_group');
                        $this->db->where('chat_course_id', $chat_course_id);
                        $query = $this->db->get();
                        $rowcount = $query->num_rows();
                        if ($query->num_rows() > 0) 
                        { 
                             $row = $query->row_array(); 
                             $chat_room_group_id = $row['id']; 

                             $udata['chat_course_id']=$data['chat_course_id'];
                             $udata['chat_room_group_id']=$chat_room_group_id;
                             $udata['chat_room_group_joining_datetime']=date("Y-m-d H:i:s");
                             $where = array('id' => $data['user_id']);
                             $this->General_model->update(TBL_REGISTRATION, $udata, $where);
                             $this->General_model->increase_field_value('is_chnage_group',TBL_REGISTRATION, $where);

                             $response['message']    = $this->lang->line('success');
                             $response['code']       = REST_Controller::HTTP_OK;

                        }
                        else
                        {
                            $response['message']    = 'Group Not Activated';
                            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;

                        }


                    }
                    else
                    { 
                         $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                         $response['message'] = 'The current chat course and the new chat course are the same.';
                    }  

            }


    
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }





}
