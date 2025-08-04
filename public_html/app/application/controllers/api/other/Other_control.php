<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Other_control extends REST_Controller {

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

    public function logout_get()
    {
        $postData = $this->get();
        if ($postData['user_id'] == '')
        {
            $response['message'] = $this->lang->line('input_fields_required');
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['errors']  = $this->form_validation->error_array();
        }
        else
        {
            $udata['device_token']       = '';
            $where = array('id' => $postData['user_id']);
            $this->General_model->update($this->tbl_user, $udata, $where);

            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
        }
       
        $this->response($response, 200);
    }
    
    
    public function version_get()
    {
        
        
$share_message = <<<TEXT
E World (Education World) 📚✨

Simplify your educational journey with this amazing app! Explore a wealth of information, including:
✔️ Courses
✔️ Colleges & Universities
✔️ Admission Processes
✔️ Cut-Off Marks
✔️ Scholarships & Loans and much more!

Exclusive Feature: My Chat Group  
Connect with students from your field to share insights, resources, and valuable updates.

💬 My Referral Code: XXX  
📲 Register Now:

Step 1: Download the app.

Step 2: Enter the referral code XXX.

Step 3: Complete the registration process.

Download the app here:  
Android: https://play.google.com/store/apps/details?id=com.app.eworldapp  
iPhone: https://apps.apple.com/us/app/e-worldeducation/id6742198981  

Don’t miss out—register today and take the next step in your education! 🚀
TEXT;

        $response['share_message'] = $share_message;
        $response['message']    = $this->lang->line('success');
        $response['code']       = REST_Controller::HTTP_OK;
        $response['version']    = 13;
        $response['logout']     = FALSE;
        $this->response($response, 200);
    }
    public function prayer_list_post()
    {
        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "prayer_request.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND prayer_request.date between '$from_date' and  '$to_date'";
         }

        if(!empty($data['id']))
        {

             $wherestring .= " AND prayer_request.id=".$data['id']."";
        }
        
        $orderby        = 'prayer_request.date';
        $sortby         = 'DESC';

         $fields         = ['prayer_request.*','m_prayer_request_category.name AS prayer_request_category'];

        $params = array(
                'table'         => TBL_PRAYER_REQUEST.' as prayer_request',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'          => $sortby,
                'compare_type'  => '=',
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_PRAYER_REQUEST_CATEGORY.' as m_prayer_request_category' => 'm_prayer_request_category.id = prayer_request.prayer_request_category_id',
                                ),
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        $cntParams = array(
                'table'         => TBL_PRAYER_REQUEST.' as prayer_request',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                'compare_type'  => '=',
                "totalrow"      => '1' );
        $total_prayer = $this->General_model->get_query_data($cntParams);

        if (!empty($total_prayer)) {
            $total_page = ceil($total_prayer / PRODUCT_PAGINATION_SIZE);
        }


        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['total_page']     = isset($total_page)?$total_page:'1';   
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }

     public function aabhar_patra_list_post()
    {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "aabhar_patra.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND aabhar_patra.date_of_decease between '$from_date' and  '$to_date'";
         }

        if(!empty($data['id']))
        {

             $wherestring .= " AND aabhar_patra.id=".$data['id']."";
        }
        
          $orderby        = 'aabhar_patra.id';
        $sortby         = 'DESC';

        $params = array(
                'table'         => TBL_AABHAR_PATRA.' as aabhar_patra',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'        => $sortby,
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        $cntParams = array(
                'table'         => TBL_AABHAR_PATRA.' as aabhar_patra',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                'compare_type'  => '=',
                "totalrow"      => '1' );
        $total_prayer = $this->General_model->get_query_data($cntParams);

        if (!empty($total_prayer)) {
            $total_page = ceil($total_prayer / PRODUCT_PAGINATION_SIZE);
        }

        for ($i=0; $i < count($aabhar_list); $i++) 
        {

                    $aabhar_list[$i]['photo']        = check_file_exists($aabhar_list[$i]['photo']);
                    $aabhar_list[$i]['upload_file']        = check_file_exists($aabhar_list[$i]['upload_file']);

        }

        if (!empty($aabhar_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['total_page']     = isset($total_page)?$total_page:'1';   
            $response['data']       = $aabhar_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    
      public function apni_khabar_antar_list_post()
     {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "apni_khabar_antar.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND apni_khabar_antar.news_date between '$from_date' and  '$to_date'";
         }

          if(!empty($data['id']))
        {

             $wherestring .= " AND apni_khabar_antar.id=".$data['id']."";
        }
        
        
        $orderby        = 'apni_khabar_antar.id';
        $sortby         = 'DESC';

        $params = array(
                'table'         => TBL_AAPNI_KHABAR_ANTAR.' as apni_khabar_antar',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'        => $sortby,
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        $cntParams = array(
                'table'         => TBL_AAPNI_KHABAR_ANTAR.' as apni_khabar_antar',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                'compare_type'  => '=',
                "totalrow"      => '1' );
        $total_prayer = $this->General_model->get_query_data($cntParams);

        if (!empty($total_prayer)) {
            $total_page = ceil($total_prayer / PRODUCT_PAGINATION_SIZE);
        }

        for ($i=0; $i < count($aabhar_list); $i++) 
        {
                $aabhar_list[$i]['file_upload'] = check_file_exists($aabhar_list[$i]['file_upload']);
                $aabhar_list[$i]['img1']        = check_file_exists($aabhar_list[$i]['img1']);
                $aabhar_list[$i]['img2']        = check_file_exists($aabhar_list[$i]['img2']);
                $aabhar_list[$i]['img3']        = check_file_exists($aabhar_list[$i]['img3']);

        }

        if (!empty($aabhar_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['total_page']     = isset($total_page)?$total_page:'1';   
            $response['data']       = $aabhar_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }

        public function language_list_get()
    {
       
        $where         = array('status' => 1);
        $params = array(
                'table'         => TB_LANGUAGE,
                'where'         => $where,
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }

      public function bhajan_sangrah_list_post()
     {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "bhajan_sangrah.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND bhajan_sangrah.create_date between '$from_date' and  '$to_date'";
         }

         if(!empty($data['language_id']))
         {
                $wherestring .= " AND bhajan_sangrah.language_id=".$data['language_id']."";
         }

          if(!empty($data['id']))
        {

             $wherestring .= " AND bhajan_sangrah.id=".$data['id']."";
        }



        $searchkeyword  = mysqli_real_escape_string($this->db->conn_id, (trim(stripslashes($data['search_text']))));

        if(!empty($searchkeyword)){
                $wherestring    .= " AND (bhajan_sangrah.title like '%".$searchkeyword."%')";
            }



        $fields         = ['bhajan_sangrah.*','m_language.name AS language_name'];
        $params = array(
                'table'         => TB_BHAJAN_SANGRAH.' as bhajan_sangrah',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TB_LANGUAGE.' as m_language' => 'm_language.id = bhajan_sangrah.language_id',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);


          for ($i=0; $i < count($aabhar_list); $i++) 
        {

                $aabhar_list[$i]['img']        = check_file_exists($aabhar_list[$i]['img']);
                $aabhar_list[$i]['audio_file'] = check_file_exists($aabhar_list[$i]['audio_file']);
                $aabhar_list[$i]['audio_file2'] = check_file_exists($aabhar_list[$i]['audio_file2']);
                $aabhar_list[$i]['audio_file3'] = check_file_exists($aabhar_list[$i]['audio_file3']);

                

                $aabhar_list[$i]['is_favourite']    =  '';
                if(!empty($data['user_id'])){
                $params = array(
                    'table'         => TBL_BHAJAN_FAVOURITE,
                    'where'         => array(
                                                    'user_id'       => $data['user_id'],
                                                    'bhajan_id'    => $aabhar_list[$i]['id'],
                                                ),
                            'compare_type'  => '=',
                        );
                        $checkProductFavourite = $this->General_model->get_query_data($params);
                        if(!empty($checkProductFavourite)){
                            $aabhar_list[$i]['is_favourite']    =  '1';
                        }
                    }


        }


        $cntParams = array(
                'table'         => TB_BHAJAN_SANGRAH.' as bhajan_sangrah',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
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
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
      public function biography_list_post()
     {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "biography.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND biography.date_of_decease between '$from_date' and  '$to_date'";
         }

          if(!empty($data['id']))
        {

             $wherestring .= " AND biography.id=".$data['id']."";
        }


          $orderby        = 'biography.id';
        $sortby         = 'DESC';

        $fields         = ['biography.*'];
        $params = array(
                'table'         => TB_BIOGRAPHY.' as biography',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'        => $sortby,
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

          for ($i=0; $i < count($aabhar_list); $i++) 
        {

                $aabhar_list[$i]['photo']        = check_file_exists($aabhar_list[$i]['photo']);
                $aabhar_list[$i]['upload_file'] = check_file_exists($aabhar_list[$i]['upload_file']);

        }

        $cntParams = array(
                'table'         => TB_BIOGRAPHY.' as biography',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
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
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    public function death_notification_list_post()
     {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "death_notification.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND death_notification.date between '$from_date' and  '$to_date'";
         }

         if(!empty($data['id']))
        {

             $wherestring .= " AND death_notification.id=".$data['id']."";
        }
        
        //$orderby        = 'death_notification.obituary_funeral_date';
        $orderby        = 'death_notification.id';
        $sortby         = 'DESC';


        $fields         = ['death_notification.*'];
        $params = array(
                'table'         => TBL_DEATH_NOTIFICATION.' as death_notification',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'        => $sortby,
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

          for ($i=0; $i < count($aabhar_list); $i++) 
        {

                $aabhar_list[$i]['photo']        = check_file_exists($aabhar_list[$i]['photo']);
                $aabhar_list[$i]['file_upload'] = check_file_exists($aabhar_list[$i]['file_upload']);

        }

        $cntParams = array(
                'table'         => TBL_DEATH_NOTIFICATION.' as death_notification',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
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
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
       
    public function message_type_get()
    {
       
        $where         = array('status' => 1);
        $params = array(
                'table'         => TBL_MESSAGE_TYPE,
                'where'         => $where,
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
   public function typewise_god_message_list_post()
    {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "god_messages.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND god_messages.date between '$from_date' and  '$to_date'";
         }

        if(!empty($data['id']))
        {

             $wherestring .= " AND god_messages.id=".$data['id']."";
        }

         $wherestring .= " AND god_messages.type_id=".$data['type_id']."";
         
        $wherestring .= " ORDER BY god_messages.date DESC";   

        $fields         = ['god_messages.*','m_message_type.name AS message_type','m_national_origin.name AS country_name'];
        $params = array(
                'table'         => TBL_GOD_MESSAGE.' as god_messages',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_MESSAGE_TYPE.' as m_message_type' => 'm_message_type.id = god_messages.type_id',
                                    TBL_NATIONAL_ORGIN.' as m_national_origin' => 'm_national_origin.id = god_messages.country',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);


          for ($i=0; $i < count($aabhar_list); $i++) 
        {

                $aabhar_list[$i]['symbols_image']        = check_file_exists($aabhar_list[$i]['symbols_image']);
                $aabhar_list[$i]['audio_file']        = check_file_exists($aabhar_list[$i]['audio_file']);

        }

        $cntParams = array(
                'table'         => TBL_GOD_MESSAGE.' as god_messages',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
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
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }


    
     public function add_edit_prayer_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['prayer_id']))
                    {
                        $id                                 = $data['prayer_id'];
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['prayer_request_category_id'] = !empty($data['prayer_request_category_id'])?$data['prayer_request_category_id']:'';
                        $insData['title']                 = !empty($data['title'])?$data['title']:'';
                        $insData['city']                    = !empty($data['city'])?$data['city']:'';
                        $insData['country']                 = !empty($data['country'])?$data['country']:'';
                        $insData['prayer_request_for']      = !empty($data['prayer_request_for'])?$data['prayer_request_for']:'';
                        $insData['prayer_requested_by']           = !empty($data['prayer_requested_by'])?$data['prayer_requested_by']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']              = !empty($data['date'])?$data['date']:'';
                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_PRAYER_REQUEST, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {                       
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['prayer_request_category_id'] = !empty($data['prayer_request_category_id'])?$data['prayer_request_category_id']:'';
                        $insData['title']                 = !empty($data['title'])?$data['title']:'';
                        $insData['city']                    = !empty($data['city'])?$data['city']:'';
                        $insData['country']                 = !empty($data['country'])?$data['country']:'';
                        $insData['prayer_request_for']      = !empty($data['prayer_request_for'])?$data['prayer_request_for']:'';
                        $insData['prayer_requested_by']           = !empty($data['prayer_requested_by'])?$data['prayer_requested_by']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']              = !empty($data['date'])?$data['date']:'';
                        $insData['create_date']       = date('Y-m-d');

                        $inserted_data = $this->general_model->insert(TBL_PRAYER_REQUEST, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }

    public function add_edit_abhar_patra_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['abhar_patra_id']))
                    {
                        $id                                 = $data['abhar_patra_id'];
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_decease']                 = !empty($data['name_of_decease'])?$data['name_of_decease']:'';
                        $insData['date_of_decease']                  = !empty($data['date_of_decease'])?$data['date_of_decease']:'';
                        $insData['city']                 = !empty($data['city'])?$data['city']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:'';
                        $insData['note']        = !empty($data['note'])?$data['note']:'';

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
                                        $uploadPath         = '/aabhar-patra-photo/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['photo'] = 'aabhar-patra-photo/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['upload_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['upload_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['upload_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['upload_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['upload_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['upload_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["upload_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/aabhar-patra-upload-files/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['upload_file'] = 'aabhar-patra-upload-files/'.$preImageData['file_name'];
                                        }
                        }

                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_AABHAR_PATRA, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {         


                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_decease']                 = !empty($data['name_of_decease'])?$data['name_of_decease']:'';
                        $insData['date_of_decease']                  = !empty($data['date_of_decease'])?$data['date_of_decease']:'';
                        $insData['city']                 = !empty($data['city'])?$data['city']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:'';
                        $insData['note']        = !empty($data['note'])?$data['note']:'';
                        $insData['date']       = date('Y-m-d');
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
                                        $uploadPath         = '/aabhar-patra-photo/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['photo'] = 'aabhar-patra-photo/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['upload_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['upload_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['upload_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['upload_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['upload_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['upload_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["upload_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/aabhar-patra-upload-files/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['upload_file'] = 'aabhar-patra-upload-files/'.$preImageData['file_name'];
                                        }
                        }


                        $inserted_data = $this->general_model->insert(TBL_AABHAR_PATRA, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }

        public function add_edit_biography_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['biographi_id']))
                    {
                        $id                              = $data['biographi_id'];
                        $insData['user_id']              = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_decease']      = !empty($data['name_of_decease'])?$data['name_of_decease']:'';
                        $insData['date_of_decease']      = !empty($data['date_of_decease'])?$data['date_of_decease']:'';
                        $insData['address']              = !empty($data['address'])?$data['address']:'';
                        $insData['city']                 = !empty($data['city'])?$data['city']:'';
                        $insData['country']              = !empty($data['country'])?$data['country']:'';
                        $insData['note']                 = !empty($data['note'])?$data['note']:'';

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
                                        $uploadPath         = '/biography-photo/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['photo'] = 'biography-photo/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['upload_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['upload_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['upload_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['upload_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['upload_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['upload_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["upload_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/biography-upload-files/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                         $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['upload_file'] = 'biography-upload-files/'.$preImageData['file_name'];
                                        }
                        }

                        $where = array('id' => $id); 
                        $this->General_model->update(TB_BIOGRAPHY, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {         


                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_decease']                 = !empty($data['name_of_decease'])?$data['name_of_decease']:'';
                        $insData['date_of_decease']                  = !empty($data['date_of_decease'])?$data['date_of_decease']:'';
                        $insData['city']                 = !empty($data['city'])?$data['city']:'';
                        $insData['address']              = !empty($data['address'])?$data['address']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:'';
                        $insData['note']        = !empty($data['note'])?$data['note']:'';
                        $insData['date']       = date('Y-m-d');
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
                                        $uploadPath         = '/biography-photo/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['photo'] = 'biography-photo/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['upload_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['upload_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['upload_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['upload_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['upload_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['upload_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["upload_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/biography-upload-files/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['upload_file'] = 'biography-upload-files/'.$preImageData['file_name'];
                                        }
                        }


                        $inserted_data = $this->general_model->insert(TB_BIOGRAPHY, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }

      public function advertisement_type_list_get()
    {
       
        $where         = array('status' => 1);
        $params = array(
                'table'         => TB_ADVERTISEMENT_TYPE,
                'where'         => $where,
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    public function fund_raising_type_list_get()
    {
       
        $where         = array('status' => 1);
        $params = array(
                'table'         => TBL_FUND_TYPE,
                'where'         => $where,
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
        public function add_edit_bhajan_sangrah_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['bhajan_sangrah_id']))
                    {
                        $id                              = $data['bhajan_sangrah_id'];
                        $insData['user_id']              = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['language_id']      = !empty($data['language_id'])?$data['language_id']:'';
                        $insData['title']      = !empty($data['title'])?$data['title']:'';
                        $insData['details']              = !empty($data['details'])?$data['details']:'';
                        $insData['send_user_name']                 = !empty($data['send_user_name'])?$data['send_user_name']:'';
                        $insData['song_by']                 = !empty($data['song_by'])?$data['song_by']:'';
                        
                        $insData['song_by_2']                 = !empty($data['song_by_2'])?$data['song_by_2']:'';
                        $insData['song_by_3']                 = !empty($data['song_by_3'])?$data['song_by_3']:'';

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
                                        $uploadPath         = '/bhajan-sangrah-images/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img'] = 'bhajan-sangrah-images/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["audio_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/bhajan-sangrah-audio-files/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'mp3|mp4|3gp|aiff|alac';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file'] = 'bhajan-sangrah-audio-files/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file2']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file2']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file2']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file2']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file2']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file2']['size'];
                                        $priNewImg          = explode('.', $_FILES["audio_file2"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/bhajan-sangrah-audio-files/';
                                        $config['file_name']        = $data['title'].'-2-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'mp3|mp4|3gp|aiff|alac';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file2'] = 'bhajan-sangrah-audio-files/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file3']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file3']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file3']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file3']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file3']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file3']['size'];
                                        $priNewImg          = explode('.', $_FILES["audio_file3"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/bhajan-sangrah-audio-files/';
                                        $config['file_name']        = $data['title'].'-3-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'mp3|mp4|3gp|aiff|alac';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file3'] = 'bhajan-sangrah-audio-files/'.$preImageData['file_name'];
                                        }
                        }

                        $where = array('id' => $id); 
                        $this->General_model->update(TB_BHAJAN_SANGRAH, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {         

                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['create_date']       = date('Y-m-d');
                        $insData['language_id']      = !empty($data['language_id'])?$data['language_id']:'';
                        $insData['title']      = !empty($data['title'])?$data['title']:'';
                        $insData['details']              = !empty($data['details'])?$data['details']:'';
                        $insData['send_user_name']                 = !empty($data['send_user_name'])?$data['send_user_name']:'';
                        $insData['song_by']                 = !empty($data['song_by'])?$data['song_by']:'';
                        $insData['song_by_2']                 = !empty($data['song_by_2'])?$data['song_by_2']:'';
                        $insData['song_by_3']                 = !empty($data['song_by_3'])?$data['song_by_3']:'';

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
                                        $uploadPath         = '/bhajan-sangrah-images/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img'] = 'bhajan-sangrah-images/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["audio_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/bhajan-sangrah-audio-files/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'mp3|mp4|3gp|aiff|alac';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file'] = 'bhajan-sangrah-audio-files/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file2']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file2']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file2']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file2']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file2']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file2']['size'];
                                        $priNewImg          = explode('.', $_FILES["audio_file2"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/bhajan-sangrah-audio-files/';
                                        $config['file_name']        = $data['title'].'-2-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'mp3|mp4|3gp|aiff|alac';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file2'] = 'bhajan-sangrah-audio-files/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file3']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file3']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file3']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file3']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file3']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file3']['size'];
                                        $priNewImg          = explode('.', $_FILES["audio_file3"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/bhajan-sangrah-audio-files/';
                                        $config['file_name']        = $data['title'].'-3-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'mp3|mp4|3gp|aiff|alac';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file3'] = 'bhajan-sangrah-audio-files/'.$preImageData['file_name'];
                                        }
                        }
                       


                        $inserted_data = $this->general_model->insert(TB_BHAJAN_SANGRAH, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }

      public function add_edit_death_notification_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['death_notification_id']))
                    {
                        $id                                 = $data['death_notification_id'];
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_decease']         = !empty($data['name_of_decease'])?$data['name_of_decease']:'';
                        $insData['birthdate']               = !empty($data['birthdate'])?$data['birthdate']:'';
                        $insData['date_of_decease']         = !empty($data['date_of_decease'])?$data['date_of_decease']:'';
                        $insData['address']                 = !empty($data['address'])?$data['address']:'';
                        $insData['city']                 = !empty($data['city'])?$data['city']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:'';
                        $insData['note']        = !empty($data['note'])?$data['note']:'';
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
                                        $uploadPath         = '/death-notification-photos/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['photo'] = 'death-notification-photos/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['upload_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['upload_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['upload_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['upload_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['upload_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['upload_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["upload_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/death-notification-upload-files/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['file_upload'] = 'death-notification-upload-files/'.$preImageData['file_name'];
                                        }
                        }

                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_DEATH_NOTIFICATION, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {         


                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['date']       = date('Y-m-d');
                        $insData['name_of_decease']         = !empty($data['name_of_decease'])?$data['name_of_decease']:'';
                        $insData['birthdate']               = !empty($data['birthdate'])?$data['birthdate']:'';
                        $insData['date_of_decease']         = !empty($data['date_of_decease'])?$data['date_of_decease']:'';
                        $insData['address']                 = !empty($data['address'])?$data['address']:'';
                        $insData['city']                 = !empty($data['city'])?$data['city']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:'';
                        $insData['obituary_funeral_date']            = !empty($data['obituary_funeral_date'])?$data['obituary_funeral_date']:'';
                        $insData['note']        = !empty($data['note'])?$data['note']:'';
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
                                        $uploadPath         = '/death-notification-photos/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['photo'] = 'death-notification-photos/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['upload_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['upload_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['upload_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['upload_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['upload_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['upload_file']['size'];
                                        $priNewImg          = explode('.', $_FILES["upload_file"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/death-notification-upload-files/';
                                        $config['file_name']        = $data['name_of_decease'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['file_upload'] = 'death-notification-upload-files/'.$preImageData['file_name'];
                                        }
                        }


                        $inserted_data = $this->general_model->insert(TBL_DEATH_NOTIFICATION, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }
    
    public function add_edit_god_message_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['god_message_id']))
                    {
                        $id                            = $data['god_message_id'];
                        $insData['user_id']            = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['type_id']            = !empty($data['type_id'])?$data['type_id']:'';
                        $insData['title']              = !empty($data['title'])?$data['title']:'';
                        $insData['details']            = !empty($data['details'])?$data['details']:'';
                        $insData['city']               = !empty($data['city'])?$data['city']:'';
                        $insData['state']              = !empty($data['state'])?$data['state']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:'';

                        $insData['name_of_church_pastor']            = !empty($data['name_of_church_pastor'])?$data['name_of_church_pastor']:'';
                        $insData['youtube_link']            = !empty($data['youtube_link'])?$data['youtube_link']:'';
                        if(!empty($_FILES['symbols_image']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['symbols_image']['name'];
                                        $_FILES['file']['type']       = $_FILES['symbols_image']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['symbols_image']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['symbols_image']['error'];
                                        $_FILES['file']['size']       = $_FILES['symbols_image']['size'];
                                        $priNewImg          = explode('.', $_FILES["symbols_image"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/god-message-symbols-images/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['symbols_image'] = 'god-message-symbols-images/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file']['size'];
                                        $priNewImg                    = explode('.', $_FILES["audio_file"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/god-message-audio-files/';
                                        $config['file_name']          = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']      = 'mp3|aiff|alac|aac|wma|wav|flac|m4a|jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file'] = 'god-message-audio-files/'.$preImageData['file_name'];
                                        }
                        }

                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_GOD_MESSAGE, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {   
                        $insData['user_id']            = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['type_id']            = !empty($data['type_id'])?$data['type_id']:'';
                        $insData['title']              = !empty($data['title'])?$data['title']:'';
                        $insData['details']            = !empty($data['details'])?$data['details']:'';
                        $insData['city']               = !empty($data['city'])?$data['city']:'';
                        $insData['state']              = !empty($data['state'])?$data['state']:'';
                        $insData['country']            = !empty($data['country'])?$data['country']:''; 
                        $insData['name_of_church_pastor']            = !empty($data['name_of_church_pastor'])?$data['name_of_church_pastor']:'';
                        $insData['youtube_link']            = !empty($data['youtube_link'])?$data['youtube_link']:'';
                        $insData['date']       = date('Y-m-d');
                         if(!empty($_FILES['symbols_image']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['symbols_image']['name'];
                                        $_FILES['file']['type']       = $_FILES['symbols_image']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['symbols_image']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['symbols_image']['error'];
                                        $_FILES['file']['size']       = $_FILES['symbols_image']['size'];
                                        $priNewImg          = explode('.', $_FILES["symbols_image"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/god-message-symbols-images/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['symbols_image'] = 'god-message-symbols-images/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['audio_file']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['audio_file']['name'];
                                        $_FILES['file']['type']       = $_FILES['audio_file']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['audio_file']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['audio_file']['error'];
                                        $_FILES['file']['size']       = $_FILES['audio_file']['size'];
                                        $priNewImg                    = explode('.', $_FILES["audio_file"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/god-message-audio-files/';
                                        $config['file_name']          = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']      = 'mp3|aiff|alac|aac|wma|wav|flac|m4a|jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['audio_file'] = 'god-message-audio-files/'.$preImageData['file_name'];
                                        }
                        }
                       

                        $inserted_data = $this->general_model->insert(TBL_GOD_MESSAGE, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }
     public function add_edit_apani_khabar_anter_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['apani_khabar_anter_id']))
                    {
                        $id                            = $data['apani_khabar_anter_id'];
                        $insData['user_id']            = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['first_name']            = !empty($data['first_name'])?$data['first_name']:'';
                        $insData['last_name']              = !empty($data['last_name'])?$data['last_name']:'';
                        $insData['city']            = !empty($data['city'])?$data['city']:'';
                        $insData['country']               = !empty($data['country'])?$data['country']:'';
                        $insData['title']              = !empty($data['title'])?$data['title']:'';
                        $insData['details']            = !empty($data['details'])?$data['details']:'';
                        $insData['news_date']            = !empty($data['news_date'])?$data['news_date']:'';
                        $insData['share_link']            = !empty($data['share_link'])?$data['share_link']:'';



                        if(!empty($_FILES['file_upload']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['file_upload']['name'];
                                        $_FILES['file']['type']       = $_FILES['file_upload']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['file_upload']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['file_upload']['error'];
                                        $_FILES['file']['size']       = $_FILES['file_upload']['size'];
                                        $priNewImg          = explode('.', $_FILES["file_upload"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/aapni-khabar-antar-upload-files/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['file_upload'] = 'aapni-khabar-antar-upload-files/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['img1']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img1']['name'];
                                        $_FILES['file']['type']       = $_FILES['img1']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img1']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img1']['error'];
                                        $_FILES['file']['size']       = $_FILES['img1']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img1"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/aapni-khabar-antar-images/';
                                        $config['file_name']          = $data['title'].'-1-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img1'] = 'aapni-khabar-antar-images/'.$preImageData['file_name'];
                                        }
                        }
                         if(!empty($_FILES['img2']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img2']['name'];
                                        $_FILES['file']['type']       = $_FILES['img2']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img2']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img2']['error'];
                                        $_FILES['file']['size']       = $_FILES['img2']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img2"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/aapni-khabar-antar-images/';
                                        $config['file_name']          = $data['title'].'-2-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img2'] = 'aapni-khabar-antar-images/'.$preImageData['file_name'];
                                        }
                        }
                          if(!empty($_FILES['img3']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img3']['name'];
                                        $_FILES['file']['type']       = $_FILES['img3']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img3']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img3']['error'];
                                        $_FILES['file']['size']       = $_FILES['img3']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img3"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/aapni-khabar-antar-images/';
                                        $config['file_name']          = $data['title'].'-3-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img3'] = 'aapni-khabar-antar-images/'.$preImageData['file_name'];
                                        }
                        }


                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_AAPNI_KHABAR_ANTAR, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {   
                        $insData['user_id']            = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['first_name']            = !empty($data['first_name'])?$data['first_name']:'';
                        $insData['last_name']              = !empty($data['last_name'])?$data['last_name']:'';
                        $insData['city']            = !empty($data['city'])?$data['city']:'';
                        $insData['country']               = !empty($data['country'])?$data['country']:'';
                        $insData['title']              = !empty($data['title'])?$data['title']:'';
                        $insData['details']            = !empty($data['details'])?$data['details']:'';
                        $insData['news_date']        = !empty($data['news_date'])?$data['news_date']:'';
                        $insData['share_link']            = !empty($data['share_link'])?$data['share_link']:'';
                        $insData['date']             = date('Y-m-d');


                                                if(!empty($_FILES['file_upload']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['file_upload']['name'];
                                        $_FILES['file']['type']       = $_FILES['file_upload']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['file_upload']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['file_upload']['error'];
                                        $_FILES['file']['size']       = $_FILES['file_upload']['size'];
                                        $priNewImg          = explode('.', $_FILES["file_upload"]['name']);
                                        $priExtension       = end($priNewImg);
                                        $uploadPath         = '/aapni-khabar-antar-upload-files/';
                                        $config['file_name']        = $data['title'].'-'.time().'.'.$priExtension;
                                        $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif|docx|csv|pdf';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['file_upload'] = 'aapni-khabar-antar-upload-files/'.$preImageData['file_name'];
                                        }
                        }
                        if(!empty($_FILES['img1']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img1']['name'];
                                        $_FILES['file']['type']       = $_FILES['img1']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img1']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img1']['error'];
                                        $_FILES['file']['size']       = $_FILES['img1']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img1"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/aapni-khabar-antar-images/';
                                        $config['file_name']          = $data['title'].'-1-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img1'] = 'aapni-khabar-antar-images/'.$preImageData['file_name'];
                                        }
                        }
                         if(!empty($_FILES['img2']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img2']['name'];
                                        $_FILES['file']['type']       = $_FILES['img2']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img2']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img2']['error'];
                                        $_FILES['file']['size']       = $_FILES['img2']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img2"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/aapni-khabar-antar-images/';
                                        $config['file_name']          = $data['title'].'-2-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img2'] = 'aapni-khabar-antar-images/'.$preImageData['file_name'];
                                        }
                        }
                          if(!empty($_FILES['img3']['name']))
                        {
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img3']['name'];
                                        $_FILES['file']['type']       = $_FILES['img3']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img3']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img3']['error'];
                                        $_FILES['file']['size']       = $_FILES['img3']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img3"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/aapni-khabar-antar-images/';
                                        $config['file_name']          = $data['title'].'-3-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img3'] = 'aapni-khabar-antar-images/'.$preImageData['file_name'];
                                        }
                        }
                        $inserted_data = $this->general_model->insert(TBL_AAPNI_KHABAR_ANTAR, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }
     public function add_edit_student_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {

                    if($data['is_update']==1 and !empty($data['student_post_id']))
                    {
                        $id                           = $data['student_post_id'];
                        $insData['user_id']           = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['title']             = !empty($data['title'])?$data['title']:'';
                        $insData['city']           = !empty($data['city'])?$data['city']:'';
                        $insData['country']           = !empty($data['country'])?$data['country']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_STUDENT_POST, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {
                       
                        $insData['user_id']           = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['title']             = !empty($data['title'])?$data['title']:'';
                        $insData['city']           = !empty($data['city'])?$data['city']:'';
                        $insData['country']           = !empty($data['country'])?$data['country']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']       = date('Y-m-d');
                        
                        $inserted_data = $this->general_model->insert(TBL_STUDENT_POST, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }

            }
        
        $this->response($response, 200);
    }
    public function student_post_list_post()
    {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "student_post.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND student_post.date between '$from_date' and  '$to_date'";
         }

          if(!empty($data['id']))
        {

             $wherestring .= " AND student_post.id=".$data['id']."";
        }
        
        $orderby        = 'student_post.id';
        $sortby         = 'DESC';


        $fields         = ['student_post.*','registration.first_name','registration.last_name'];
        $params = array(
                'table'         => TBL_STUDENT_POST.' as student_post',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'        => $sortby,
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_REGISTRATION.' as registration' => 'registration.id = student_post.user_id',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        $cntParams = array(
                'table'         => TBL_STUDENT_POST.' as student_post',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "totalrow"      => '1',
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_REGISTRATION.' as registration' => 'registration.id = student_post.user_id',
                                ),

                 );
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
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    public function student_post_report_spam_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_STUDENT_POST_REPORT_SPAM,
                    'where'         => array('user_id' => $data['user_id'],'post_id' => $data['id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                  $insData['user_id']        = !empty($data['user_id'])?$data['user_id']:'';
                  $insData['post_id']        = !empty($data['id'])?$data['id']:'';
                  $insData['datetime']       = date('Y-m-d H:i:s'); 
                  $inserted_data = $this->general_model->insert(TBL_STUDENT_POST_REPORT_SPAM, $insData);
                  $response['code']    = REST_Controller::HTTP_OK;
                  $response['message'] = $this->lang->line('success');

                $where = array('id' => $data['id']);
                $this->General_model->increase_field_value(report_spam,TBL_STUDENT_POST,$where);
            }
            else 
            {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = 'You already  Reported it';
            }
        $this->response($response, 200);
    }
      
    public function matrimonial_membership_plan_list_get()
    {

        $data = $this->get(); 
        $wherestring    = "matrimonial_membership_plan.status=1";

        $params = array(
                'table'         => TBL_MATRIMONIAL_MEMBERSHIP_PLAN.' as matrimonial_membership_plan',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);



        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
    public function current_residency_status_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_current_residency_status.status=1";

        $params = array(
                'table'         => TBL_CURRENT_RESIDENCY_STATUS.' as m_current_residency_status',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);



        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }

    public function residency_type_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_residency_type.status=1";

        $params = array(
                'table'         => TBL_RESIDENCY_TYPE.' as m_residency_type',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);



        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
    public function residence_owenership_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_residence_owenership.status=1";

        $params = array(
                'table'         => TBL_RELOCATION_OWENERSHIP.' as m_residence_owenership',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);



        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }

     public function relocation_plan_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_relocation_plan.status=1";

        $params = array(
                'table'         => TBL_RELOCATION_PLAN.' as m_relocation_plan',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    } 
    public function national_origin_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_national_origin.status=1";

        $params = array(
                'table'         => TBL_NATIONAL_ORGIN.' as m_national_origin',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
      public function marital_status_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_marital_status.status=1";

        $params = array(
                'table'         => TBL_MARITAL_STATUS.' as m_marital_status',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
     public function profile_created_by_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_profile_created_by.status=1";

        $params = array(
                'table'         => TBL_PROFILE_CREATED_BY.' as m_profile_created_by',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
     public function bhajan_favourite_post() 
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
            $params = array(
                'table'         => TBL_BHAJAN_FAVOURITE,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'bhajan_id'  => $data['bhajan_id'],
                                    ),
                'compare_type'  => '=',
            );
            $checkProductFavourite = $this->General_model->get_query_data($params);
            if(empty($checkProductFavourite))
            {
                $insData = array (
                    "user_id"        => !empty($data['user_id'])?strtolower($data['user_id']):'',
                    "bhajan_id"     => !empty($data['bhajan_id'])?$data['bhajan_id']:'',
                    "created_date"   => date('Y-m-d H:i:s'),
                );
                //prd($data);
                $inserted_data = $this->general_model->insert(TBL_BHAJAN_FAVOURITE, $insData);
                if (isset($inserted_data))
                {
                    $response['message']    = 'bhajan favourite successful';
                    $response['code']       = REST_Controller::HTTP_OK;
                }
                else
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('warning');
                }
            }else{
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'bhajan already favourite';
                
            }
        }
        $this->response($response, 200);
    }
     public function remove_bhajan_favourite_post() 
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
            $params = array(
                'table'         => TBL_BHAJAN_FAVOURITE,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'bhajan_id'  => $data['bhajan_id'],
                                    ),
                'compare_type'  => '=',
            );
            $checkProductFavourite = $this->General_model->get_query_data($params);
            if(!empty($checkProductFavourite))
            {
                $this->general_model->delete(TBL_BHAJAN_FAVOURITE,array('id' => $checkProductFavourite[0]['id']));
                
                $response['message']    = 'bhajan favourite removed';
                $response['code']       = REST_Controller::HTTP_OK;
                
            }else{
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
                
            }
        }
        $this->response($response, 200);
    }

    public function apani_khabar_anter_add_report_spam_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_AAPNI_KHABAR_ANTAR_REPORT_SPAM,
                    'where'         => array('user_id' => $data['user_id'],'post_id' => $data['id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                  $insData['user_id']        = !empty($data['user_id'])?$data['user_id']:'';
                  $insData['post_id']        = !empty($data['id'])?$data['id']:'';
                  $insData['datetime']       = date('Y-m-d H:i:s'); 
                  $inserted_data = $this->general_model->insert(TBL_AAPNI_KHABAR_ANTAR_REPORT_SPAM, $insData);
                  $response['code']    = REST_Controller::HTTP_OK;
                  $response['message'] = $this->lang->line('success');

                $where = array('id' => $data['id']);
                $this->General_model->increase_field_value(report_spam,TBL_AAPNI_KHABAR_ANTAR,$where);
            }
            else 
            {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = 'You already  Reported it';
            }
        $this->response($response, 200);
    }
    public function prayer_request_category_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "m_prayer_request_category.status=1";

        $params = array(
                'table'         => TBL_PRAYER_REQUEST_CATEGORY.' as m_prayer_request_category',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "offset"        => $per_page,
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
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
        public function holi_bible_list_post()
    {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "holy_bible.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND holy_bible.date between '$from_date' and  '$to_date'";
         }

         if(!empty($data['id']))
        {

             $wherestring .= " AND holy_bible.id=".$data['id']."";
        }
        if(!empty($data['chapter_id']))
        {

             $wherestring .= " AND holy_bible.chapter_id=".$data['chapter_id']."";
        }
             $wherestring .= " ORDER BY holy_bible.date ASC";

        $fields         = ['holy_bible.*','m_book.name AS book_name','m_chapter.name AS chapter_name','m_language.name As language_name'];
        $params = array(
                'table'         => TBL_HOLY_BIBLE.' as holy_bible',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_CHAPTER.' as m_chapter' => 'm_chapter.id = holy_bible.chapter_id',
                                    TB_LANGUAGE.' as m_language' => 'm_language.id = holy_bible.language_id',
                                    TBL_BOOK.' as m_book' => 'm_book.id = holy_bible.book_id',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        for($i=0; $i<count($aabhar_list); $i++)
        {
                 $aabhar_list[$i]['is_favourite']    =  '';
                 $params = array(
                    'table'         => TBL_HOLY_BIBLE_FAVOURITE,
                    'where'         => array(
                                                    'user_id'       => $data['user_id'],
                                                    'holi_bible_id'    => $aabhar_list[$i]['id'],
                                                ),
                            'compare_type'  => '=',
                        );
                        $checkProductFavourite = $this->General_model->get_query_data($params);
                        if(!empty($checkProductFavourite)){
                            $aabhar_list[$i]['is_favourite']    =  '1';
                        }
        }


        $cntParams = array(
                'table'         => TBL_HOLY_BIBLE.' as holy_bible',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
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
        
        $this->response($response, 200);
    }
     public function add_edit_holi_bible_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['holi_bible_id']))
                    {
                        $id                           = $data['holi_bible_id'];
                        $insData['user_id']           = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_book']             = !empty($data['name_of_book'])?$data['name_of_book']:'';
                        $insData['chapter_id']           = !empty($data['chapter_id'])?$data['chapter_id']:'';
                        $insData['language_id']           = !empty($data['language_id'])?$data['language_id']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']              = !empty($data['date'])?$data['date']:'';
                        $insData['book_id']             = !empty($data['book_id'])?$data['book_id']:'';

                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_HOLY_BIBLE, $insData, $where);

                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {                       
                       
                        $insData['user_id']           = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['name_of_book']             = !empty($data['name_of_book'])?$data['name_of_book']:'';
                        $insData['chapter_id']           = !empty($data['chapter_id'])?$data['chapter_id']:'';
                        $insData['language_id']           = !empty($data['language_id'])?$data['language_id']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']              = !empty($data['date'])?$data['date']:'';
                        $insData['create_date']       = date('Y-m-d');
                        $insData['book_id']             = !empty($data['book_id'])?$data['book_id']:'';

                        $inserted_data = $this->general_model->insert(TBL_HOLY_BIBLE, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }
      public function chapter_list_get()
    {
        $data = $this->get();
        $where         = array('m_chapter.status' => 1);
        if($data['book_id'])
        {
             $wherestring    = "m_chapter.book_id=".$data['book_id']."";
        }
        $fields         = ['m_chapter.*','m_book.name AS book_name'];
        $params = array(
                'table'         => TBL_CHAPTER.' as m_chapter',
                'fields'        => $fields,
                'where'         => $where,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_BOOK.' as m_book' => 'm_book.id = m_chapter.book_id',
                                ),
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    public function book_type_list_get()
    {
         $data = $this->get();
        if(!empty($data['language_id']))
        {
             $wherestring    = "language_id=".$data['language_id']."";
        }
        $where         = array('status' => 1);
        $params = array(
                'table'         => TBL_BOOK_TYPE,
                'where'         => $where,
                 'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {

            for($i=0; $i<count($stateStatus); $i++)
            {
                $book_type_id=$stateStatus[$i]['id'];
                $wherestring    = "book_type_id=".$book_type_id."";
                $book_id=$stateStatus[$i]['id'];
                $params = array(
                'table'         => TBL_BOOK,
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',);
                 $stateStatus[$i]['book_list'] = $this->General_model->get_query_data($params);

                for($ii=0; $ii<count($stateStatus[$i]['book_list']); $ii++)
                {
                    $book_id=$stateStatus[$i]['book_list'][$ii]['id'];
                    $wherestring    = "m_chapter.book_id=".$book_id."";

                    $orderby        = 'name';
                    $sortby         = 'ASC';
                    $params = array(
                    'table'         => TBL_CHAPTER.' as m_chapter',
                                    'where'         => $where,
                                    'wherestring'   => !empty($wherestring)?$wherestring:'',
                                    'compare_type'  => '=',
                                    "orderby"       => $orderby,
                                    "sort"          => $sortby,
                                    
                                    
                    );
                    $stateStatus[$i]['book_list'][$ii]['chapter_list'] = $this->General_model->get_query_data($params);

                }


            }
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }

     public function book_list_get()
    {
        $where         = array('status' => 1);
        $params = array(
                'table'         => TBL_BOOK,
                'where'         => $where,
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
         public function chapterwise_book_list_get()
    {
        $where         = array('status' => 1);
        $params = array(
                'table'         => TBL_BOOK,
                'where'         => $where,
                'compare_type'  => '=',
            );
        $stateStatus = $this->General_model->get_query_data($params);
        if (!empty($stateStatus))
        {

            for($i=0; $i<count($stateStatus); $i++)
            {
                $book_id=$stateStatus[$i]['id'];

                $wherestring    = "m_chapter.book_id=".$book_id."";

                $fields         = ['m_chapter.*'];
                $params = array(
                'table'         => TBL_CHAPTER.' as m_chapter',
                'fields'        => $fields,
                'where'         => $where,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',);
                $stateStatus[$i]['chapter_list'] = $this->General_model->get_query_data($params);

            }

            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $stateStatus;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
     public function holi_bible_favourite_post() 
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
            $params = array(
                'table'         => TBL_HOLY_BIBLE_FAVOURITE,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'holi_bible_id'  => $data['holi_bible_id'],
                                    ),
                'compare_type'  => '=',
            );
            $checkProductFavourite = $this->General_model->get_query_data($params);
            if(empty($checkProductFavourite))
            {
                $insData = array (
                    "user_id"        => !empty($data['user_id'])?strtolower($data['user_id']):'',
                    "holi_bible_id"  => !empty($data['holi_bible_id'])?$data['holi_bible_id']:'',
                    "created_date"   => date('Y-m-d H:i:s'),
                );
                //prd($data);
                $inserted_data = $this->general_model->insert(TBL_HOLY_BIBLE_FAVOURITE, $insData);
                if (isset($inserted_data))
                {
                    $response['message']    = 'holi Bible favourite successful';
                    $response['code']       = REST_Controller::HTTP_OK;
                }
                else
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('warning');
                }
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'holi Bible already favourite';
            }
        }
        $this->response($response, 200);
    }
    public function remove_holi_bible_favourite_post() 
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
            $params = array(
                'table'         => TBL_HOLY_BIBLE_FAVOURITE,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'holi_bible_id'  => $data['holi_bible_id'],
                                    ),
                'compare_type'  => '=',
            );
            $checkProductFavourite = $this->General_model->get_query_data($params);
            if(!empty($checkProductFavourite))
            {
                $this->general_model->delete(TBL_HOLY_BIBLE_FAVOURITE,array('id' => $checkProductFavourite[0]['id']));
                
                $response['message']    = 'holi bible favourite removed';
                $response['code']       = REST_Controller::HTTP_OK;
                
            }else{
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
                
            }
        }
        $this->response($response, 200);
    }
     public function delete_my_obituary_post() 
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
            
            if($data['type_id']==1)
            {

                $params = array(
                'table'         => TBL_DEATH_NOTIFICATION,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'id'  => $data['id'],
                                    ),
                'compare_type'  => '=',
                );
                $checkProductFavourite = $this->General_model->get_query_data($params);

                if(!empty($checkProductFavourite))
                {
                    $this->general_model->delete(TBL_DEATH_NOTIFICATION,array('id' => $checkProductFavourite[0]['id']));
                
                    $response['message']    = 'your obituary deleted';
                    $response['code']       = REST_Controller::HTTP_OK;
                
                }
                else
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('no_record_found');
                
                }

            }
             if($data['type_id']==2)
            {

                $params = array(
                'table'         => TBL_AABHAR_PATRA,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'id'  => $data['id'],
                                    ),
                'compare_type'  => '=',
                );
                $checkProductFavourite = $this->General_model->get_query_data($params);

                if(!empty($checkProductFavourite))
                {
                    $this->general_model->delete(TBL_AABHAR_PATRA,array('id' => $checkProductFavourite[0]['id']));
                
                    $response['message']    = 'your obituary deleted';
                    $response['code']       = REST_Controller::HTTP_OK;
                
                }
                else
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('no_record_found');
                
                }

            }
             if($data['type_id']==3)
            {

                $params = array(
                'table'         => TB_BIOGRAPHY,
                'where'         => array(
                                        'user_id'  => $data['user_id'],
                                        'id'  => $data['id'],
                                    ),
                'compare_type'  => '=',
                );
                $checkProductFavourite = $this->General_model->get_query_data($params);

                if(!empty($checkProductFavourite))
                {
                    $this->general_model->delete(TB_BIOGRAPHY,array('id' => $checkProductFavourite[0]['id']));
                
                    $response['message']    = 'your obituary deleted';
                    $response['code']       = REST_Controller::HTTP_OK;
                
                }
                else
                {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = $this->lang->line('no_record_found');
                
                }

            }



        }
        $this->response($response, 200);
    }
    public function add_edit_advertisment_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {

                    if($data['is_update']==1 and !empty($data['advertisment_id']))
                    {
                        $id                           = $data['advertisment_id'];
                        $insData['user_id']           = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['type_id']           = !empty($data['type_id'])?$data['type_id']:'';
                        $insData['title']             = !empty($data['title'])?$data['title']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']              = !empty($data['date'])?$data['date']:'';
if(!empty($_FILES['img1']['name']))
{
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img1']['name'];
                                        $_FILES['file']['type']       = $_FILES['img1']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img1']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img1']['error'];
                                        $_FILES['file']['size']       = $_FILES['img1']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img1"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/advertisement-images/';
                                        $config['file_name']          = $data['title'].'-1-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img1'] = 'advertisement-images/'.$preImageData['file_name'];
                                        }
}
if(!empty($_FILES['img2']['name']))
{
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img2']['name'];
                                        $_FILES['file']['type']       = $_FILES['img2']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img2']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img2']['error'];
                                        $_FILES['file']['size']       = $_FILES['img2']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img2"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/advertisement-images/';
                                        $config['file_name']          = $data['title'].'-2-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img2'] = 'advertisement-images/'.$preImageData['file_name'];
                                        }
}
 if(!empty($_FILES['img3']['name']))
{
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img3']['name'];
                                        $_FILES['file']['type']       = $_FILES['img3']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img3']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img3']['error'];
                                        $_FILES['file']['size']       = $_FILES['img3']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img3"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/advertisement-images/';
                                        $config['file_name']          = $data['title'].'-3-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img3'] = 'advertisement-images/'.$preImageData['file_name'];
                                        }
}




                        $where = array('id' => $id); 
                        $this->General_model->update(TB_ADVERTISEMENT, $insData, $where);

                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {
                       
                        $insData['user_id']           = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['type_id']             = !empty($data['type_id'])?$data['type_id']:'';
                        $insData['title']             = !empty($data['title'])?$data['title']:'';
                        $insData['details']           = !empty($data['details'])?$data['details']:'';
                        $insData['date']              = !empty($data['date'])?$data['date']:'';
                        $insData['datetime']       = date('Y-m-d H:i:s');
if(!empty($_FILES['img1']['name']))
{
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img1']['name'];
                                        $_FILES['file']['type']       = $_FILES['img1']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img1']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img1']['error'];
                                        $_FILES['file']['size']       = $_FILES['img1']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img1"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/advertisement-images/';
                                        $config['file_name']          = $data['title'].'-1-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img1'] = 'advertisement-images/'.$preImageData['file_name'];
                                        }
}
if(!empty($_FILES['img2']['name']))
{
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img2']['name'];
                                        $_FILES['file']['type']       = $_FILES['img2']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img2']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img2']['error'];
                                        $_FILES['file']['size']       = $_FILES['img2']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img2"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/advertisement-images/';
                                        $config['file_name']          = $data['title'].'-2-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img2'] = 'advertisement-images/'.$preImageData['file_name'];
                                        }
}
 if(!empty($_FILES['img3']['name']))
{
                                        $this->load->library('upload');
                                        $_FILES['file']['name']       = $_FILES['img3']['name'];
                                        $_FILES['file']['type']       = $_FILES['img3']['type'];
                                        $_FILES['file']['tmp_name']   = $_FILES['img3']['tmp_name'];
                                        $_FILES['file']['error']      = $_FILES['img3']['error'];
                                        $_FILES['file']['size']       = $_FILES['img3']['size'];
                                        $priNewImg                    = explode('.', $_FILES["img3"]['name']);
                                        $priExtension                 = end($priNewImg);
                                        $uploadPath                   = '/advertisement-images/';
                                        $config['file_name']          = $data['title'].'-3-'.time().'.'.$priExtension;
                                        $config['upload_path']        = $this->config->item('org_base_path').$uploadPath;
                                        $config['allowed_types']    = 'jpg|jpeg|png|gif';
                                        $this->upload->initialize($config);
                                        if($this->upload->do_upload('file'))
                                        {
                                            $preImageData = $this->upload->data();  
                                            $insData['img3'] = 'advertisement-images/'.$preImageData['file_name'];
                                        }
}
                        
                        $advertisment_id = $this->general_model->insert(TB_ADVERTISEMENT, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                        $response['data']           = [
                                                         
                                                    'advertisment_id'=>$advertisment_id,
                                             ];

                    }


            }
        
        $this->response($response, 200);
    }

        public function advertisement_plan_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "advertisement_plan.status=1";

        $params = array(
                'table'         => TBL_ADVERTISMENT_PLAN.' as advertisement_plan',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);



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
    public function create_advertisement_order_id_post()
    {
        $data = $this->post();
 
        $iData['registration_id']          =$data['user_id'];
        $iData['package_id']    =$data['package_id'];
        $iData['advertisement_id']    =$data['advertisement_id'];
        $iData['package_days']    =$data['package_days'];
        $wallet_id=$this->General_model->insert(TBL_ADVERTISMENT_PACKAGE_TXN, $iData);
        if(isset($wallet_id))
        {
                $response['message']        = $this->lang->line('success');
                $response['code']           = REST_Controller::HTTP_OK;
                $response['data']           = [
                                                         
                                                    'advertisement_package_txn_id'=>$wallet_id,
                                             ];
        }
        else
        {
                        $response['message']        = $this->lang->line('warning');
                        $response['code']           = REST_Controller::HTTP_BAD_REQUEST;
        }

        $this->response($response, 200);
    }

    public function success_advertisement_payment_post() 
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
            $cdata['registration_id']   = !empty($data['user_id'])?trim($data['user_id']):'';
            $cdata['amount']          = !empty($data['amount'])?trim($data['amount']):'';
            $cdata['date']            = date('Y-m-d');
            $cdata['auto_datee']             = date('Y-m-d H:i:s');
            $cdata['payment_gateway_history']           = !empty($data['payment_gateway_history'])?trim($data['payment_gateway_history']):'';
             $cdata['transaction_id']             = !empty($data['transaction_id'])?trim($data['transaction_id']):'';
            $cdata['order_currency_name']             = !empty($data['order_currency_name'])?trim($data['order_currency_name']):'';
            $cdata['order_currency_name_symbol']             = !empty($data['order_currency_name_symbol'])?trim($data['order_currency_name_symbol']):'';
            $cdata['current_currency_in_rs']             = !empty($data['current_currency_in_rs'])?trim($data['current_currency_in_rs']):'';
            $cdata['currency_id']             = !empty($data['currency_id'])?trim($data['currency_id']):'';

             $cdata['success_status']=1;

            $where = array('id' => $data['advertisement_package_txn_id']);

            if(!empty($data['advertisement_package_txn_id']))
            {

                    $join_tables = array(
                                        TBL_ADVERTISMENT_PLAN.' as advertisement_plan' => 'advertisement_plan.id = advertisement_package_txn.package_id',
                                    );
                    $params = array(
                    'table'         => TBL_ADVERTISMENT_PACKAGE_TXN.' as advertisement_package_txn',
                    'fields'        => ['advertisement_package_txn.advertisement_id as advertisement_id','advertisement_plan.name','advertisement_plan.days'],
                    'where'         => array('advertisement_package_txn.id' => $data['advertisement_package_txn_id']),
                    'join_type'     => 'left',
                    'join_tables'   => $join_tables,
                    'compare_type'  => '=',
                    );
                    $chkrecord = $this->General_model->get_query_data($params);
                    if(!empty($chkrecord))
                    {
                        $advertisement_id =$chkrecord[0]['advertisement_id'];
                        $days=$chkrecord[0]['days'];
                        $date=strtotime("+".$days." day");
                        $expire_date=date('Y-m-d', $date);
                        $cdata['expire_date']=$expire_date;
                        $cdata['package_name']=$chkrecord[0]['name'];
                        $this->General_model->update(TBL_ADVERTISMENT_PACKAGE_TXN, $cdata, $where);

                        $udata['status']     = 1;
                         $udata['expire_date']     = $expire_date;
                        $where = array('id' => $advertisement_id);
                        $this->General_model->update(TB_ADVERTISEMENT, $udata, $where);

                    }
                        
            }

            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
        }
        $this->response($response, 200);
    }
     public function advertisement_list_post()
     {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "advertisement.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND advertisement.date between '$from_date' and  '$to_date'";
         }

          if(!empty($data['id']))
        {

             $wherestring .= " AND advertisement.id=".$data['id']."";
        }

        $today=date('Y-m-d');
        $wherestring .= " AND advertisement.expire_date>=".$today."";

         $orderby        = 'advertisement.id DESC';


        $fields         = ['advertisement.*','advertisement_type.name AS advertisement_type'];
        $params = array(
                'table'         => TB_ADVERTISEMENT.' as advertisement',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                 "orderby"       => $orderby,
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TB_ADVERTISEMENT_TYPE.' as advertisement_type' => 'advertisement_type.id = advertisement.type_id',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

        $cntParams = array(
                'table'         => TB_ADVERTISEMENT.' as advertisement',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                'compare_type'  => '=',
                "totalrow"      => '1' );
        $total_prayer = $this->General_model->get_query_data($cntParams);

        if (!empty($total_prayer)) 
        {
            $total_page = ceil($total_prayer / PRODUCT_PAGINATION_SIZE);
        }

      
        if (!empty($aabhar_list))
        {

                for ($i=0; $i < count($aabhar_list); $i++) 
                {
                    $aabhar_list[$i]['img1']        = check_file_exists($aabhar_list[$i]['img1']);
                    $aabhar_list[$i]['img2']        = check_file_exists($aabhar_list[$i]['img2']);
                    $aabhar_list[$i]['img3']        = check_file_exists($aabhar_list[$i]['img3']);

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
        
        $this->response($response, 200);
    }
    
    public function help_support_list_get()
    {
        $params = array(
                'table'         => TBL_HELP_SUPPORT,
                'compare_type'  => '=',
            );
        $help_support = $this->General_model->get_query_data($params);
        if (!empty($help_support))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $help_support;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }


    public function purpose_of_fund_raising_list_get()
    {
        $params = array(
                'table'         => TBL_PURPOSE_OF_FUND_RAISING,
                'where'         => array('status' => 1),
                'compare_type'  => '=',
            );
        $help_support = $this->General_model->get_query_data($params);
        if (!empty($help_support))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $help_support;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }
         public function add_edit_fund_raising_details_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['fund_raising_id']))
                    {
                        $id                                 = $data['fund_raising_id'];
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['type_id']                 = !empty($data['type_id'])?$data['type_id']:'';
                        
                        $insData['message']                 = !empty($data['message'])?$data['message']:'';
                        $insData['request_date']            = !empty($data['request_date'])?$data['request_date']:'';
                        $insData['create_user_name']        = !empty($data['create_user_name'])?$data['create_user_name']:'';
                        $insData['create_user_city']        = !empty($data['create_user_city'])?$data['create_user_city']:'';

$insData['purpose_of_fund_raising_id']= !empty($data['purpose_of_fund_raising_id'])?$data['purpose_of_fund_raising_id']:'';
$insData['create_user_country']= !empty($data['create_user_country'])?$data['create_user_country']:'';
$insData['goal']= !empty($data['goal'])?$data['goal']:'';
$insData['achieve']= !empty($data['achieve'])?$data['achieve']:'';
$insData['write_your_story']= !empty($data['write_your_story'])?$data['write_your_story']:'';
$insData['payment_details']= !empty($data['payment_details'])?$data['payment_details']:'';
$insData['currency_type']= !empty($data['currency_type'])?$data['currency_type']:'';




if(!empty($_FILES['image']['name']))
{

                // Load and initialize upload library
                $this->load->library('upload');

                $_FILES['file']['name']       = $_FILES['image']['name'];
                $_FILES['file']['type']       = $_FILES['image']['type'];
                $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'];
                $_FILES['file']['error']      = $_FILES['image']['error'];
                $_FILES['file']['size']       = $_FILES['image']['size'];

                $priNewImg          = explode('.', $_FILES["image"]['name']);
                $priExtension       = end($priNewImg);
                $uploadPath         = '/fund-raising-donation-request-photos/';


                $config['file_name']        = $data['amount'].'-'.time().'.'.$priExtension;
                $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                $config['allowed_types']    = 'jpg|jpeg|png|gif';
               
                $this->upload->initialize($config);

                // Upload file to server
                if($this->upload->do_upload('file')){
                    $preImageData = $this->upload->data();   // Uploaded file data
                    $insData['photos'] = 'fund-raising-donation-request-photos/'.$preImageData['file_name'];
                }
}


                        $insData['date']              = !empty($data['date'])?$data['date']:'';
                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_FUND_RAISING_DONATION_REQUEST, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {                       
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['type_id']                 = !empty($data['type_id'])?$data['type_id']:'';
                        $insData['message']                 = !empty($data['message'])?$data['message']:'';
                        $insData['request_date']            = !empty($data['request_date'])?$data['request_date']:'';
                        $insData['create_user_name']        = !empty($data['create_user_name'])?$data['create_user_name']:'';
                        $insData['create_user_city']        = !empty($data['create_user_city'])?$data['create_user_city']:'';

$insData['purpose_of_fund_raising_id']= !empty($data['purpose_of_fund_raising_id'])?$data['purpose_of_fund_raising_id']:'';
$insData['create_user_country']= !empty($data['create_user_country'])?$data['create_user_country']:'';
$insData['goal']= !empty($data['goal'])?$data['goal']:'';
$insData['achieve']= !empty($data['achieve'])?$data['achieve']:'';
$insData['write_your_story']= !empty($data['write_your_story'])?$data['write_your_story']:'';
$insData['payment_details']= !empty($data['payment_details'])?$data['payment_details']:'';
$insData['date']       = date('Y-m-d');
$insData['currency_type']= !empty($data['currency_type'])?$data['currency_type']:'';


if(!empty($_FILES['image']['name'])){

                // Load and initialize upload library
                $this->load->library('upload');

                $_FILES['file']['name']       = $_FILES['image']['name'];
                $_FILES['file']['type']       = $_FILES['image']['type'];
                $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'];
                $_FILES['file']['error']      = $_FILES['image']['error'];
                $_FILES['file']['size']       = $_FILES['image']['size'];

                $priNewImg          = explode('.', $_FILES["image"]['name']);
                $priExtension       = end($priNewImg);
                $uploadPath         = '/fund-raising-donation-request-photos/';


                $config['file_name']        = $data['amount'].'-'.time().'.'.$priExtension;
                $config['upload_path']      = $this->config->item('org_base_path').$uploadPath;
                $config['allowed_types']    = 'jpg|jpeg|png|gif';
               
                $this->upload->initialize($config);

                // Upload file to server
                if($this->upload->do_upload('file')){
                    $preImageData = $this->upload->data();   // Uploaded file data
                    $insData['photos'] = 'fund-raising-donation-request-photos/'.$preImageData['file_name'];
                }
            }



                        $inserted_data = $this->general_model->insert(TBL_FUND_RAISING_DONATION_REQUEST, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }
 public function fund_raising_donation_request_list_post()
     {

        $data = $this->post(); 
        $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $wherestring    = "fund_raising_donation_request.status=1";

         if(!empty($data['from_date']) AND !empty($data['to_date']))
         {
                    $from_date=$data['from_date'];
                    $to_date=$data['to_date'];
                    $wherestring .= " AND fund_raising_donation_request.request_date between '$from_date' and  '$to_date'";
         }

         if(!empty($data['id']))
        {

             $wherestring .= " AND fund_raising_donation_request.id=".$data['id']."";
        }

         //$wherestring .= " AND fund_raising_donation_request.user_id=".$data['user_id']."";

         $orderby        = 'fund_raising_donation_request.id DESC';

        $fields         = ['fund_raising_donation_request.*','m_fund_type.name AS fund_type','m_purpose_of_fund_raising.name AS purpose_of_fund_raising'];
        $params = array(
                'table'         => TBL_FUND_RAISING_DONATION_REQUEST.' as fund_raising_donation_request',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                "orderby"       => $orderby,
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page,
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_FUND_TYPE.' as m_fund_type' => 'm_fund_type.id = fund_raising_donation_request.type_id',
                                    TBL_PURPOSE_OF_FUND_RAISING.' as m_purpose_of_fund_raising' => 'm_purpose_of_fund_raising.id = fund_raising_donation_request.purpose_of_fund_raising_id',
                                ),
            );
        $aabhar_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);

          for ($i=0; $i < count($aabhar_list); $i++) 
        {

                $aabhar_list[$i]['photos']        = check_file_exists($aabhar_list[$i]['photos']);


        }

        $cntParams = array(
                'table'         => TBL_FUND_RAISING_DONATION_REQUEST.' as fund_raising_donation_request',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
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
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
     public function fund_raising_report_spam_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_FUND_RAISING_REPORT_SPAM,
                    'where'         => array('user_id' => $data['user_id'],'post_id' => $data['id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                  $insData['user_id']        = !empty($data['user_id'])?$data['user_id']:'';
                  $insData['post_id']        = !empty($data['id'])?$data['id']:'';
                  $insData['datetime']       = date('Y-m-d H:i:s'); 
                  $inserted_data = $this->general_model->insert(TBL_FUND_RAISING_REPORT_SPAM, $insData);
                  $response['code']    = REST_Controller::HTTP_OK;
                  $response['message'] = $this->lang->line('success');

                $where = array('id' => $data['id']);
                $this->General_model->increase_field_value(report_spam,TBL_FUND_RAISING_DONATION_REQUEST,$where);
            }
            else 
            {
                    $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                    $response['message'] = 'You already  Reported it';
            }
        $this->response($response, 200);
    }
           public function add_edit_prayer_request_msg_post() 
    {   
            $data = $this->post();
            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);

            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                    if($data['is_update']==1 and !empty($data['msg_id']))
                    {
                        $id                                 = $data['msg_id'];
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['prayer_request_id'] = !empty($data['prayer_request_id'])?$data['prayer_request_id']:'';
                        $insData['msg']                 = !empty($data['msg'])?$data['msg']:'';
                        $where = array('id' => $id); 
                        $this->General_model->update(TBL_PRAYER_REQUEST_MSG_txn, $insData, $where);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
                    else
                    {                       
                        $insData['user_id']                 = !empty($data['user_id'])?$data['user_id']:'';
                        $insData['prayer_request_id'] = !empty($data['prayer_request_id'])?$data['prayer_request_id']:'';
                        $insData['msg']                 = !empty($data['msg'])?$data['msg']:'';
                        $insData['date']       = date('Y-m-d');
                        $insData['date_time']       = date('Y-m-d H:i:s');
                        $insData['status']=1;

                        $inserted_data = $this->general_model->insert(TBL_PRAYER_REQUEST_MSG_txn, $insData);
                        $response['code']    = REST_Controller::HTTP_OK;
                        $response['message'] = $this->lang->line('success');
                    }
            }
        
        $this->response($response, 200);
    }
            public function prayer_list_details_post()
    {
        $data = $this->post(); 
        if(!empty($data['id']))
        {

             $wherestring = "prayer_request.id=".$data['id']."";
        }

        $fields         = ['prayer_request.*','m_prayer_request_category.name AS prayer_request_category'];
        $params = array(
                'table'         => TBL_PRAYER_REQUEST.' as prayer_request',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
               
                'compare_type'  => '=',
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_PRAYER_REQUEST_CATEGORY.' as m_prayer_request_category' => 'm_prayer_request_category.id = prayer_request.prayer_request_category_id',
                                ),
            );
        $prayer_list = $this->General_model->get_query_data($params);
        if (!empty($prayer_list))
        {
                $orderby="prayer_request_msg_txn.id";
                $sortby="DESC";


                $wherestring = "prayer_request_msg_txn.prayer_request_id=".$data['id']."";
                $fields         = ['prayer_request_msg_txn.*','CONCAT_WS(" ", first_name, last_name ) AS message_user'];
                $params = array(
                'table'         => TBL_PRAYER_REQUEST_MSG_txn.' as prayer_request_msg_txn',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'orderby'       => $orderby,
                'sort'          => $sortby,
                'compare_type'  => '=',
                'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_REGISTRATION.' as register' => 'register.id = prayer_request_msg_txn.user_id',
                                ),
            );
            $prayer_list[0]['msg_details'] = $this->General_model->get_query_data($params);
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['total_page']     = isset($total_page)?$total_page:'1';   
            $response['data']       = $prayer_list;
        }
        else 
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('warning');
        }
        
        $this->response($response, 200);
    }

    public function delete_prayer_request_msg_post() 
    {   
            $data = $this->post();

            $params = array(
                    'table'         => TBL_REGISTRATION,
                    'where'         => array('id' => $data['user_id']),
                    'compare_type'  => '=',
                );
            $chkUser = $this->General_model->get_query_data($params);
            if (empty($chkUser))
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('mobile_already_existing');
            }
            else 
            {
                  $id=$data['msg_id'];
                  $where = array('id' => $id); 
                  $this->General_model->delete(TBL_PRAYER_REQUEST_MSG_txn, $where);
                  $response['message']    = $this->lang->line('success');
                  $response['code']       = REST_Controller::HTTP_OK;  
                    
            }
        
        $this->response($response, 200);
    }
    
      public function city_list_get()
{
    $data = $this->get(); 
    $wherestring = "status=1";

    $params = array(
        'table'         => TBL_CITY . ' as m_city',
        'wherestring'   => !empty($wherestring) ? $wherestring : '',
        'compare_type'  => '=',
        'orderby'       => 'm_city.name ASC', // Assuming 'city_name' is the column for city names
    );

    $prayer_list = $this->General_model->get_query_data($params);
    //prd($prayer_list);
    if (!empty($prayer_list)) {
        $response['message'] = $this->lang->line('success');
        $response['code']    = REST_Controller::HTTP_OK;
        $response['data']    = $prayer_list;
    } else {
        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
        $response['message'] = $this->lang->line('no_record_found');
    }

    $this->response($response, 200);
}

    public function all_course_list_post()
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

                $wherestring    = "courses_details.status=1";
                if(!empty($data['main_courses_id']))
                {
                        $main_courses_id=$data['main_courses_id'];
                        $wherestring    .= " and courses_details.main_courses_id='$main_courses_id'";
                }
                 if(!empty($data['extra_course_id']))
                {
                        $extra_course_id=$data['extra_course_id'];
                        $wherestring    .= " and courses_details.extra_course_id='$extra_course_id'";
                }

                $wherestring     .= " ORDER BY
                                        CASE
                                            WHEN courses_details.name REGEXP '^[઀-૿]' THEN 0
                                            ELSE 1
                                        END,
                                        CONVERT(courses_details.name USING utf8mb4) ASC";
               
                $fields         = ['courses_details.id,courses_details.name'];
                $params = array(
                'table'         => TBL_COURSES_DETAILS.' as courses_details',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {
                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK;
                            $response['data']       = $porductList;
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
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }

      public function year_list_get()
    {
        $data = $this->get(); 
        $wherestring    = "status=1";

        $params = array(
                'table'         => TBL_YEAR.' as m_year',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);
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
             public function home_banners_get()
    {
            $data = $this->get();

            $wherestring    = "banner.status=1";
            $params = array(
                 'table'         => TBL_BANNER.' as banner',
                 'wherestring'   => !empty($wherestring)?$wherestring:'',

            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {
                        for ($i=0; $i < count($porductList); $i++) 
                        {
                                $porductList[$i]['img']     = check_file_exists($porductList[$i]['img']);
                        }    

                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK;
                            $response['data']       = $porductList;

            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
            
                   
        
       

        
        $this->response($response, 200);
    }
public function custom_notification_list_post()
{
    $data = $this->post();
    $page = !empty($data['page_no']) ? $data['page_no'] - 1 : '0';
    $per_page = $page * PRODUCT_PAGINATION_SIZE;

    $user_id = $data['user_id'];
    $params = array(
        'table' => TBL_REGISTRATION,
        'where' => array('id' => $data['user_id']),
        'compare_type' => '=',
    );
    $chkUser = $this->General_model->get_query_data($params);

    if (!empty($chkUser)) {
        $interesting_city_ids = $chkUser[0]['interesting_city_ids'];
        $course_details_ids = $chkUser[0]['course_details_ids'];

        // $wherestring = "notification.notification_type > 0";

        // if (empty($interesting_city_ids) || empty($course_details_ids)) {
        //     $wherestring = "notification.id = 0";
        // } else {
        //     // Prepare dynamic conditions
        //     $conditions = [];

        //     // Check and handle $interesting_city_ids
        //     if (!empty($interesting_city_ids)) {
        //         $city_ids_array = explode(',', $interesting_city_ids);
        //         foreach ($city_ids_array as $city_id) {
        //             $conditions[] = "FIND_IN_SET('$city_id', notification.city_ids) > 0";
        //         }
        //     }

        //     // Check and handle $course_details_ids
        //     if (!empty($course_details_ids)) {
        //         $course_ids_array = explode(',', $course_details_ids);
        //         foreach ($course_ids_array as $course_id) {
        //             $conditions[] = "FIND_IN_SET('$course_id', notification.course_ids) > 0";
        //         }
        //     }

        //     // Combine conditions into a single WHERE clause
        //     if (!empty($conditions)) {
        //         $wherestring .= " AND (" . implode(' OR ', $conditions) . ")";
        //     }
        // }

        $wherestring = "(notification.notification_type = 3";
        if (!empty($interesting_city_ids) && !empty($course_details_ids)) {
            $conditions = [];
        
            // City filters
            $city_ids_array = explode(',', $interesting_city_ids);
            foreach ($city_ids_array as $city_id) {
                $conditions[] = "FIND_IN_SET('$city_id', notification.city_ids) > 0";
            }
        
            // Course filters
            $course_ids_array = explode(',', $course_details_ids);
            foreach ($course_ids_array as $course_id) {
                $conditions[] = "FIND_IN_SET('$course_id', notification.course_ids) > 0";
            }
        
            if (!empty($conditions)) {
                $wherestring .= " OR (notification.notification_type IN (1, 2) AND (" . implode(' OR ', $conditions) . "))";
            }
        }
        $wherestring .= ")";


        $orderby = 'notification.id';
        $sortby = 'DESC';

        // Fetch notifications with pagination
        $params = array(
            'table' => TBL_NOTIFICATION . ' as notification',
            'wherestring' => $wherestring,
            'orderby' => $orderby,
            'sort' => $sortby,
            'num' => PRODUCT_PAGINATION_SIZE,
            'offset' => $per_page,
            'compare_type' => '=',
        );
        $blogList = $this->General_model->get_query_data($params);

        // Count total notifications
        $cntParams = array(
            'table' => TBL_NOTIFICATION . ' as notification',
            'wherestring' => $wherestring,
            'orderby' => $orderby,
            'sort' => $sortby,
            'totalrow' => '1',
        );
        $totalReview = $this->General_model->get_query_data($cntParams);

        if (!empty($totalReview)) {
            $total_page = ceil($totalReview / PRODUCT_PAGINATION_SIZE);
        }

        if (!empty($blogList)) {
            for ($i=0; $i < count($blogList); $i++) {
         
                $blogList[$i]['img'] = check_file_exists($blogList[$i]['img']);
                 $blogList[$i]['is_read']    =  '';
                if(!empty($data['user_id']))
                {
                    $params = array(
                        'table'         => TBL_VIEW_NOTIFICATION_TXN,
                        'where'         => array(
                                                'user_id'       => $data['user_id'],
                                                'notification_id'    => $blogList[$i]['id'],
                                            ),
                        'compare_type'  => '=',
                    );
                    $checkProductFavourite = $this->General_model->get_query_data($params);
                    
                    if(!empty($checkProductFavourite)){
                        $blogList[$i]['is_read']    =  '1';
                    }
                }
            }
            
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['total_page'] = isset($total_page) ? $total_page : '1';
            $response['data'] = $blogList;
        } else {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
    } else {
        $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
        $response['message'] = $this->lang->line('invalid_user');
    }

    $this->response($response, 200);
}
public function custom_notification_details_post()
{
    $data = $this->post();
    $user_id = $data['user_id'];
    $params = array(
        'table' => TBL_REGISTRATION,
        'where' => array('id' => $data['user_id']),
        'compare_type' => '=',
    );
    $chkUser = $this->General_model->get_query_data($params);
    if (!empty($chkUser) and !empty($data['id'])) {
        
        $id=$data['id'];
        $wherestring = "notification.notification_type > 0 and id='$id' ";
        $orderby = 'notification.id';
        $sortby = 'DESC';

        // Fetch notifications with pagination
        $params = array(
            'table' => TBL_NOTIFICATION . ' as notification',
            'wherestring' => $wherestring,
            'compare_type' => '=',
        );
        $blogList = $this->General_model->get_query_data($params);


        if (!empty($blogList)) 
        {
                            $iData['user_id']             = $user_id;
                            $iData['notification_id']             = $id;
                            $iData['datetime']             =date('Y-m-d H:i:s');

                            $inserted_data = $this->General_model->insert(TBL_VIEW_NOTIFICATION_TXN, $iData);
            
            
            foreach ($blogList as &$blog) 
            {
                $blog['img'] = check_file_exists($blog['img']);
            }
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['data'] = $blogList;
        } 
        else 
        {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
    } else {
        $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
        $response['message'] = $this->lang->line('invalid_user');
    }

    $this->response($response, 200);
}




}
