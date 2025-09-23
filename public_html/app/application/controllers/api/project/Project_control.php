<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Project_control extends REST_Controller {

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
 
    public function project_list_post()
    {
        $data = $this->post();
    
        $wherestring = "project_and_internship.status=1";
    
        $fields = [
            'project_and_internship.id',
            'project_and_internship.consultancy_name',
            'project_and_internship.city_id AS city_id',
            'city.name AS city_name',
            'project_and_internship.nearby_area',
            'project_and_internship.mou_is_present',
            'project_and_internship.whatsapp_number',
            'project_and_internship.institute_web_url',
            'p_courses.id AS course_id',
            'p_courses.name AS course_name',
            'project_and_internship.job_type'
        ];
        
    
        $params = [
            'table'         => 'project_and_internship',
            'fields'        => $fields,
            'wherestring'   => $wherestring,
            'compare_type'  => '=',
            'join_type'     => 'left',
            'join_tables'   => [
                'project_and_internship_courses' => 'project_and_internship_courses.project_and_internship_id = project_and_internship.id',
                'p_courses' => 'p_courses.id = project_and_internship_courses.course_id',
                
                'city' => 'city.id = project_and_internship.city_id',
            ]
        ];
    
        $raw_list = $this->General_model->get_query_data($params);
    
        $formatted_list = [];
    
        foreach ($raw_list as $row) {
            $id = $row['id'];
    
            // Initialize if not exists
            if (!isset($formatted_list[$id])) {
                $formatted_list[$id] = [
                    'id' => $id,
                    'Name' => $row['consultancy_name'],
                    'City' => $row['city_name'],
                    'Near By Area' => $row['nearby_area'],
                    'MOU' => (bool)$row['mou_is_present'],
                    'whatsappNumber' => $row['whatsapp_number'],
                    'web application link' => $row['institute_web_url'],
                   'Job Type' =>match($row['job_type']) {
                        'Part_time' => 'Part Time',
                        'Full_time' => 'Full Time',
                        'Remote'    => 'Remote',
                        default     => null
                    },
                    'Category Details' => []
                ];
            }
            
           
    
            // Append courses
            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Category Details'][$row['course_id']] = [
                    'id' => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
    
          
        }
    
        // Reset array keys (to make it a clean array, not an associative map)
        $result = array_values(array_map(function($institute) {
            
            $institute['Category Details'] = array_values($institute['Category Details']);
           
            return $institute;
        }, $formatted_list));
    
        // Final response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['data'] = $result;
        } else {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
    
        $this->response($response, 200);
    }
    
    
    public function project_filter_post()
    {
        $data = $this->post();
    
        // Base conditions
        $wheres = ["project_and_internship.status = 1"];
    
        // Filters
        $class_mode = isset($data['job_type']) ? trim($data['job_type']) : '';
        $course     = isset($data['course']) ? trim($data['course']) : '';
        $city       = isset($data['city']) ? trim($data['city']) : '';
    
        // Apply filters
    
        if ($class_mode !== '') {
            $wheres[] = "(project_and_internship.job_type = '" . $this->db->escape_str($class_mode) . "' OR project_and_internship.job_type IS NULL)";
        }
    
        if ($course !== '') {
            $wheres[] = "(p_courses.name LIKE '%" . $this->db->escape_like_str($course) . "%' OR project_and_internship_courses.course_id IS NULL)";
        }
    
        if ($city !== '') {
            $wheres[] = "(city.name = '" . $this->db->escape_str($city) . "' OR project_and_internship.city_id IS NULL)";
        }
    
        $wherestring = implode(' AND ', $wheres);
    
        // Fields
        $fields = [
            'project_and_internship.id',
            'project_and_internship.consultancy_name',
            'project_and_internship.city_id AS city_id',
            'city.name AS city_name',
            'project_and_internship.nearby_area',
            'project_and_internship.mou_is_present',
            'project_and_internship.whatsapp_number',
            'project_and_internship.institute_web_url',
           
            'p_courses.id AS course_id',
            'p_courses.name AS course_name',
            'project_and_internship.job_type'
        ];
    
        $params = [
            'table'         => 'project_and_internship',
            'fields'        => $fields,
            'wherestring'   => $wherestring,
            'compare_type'  => '=',
            'join_type'     => 'left',
            'join_tables'   => [
                'project_and_internship_courses' => 'project_and_internship_courses.project_and_internship_id = project_and_internship.id',
                'p_courses' => 'p_courses.id = project_and_internship_courses.course_id',
                'city' => 'city.id = project_and_internship.city_id',
            ]
        ];
    
        // Get raw data
        $raw_list = $this->General_model->get_query_data($params);
    
        // Format response
        $formatted_list = [];
    
        foreach ($raw_list as $row) {
            $id = $row['id'];
    
            if (!isset($formatted_list[$id])) {
                $formatted_list[$id] = [
                    'id' => $id,
                    'Name' => $row['consultancy_name'],
                    'City' => $row['city_name'],
                    'Near By Area' => $row['nearby_area'],
                    'MOU' => (bool)$row['mou_is_present'],
                    'whatsappNumber' => $row['whatsapp_number'],
                    'web application link' => $row['institute_web_url'],
                    'Job Type' =>match($row['job_type']) {
                        'Part_time' => 'Part Time',
                        'Full_time' => 'Full Time',
                        'Remote'    => 'Remote',
                        default     => null
                    },
                    'Category Details' => []
                ];
            }
    
            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Course Details'][$row['course_id']] = [
                    'id' => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
        }
    
        // Clean result
        $result = array_values(array_map(function($institute) {
            $institute['Course Details'] = array_values($institute['Course Details']);
            return $institute;
        }, $formatted_list));
    
        // Response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['data'] = $result;
        } else {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }
    
        $this->response($response, 200);
    }
    

    
    

    


}