<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class College_control extends REST_Controller {

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

 
     public function college_university_type_list_post()
    {
        $data = $this->post(); 

        $wherestring    = "status=1";
        $params = array(
                'table'         => TBL_COLLEGE_UNIVERSITY_TYPE.' as m_college_university_type',
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

    public function college_university_list_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
/*         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        { */      
            $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
            $per_page   = $page * PRODUCT_PAGINATION_SIZE;
            

                $college_university_type_id=$data['college_university_type_id'];


                $wherestring    = "college_university_details.status=1";
                $wherestring    .= " and college_university_details.college_university_type_id='$college_university_type_id'";

                if(!empty($data['city_id']))
                {
                    $city_id=$data['city_id'];
                    $wherestring    .= " and college_university_details.city_id='$city_id'";
                }

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

                if(!empty($data['course_id']))
                {
                    $wherestring    .= " AND CONCAT(',', college_university_details.course_ids, ',') like '%,".$data['course_id'].",%'";
                }
                
               



                 $wherestring    .= " GROUP BY college_university_details.id";
                 
                  if($data['college_university_type_id']== 1  or $data['college_university_type_id']== 2)
                {
                    
                    //  $wherestring    .= " order by  college_university_details.name ASC";
                    $wherestring     .= " ORDER BY
                                            CASE
                                                WHEN college_university_details.name REGEXP '^[઀-૿]' THEN 0
                                                ELSE 1
                                            END,
                                            CONVERT(college_university_details.name USING utf8mb4) ASC";
                }

                $fields         = ['college_university_details.name,college_university_details.is_mou,college_university_details.whatsapp_number,college_university_details.website_link,m_city.name AS city_name', 'college_university_details.remark AS courese_list_name'];

                $params = array(
                'table'         => TBL_COLLEGE_UNIVERSITY_DETAILS.' as college_university_details',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                 "num"           => PRODUCT_PAGINATION_SIZE,
                 "offset"        => $per_page,
                 'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_CITY.' as m_city' => 'm_city.id = college_university_details.city_id',
                                    TBL_COURSES_DETAILS.' as courses_details'  => "FIND_IN_SET(courses_details.id, college_university_details.course_ids) > 0",
                                     
                                ),
            );
            $porductList = $this->General_model->get_query_data($params);  
            //prd($porductList);
             $cntParams = array(
                    'table'         => TBL_COLLEGE_UNIVERSITY_DETAILS.' as college_university_details',
                    'fields'        => $fields,
                    'wherestring'   => !empty($wherestring)?$wherestring:'',
                    'compare_type'  => '=',
                    "totalrow"      => '1',
                    'join_type'     => 'left',
                    'join_tables'   => array(
                                    TBL_CITY.' as m_city' => 'm_city.id = college_university_details.city_id',
                                    TBL_COURSES_DETAILS.' as courses_details'  => "FIND_IN_SET(courses_details.id, college_university_details.course_ids) > 0",
                                     
                                ),
                );
            $totalProduct = $this->General_model->get_query_data($cntParams); 
             if (!empty($totalProduct)) 
            {
                $total_page = ceil($totalProduct / PRODUCT_PAGINATION_SIZE);
            }  
            if(!empty($porductList))
            {
                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK;
                            $response['total_page']     = isset($total_page)?$total_page:'1';
                            $response['data']       = $porductList;
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
            
                   
        /*}
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }*/

        
        $this->response($response, 200);
    }
      public function college_university_list_without_pagination_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];

            

                $college_university_type_id=$data['college_university_type_id'];


                $wherestring    = "college_university_details.status=1";
                $wherestring    .= " and college_university_details.college_university_type_id='$college_university_type_id'";

                if(!empty($data['city_id']))
                {
                    $city_id=$data['city_id'];
                    $wherestring    .= " and college_university_details.city_id='$city_id'";
                }

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

                if(!empty($data['course_id']))
                {
                    $wherestring    .= " AND CONCAT(',', college_university_details.course_ids, ',') like '%,".$data['course_id'].",%'";
                }



                 $wherestring    .= " GROUP BY college_university_details.id";

                 $wherestring     .= " ORDER BY
                                            CASE
                                                WHEN college_university_details.name REGEXP '^[઀-૿]' THEN 0
                                                ELSE 1
                                            END,
                                            CONVERT(college_university_details.name USING utf8mb4) ASC";

                    $fields         = ['college_university_details.name,college_university_details.website_link,m_city.name AS city_name,college_university_details.course_ids'];

                $params = array(
                'table'         => TBL_COLLEGE_UNIVERSITY_DETAILS.' as college_university_details',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                 'join_type'     => 'left',
                'join_tables'   => array(
                                    TBL_CITY.' as m_city' => 'm_city.id = college_university_details.city_id',
                                    TBL_COURSES_DETAILS.' as courses_details'  => "FIND_IN_SET(courses_details.id, college_university_details.course_ids) > 0",
                                     
                                ),
            );
            $porductList = $this->General_model->get_query_data($params);   
            //prd($porductList);
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
            

        
        $this->response($response, 200);
    }
    public function college_application_post()
    {
        $data = $this->post();

        // Validate required fields
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['contact_number']) ||
            empty($data['course_type']) ||
            empty($data['sub_course_type']) ||
            empty($data['results_type']) ||
            !isset($data['results_value']) ||
            empty($data['passing_year'])
        ) {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = "Required fields are missing.";
            return $this->response($response, 200);
        }

        // Prepare insert data
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_number' => $data['contact_number'],
            'course_type' => $data['course_type'],
            'sub_course_type' => $data['sub_course_type'],
            'results_type' => $data['results_type'],
            'results_value' => $data['results_value'],
            'passing_year' => $data['passing_year'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert into database using General_model->insert
        $insert_id = $this->General_model->insert('college_application_form', $insertData);

        if ($insert_id) {
            // Optional: send WhatsApp or SMS notification if needed
            if (!empty($data['contact_number'])) {
                $msg = "Hello *{$data['name']}* 👋,\n\n"
                    . "Your application for *{$data['course_type']}* has been successfully received.\n"
                    . "Our team will contact you shortly. ✅\n\n"
                    . "Thank you for applying to our college 🌟";

                // Assuming you have Twilio or WhatsApp library
                if (isset($this->twilio_lib)) {
                    $this->twilio_lib->send_project_message(
                        $data['contact_number'],
                        $data['name'],
                        'College Application',
                        $msg
                    );
                }
            }

            $response = [
                'code' => REST_Controller::HTTP_OK,
                'message' => "Application submitted successfully.",
                'data' => ['application_id' => $insert_id]
            ];
        } else {
            $response = [
                'code' => REST_Controller::HTTP_INTERNAL_ERROR,
                'message' => "Failed to submit application."
            ];
        }

        return $this->response($response, 200);
    }
        
     



}