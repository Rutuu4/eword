<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Tuition_control extends REST_Controller {

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
 
    public function tuition_list_post()
    {
        $data = $this->post();
    
        $wherestring = "tuition_and_training.status=1";
    
        $fields = [
            'tuition_and_training.id',
            'tuition_and_training.consultancy_name',
            'tuition_and_training.city AS city_id',
            'city.name AS city_name',
            'tuition_and_training.nearby_area',
            'tuition_and_training.mou_is_present',
            'tuition_and_training.whats_app_number',
            'tuition_and_training.institute_web_url',
            't_courses.id AS course_id',
            't_courses.name AS course_name',
            'tuition_and_training.class_type'
        ];
        
    
        $params = [
            'table'         => 'tuition_and_training',
            'fields'        => $fields,
            'wherestring'   => $wherestring,
            'compare_type'  => '=',
            'join_type'     => 'left',
            'join_tables'   => [
                'tuition_and_training_courses' => 'tuition_and_training_courses.tuition_and_training_id = tuition_and_training.id',
                't_courses' => 't_courses.id = tuition_and_training_courses.course_id',
                
                'city' => 'city.id = tuition_and_training.city',
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
                    'whatsappNumber' => $row['whats_app_number'],
                    'web application link' => $row['institute_web_url'],
                    'Class Type' => $row['class_type'],
                    'Course Details' => []
                ];
            }
            
           
    
            // Append courses
            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Course Details'][$row['course_id']] = [
                    'id' => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
    
          
        }
    
        // Reset array keys (to make it a clean array, not an associative map)
        $result = array_values(array_map(function($institute) {
            
            $institute['Course Details'] = array_values($institute['Course Details']);
           
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
    
    
    public function tuition_filter_post()
    {
        $data = $this->post();
    
        // Base conditions
        $wheres = ["tuition_and_training.status = 1"];
    
        // Filters
        $class_mode = isset($data['class_type']) ? trim($data['class_type']) : '';
        $course     = isset($data['course']) ? trim($data['course']) : '';
        $city       = isset($data['city']) ? trim($data['city']) : '';
    
        // Apply filters
    
        if ($class_mode !== '') {
            $wheres[] = "(tuition_and_training.class_type = '" . $this->db->escape_str($class_mode) . "' OR tuition_and_training.class_type IS NULL)";
        }
    
        if ($course !== '') {
            $wheres[] = "(t_courses.name LIKE '%" . $this->db->escape_like_str($course) . "%' OR tuition_and_training_courses.course_id IS NULL)";
        }
    
        if ($city !== '') {
            $wheres[] = "(city.name = '" . $this->db->escape_str($city) . "' OR tuition_and_training.city IS NULL)";
        }
    
        $wherestring = implode(' AND ', $wheres);
    
        // Fields
        $fields = [
            'tuition_and_training.id',
            'tuition_and_training.consultancy_name',
            'tuition_and_training.city AS city_id',
            'city.name AS city_name',
            'tuition_and_training.nearby_area',
            'tuition_and_training.mou_is_present',
            'tuition_and_training.whats_app_number',
            'tuition_and_training.institute_web_url',
           
            't_courses.id AS course_id',
            't_courses.name AS course_name',
            'tuition_and_training.class_type'
        ];
    
        $params = [
            'table'         => 'tuition_and_training',
            'fields'        => $fields,
            'wherestring'   => $wherestring,
            'compare_type'  => '=',
            'join_type'     => 'left',
            'join_tables'   => [
                'tuition_and_training_courses' => 'tuition_and_training_courses.tuition_and_training_id = tuition_and_training.id',
                't_courses' => 't_courses.id = tuition_and_training_courses.course_id',
                'city' => 'city.id = tuition_and_training.city',
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
                    'whatsappNumber' => $row['whats_app_number'],
                    'web application link' => $row['institute_web_url'],
                    'Class Type' => $row['class_type'],
                    'Course Details' => []
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