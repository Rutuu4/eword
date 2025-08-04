<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Admission_control extends REST_Controller {

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
     

  public function course_list_post()
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

                $wherestring    = "id=4";
                $params = array(
                        'table'         => TBL_MAIN_VIDEO_LIST.' as main_manu_video_list',

                        'wherestring'   => !empty($wherestring)?$wherestring:'',
                        'compare_type'  => '=',
                    );
                $main_manu_video = $this->General_model->get_query_data($params);
                //prd($main_manu_video);


                $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
                $per_page   = $page * PRODUCT_PAGINATION_SIZE;
           

                $wherestring    = "admission_process.status=1 ";
                if(!empty($data['main_courses_id']))
                {
                    $main_courses_id=$data['main_courses_id'];
                    $wherestring    .= " and m_exrta_course.main_courses_id='$main_courses_id'";
                }


                $wherestring    .= " Group by admission_process.id ";
                $fields = ['admission_process.id,m_exrta_course.name'];
                $params = array(
                    'table'         => TBL_EXRTA_COURSE . ' as m_exrta_course',
                    'fields'        => $fields,
                    'wherestring'   => !empty($wherestring) ? $wherestring : '',
                    "num"           => PRODUCT_PAGINATION_SIZE,
                    "offset"        => $per_page,
                    'join_type'     => 'left',
                    'join_tables'   => array(
                        TBL_ADMISSION_PROCESS . ' as admission_process'  => 'm_exrta_course.name = admission_process.name',
                    ),
                );

                $porductList = $this->General_model->get_query_data($params);
            

            $cntParams = array(
                                'table'         => TBL_EXRTA_COURSE . ' as m_exrta_course',
                                'fields'        => ['COUNT(*) as total'],  // Used for counting rows
                                'wherestring'   => !empty($wherestring) ? $wherestring : '',
                                'compare_type'  => '=',
                                'totalrow'      => '1',  // Indicates a count query
                                'join_type'     => 'left',
                                'join_tables'   => array(
                                    TBL_ADMISSION_PROCESS . ' as admission_process'  => 'm_exrta_course.name = admission_process.name',
                                ),
                            );
            $totalProduct = $this->General_model->get_query_data($cntParams);


             if (!empty($totalProduct)) 
             {
                $total_page = ceil($totalProduct / PRODUCT_PAGINATION_SIZE);
             }  
             


              $response['message']    = $this->lang->line('success');
              $response['code']       = REST_Controller::HTTP_OK;
              $response['total_page']     = isset($total_page)?$total_page:'1';
              $response['main_video_link']       = $main_manu_video[0]['video_link'];
              $response['data']       = $porductList;
              
              
            
                   
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
        
     public function admission_details_post()
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
           
            $courses_id=$data['courses_id'];
            $wherestring    = "admission_process.id=$courses_id";
            $params = array(
                 'table'         => TBL_ADMISSION_PROCESS.' as admission_process',
                 'wherestring'   => !empty($wherestring)?$wherestring:'',

            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {
                    for ($i=0; $i < count($porductList); $i++) 
                    {
                                $porductList[$i]['pdf_file']     = check_file_exists($porductList[$i]['pdf_file']);

                    } 

                    $wherestring    = "admission_process_website_link_txn.admission_process_id=$courses_id";
                    $params = array(
                         'table'         => TBL_ADMISION_PROCESS_WEBSITE_LINK_TXN.' as admission_process_website_link_txn',
                         'wherestring'   => !empty($wherestring)?$wherestring:'',

                    );
                    $porductList[0]['website_link_list'] = $this->General_model->get_query_data($params);  

                      

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
     



}