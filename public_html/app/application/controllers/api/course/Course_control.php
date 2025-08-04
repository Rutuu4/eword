<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Course_control extends REST_Controller {

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

 
     public function main_course_list_post()
    {
        $data = $this->post(); 

        $wherestring    = "id=1";
        $params = array(
                'table'         => TBL_MAIN_VIDEO_LIST.' as main_manu_video_list',

                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
            );
        $main_manu_video = $this->General_model->get_query_data($params);


        $wherestring    = "status=1";
        $fields=['m_main_courses.id,m_main_courses.name,m_main_courses.isExtra,m_main_courses.video_link'];
        $params = array(
                'table'         => TBL_MAIN_COURSES.' as m_main_courses',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
                'orderby'       => 'm_main_courses.display_order ASC',
            );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);
        if (!empty($prayer_list))
        {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['main_video_link']       = $main_manu_video[0]['video_link'];
            $response['data']       = $prayer_list;
        }
        else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
        
        $this->response($response, 200);
    }
    public function extra_course_list_post()
    {
        $data = $this->post(); 

        $main_course_id=$data['main_course_id'];
        $wherestring    = "status=1 and main_courses_id='$main_course_id'";
        $wherestring    .= " order by m_exrta_course.name ASC";
        $params = array(
                'table'         => TBL_EXRTA_COURSE.' as m_exrta_course',
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'compare_type'  => '=',
            );
        $prayer_list = $this->General_model->get_query_data($params);
        if (!empty($prayer_list))
        {

            for ($i=0; $i < count($prayer_list); $i++) 
            {
                $extra_course_id=$prayer_list[$i]['id'];
                $this->db->select('id');
                $this->db->from('courses_details');
                $this->db->where('extra_course_id', $extra_course_id);
                $query = $this->db->get();

                $rowcount = $query->num_rows();

                if ($rowcount == 1) 
                {
                    $row_chk = $query->row_array();
                    $prayer_list[$i]['courses_details_id'] = $row_chk['id'];
                } 
                else 
                {
                     $prayer_list[$i]['courses_details_id'] = '0';
                }

            }



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
        public function extra_sub_course_list_post()
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
           
                $extra_course_id=$data['extra_course_id'];

                $wherestring    = "courses_details.status=1";
                $wherestring    .= " and courses_details.extra_course_id='$extra_course_id'";
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


    // public function course_list_post()
    // {
    //      $data = $this->post();
    //      $user_id=$data['user_id'];
    //      $params = array(
    //                 'table'=>TBL_REGISTRATION, 
    //                 'where'=>array('id' => $data['user_id']),
    //                 'compare_type' => '=',
    //             );
    //     $chkUser = $this->General_model->get_query_data($params);
    //     if (!empty($chkUser))
    //     {       
    //         $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
    //         $per_page   = $page * PRODUCT_PAGINATION_SIZE;


    //                 $main_courses_id=$data['main_courses_id'];

    //                 $wherestring    = "id=$main_courses_id";

    //                 $fields=['m_main_courses.video_link'];
    //                 $params = array(
    //                         'table'         => TBL_MAIN_COURSES.' as m_main_courses',
    //                         'fields'        => $fields,
    //                         'wherestring'   => !empty($wherestring)?$wherestring:'',
    //                         'compare_type'  => '=',
    //                     );
    //                 $main_courses = $this->General_model->get_query_data($params);


    //             $wherestring    = "courses_details.status=1";
    //             $wherestring    .= " and courses_details.main_courses_id='$main_courses_id'";

    //             $fields         = ['courses_details.id,courses_details.name'];
    //             $params = array(
    //             'table'         => TBL_COURSES_DETAILS.' as courses_details',
    //              'fields'        => $fields,
    //             'wherestring'   => !empty($wherestring)?$wherestring:'',
    //              "num"           => PRODUCT_PAGINATION_SIZE,
    //              "offset"        => $per_page
    //         );
    //         $porductList = $this->General_model->get_query_data($params);  
    //          $cntParams = array(
    //                 'table'         => TBL_COURSES_DETAILS.' as courses_details',
    //                 'fields'        => $fields,
    //                 'wherestring'   => !empty($wherestring)?$wherestring:'',
    //                 'compare_type'  => '=',
    //                 "totalrow"      => '1',
    //             );
    //         $totalProduct = $this->General_model->get_query_data($cntParams); 
    //          if (!empty($totalProduct)) {
    //             $total_page = ceil($totalProduct / PRODUCT_PAGINATION_SIZE);
    //         }  

    //         if(!empty($porductList))
    //         {
    //                         $response['message']    = $this->lang->line('success');
    //                         $response['code']       = REST_Controller::HTTP_OK;
    //                         $response['total_page']     = isset($total_page)?$total_page:'1';
    //                         $response['main_video_link']       = $main_courses[0]['video_link'];
    //                         $response['data']       = $porductList;
    //         }
    //         else
    //         {
    //             $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
    //             $response['message'] = $this->lang->line('no_record_found');
    //         }


    //     }
    //     else
    //     {
    //         $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
    //         $response['message'] = $this->lang->line('no_record_found');
    //     }


    //     $this->response($response, 200);
    // }

    public function course_list_post()
    {
        $data = $this->post();
        $user_id = $data['user_id'];
        $params = array(
            'table' => TBL_REGISTRATION,
            'where' => array('id' => $data['user_id']),
            'compare_type' => '=',
        );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser)) {
            $page       = !empty($data['page_no']) ? $data['page_no'] - 1 : '1';
            $per_page   = $page * PRODUCT_PAGINATION_SIZE;


            $main_courses_id = $data['main_courses_id'];

            $wherestring_main_courses = "id=$main_courses_id"; // Separate where string for main courses

            $fields_main_courses = ['m_main_courses.video_link'];
            $params_main_courses = array(
                'table'         => TBL_MAIN_COURSES . ' as m_main_courses',
                'fields'        => $fields_main_courses,
                'wherestring'   => !empty($wherestring_main_courses) ? $wherestring_main_courses : '',
                'compare_type'  => '=',
            );
            $main_courses = $this->General_model->get_query_data($params_main_courses);


            $wherestring_courses_details     = "courses_details.status=1";
            $wherestring_courses_details     .= " and courses_details.main_courses_id='$main_courses_id'";
            $wherestring_courses_details     .= " ORDER BY
                                                CASE
                                                    WHEN courses_details.name REGEXP '^[઀-૿]' THEN 0
                                                    ELSE 1
                                                END,
                                                CONVERT(courses_details.name USING utf8mb4) ASC";

            $fields_courses_details      = ['courses_details.id,courses_details.name'];
            $params_courses_details = array(
                'table'         => TBL_COURSES_DETAILS . ' as courses_details',
                'fields'        => $fields_courses_details,
                'wherestring'   => !empty($wherestring_courses_details) ? $wherestring_courses_details : '',
                "num"           => PRODUCT_PAGINATION_SIZE,
                "offset"        => $per_page
            );
            $porductList = $this->General_model->get_query_data($params_courses_details);
            $cntParams = array(
                'table'         => TBL_COURSES_DETAILS . ' as courses_details',
                'fields'        => $fields_courses_details,
                'wherestring'   => str_replace("ORDER BY CASE WHEN courses_details.name REGEXP '^[઀-૿]' THEN 0 ELSE 1 END, CONVERT(courses_details.name USING utf8mb4) ASC", "", !empty($wherestring_courses_details) ? $wherestring_courses_details : ''), // Remove ORDER BY for count
                'compare_type'  => '=',
                "totalrow"      => '1',
            );
            $totalProduct = $this->General_model->get_query_data($cntParams);
            if (!empty($totalProduct)) {
                $total_page = ceil($totalProduct / PRODUCT_PAGINATION_SIZE);
            }

            if (!empty($porductList)) {
                $response['message']     = $this->lang->line('success');
                $response['code']        = REST_Controller::HTTP_OK;
                $response['total_page']      = isset($total_page) ? $total_page : '1';
                $response['main_video_link']   = $main_courses[0]['video_link'];
                $response['data']        = $porductList;
            } else {
                $response['code']        = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
        } else {
            $response['code']        = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }


        $this->response($response, 200);
    }

     public function course_details_post()
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
            $wherestring    = "courses_details.id=$courses_id";
            $params = array(
                 'table'         => TBL_COURSES_DETAILS.' as courses_details',
                 'wherestring'   => !empty($wherestring)?$wherestring:'',

            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {
                        for ($i=0; $i < count($porductList); $i++) 
                        {
                                $porductList[$i]['pdf_file']     = check_file_exists($porductList[$i]['pdf_file']);

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
            
                   
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
     



}