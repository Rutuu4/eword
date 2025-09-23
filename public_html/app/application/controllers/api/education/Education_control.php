<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Education_control extends REST_Controller {

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
 
    public function foreign_education_list_post()
    {
        $data = $this->post();
    
        $wherestring = "foreign_education.status=1";
    
        $fields = [
            'foreign_education.id',
            'foreign_education.consultancy_name',
            'foreign_education.establishment_year',
            'TIMESTAMPDIFF(YEAR, foreign_education.establishment_year, CURDATE()) AS total_years',
            'foreign_education.city AS city_id',
            'city.name AS city_name',
            'foreign_education.nearby_area',
            'foreign_education.mou_present',
            'foreign_education.whats_app_number',
            'foreign_education.institute_url',
    
            'country.id AS country_id',
            'country.name AS country_name',
    
            'visa_type.id AS visa_type_id',
            'visa_type.name AS visa_type_name',
    
            'exam_type.id AS exam_type_id',
            'exam_type.name AS exam_type_name',
    
            'f_courses.id AS course_id',
            'f_courses.name AS course_name',
        ];
    
        $params = [
            'table'         => 'foreign_education',
            'fields'        => $fields,
            'wherestring'   => $wherestring,
            'compare_type'  => '=',
            'join_type'     => 'left',
            'join_tables'   => [
                'f_courses' => 'f_courses.id = foreign_education.course_id',
                'foreign_education_countries' => 'foreign_education_countries.foreign_education_id = foreign_education.id',
                'country' => 'country.id = foreign_education_countries.country_id',
                'foreign_education_visa_types' => 'foreign_education_visa_types.foreign_education_id = foreign_education.id',
                'visa_type' => 'visa_type.id = foreign_education_visa_types.visa_type_id',
                'foreign_education_exam_types' => 'foreign_education_exam_types.foreign_education_id = foreign_education.id',
                'exam_type' => 'exam_type.id = foreign_education_exam_types.exam_type_id',
                'city' => 'city.id = foreign_education.city',
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
                    'MOU' => (bool)$row['mou_present'],
                    'whatsappNumber' => $row['whats_app_number'],
                    'year' => $row['establishment_year'],
                    'total' => $row['total_years'],
                    'web application link' => $row['institute_url'],
                    'Country' => [],
                    'visaType' => [],
                    'Course Details' => [],
                    'Exam Type' => []
                ];
            }
    
            // Append countries
            if (!empty($row['country_id'])) {
                $formatted_list[$id]['Country'][$row['country_id']] = [
                    'id' => $row['country_id'],
                    'name' => $row['country_name']
                ];
            }
    
            // Append visa types
            if (!empty($row['visa_type_id'])) {
                $formatted_list[$id]['visaType'][$row['visa_type_id']] = [
                    'id' => $row['visa_type_id'],
                    'name' => $row['visa_type_name']
                ];
            }
    
            // Append courses
            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Course Details'][$row['course_id']] = [
                    'id' => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
    
            // Append exams
            if (!empty($row['exam_type_id'])) {
                $formatted_list[$id]['Exam Type'][$row['exam_type_id']] = [
                    'id' => $row['exam_type_id'],
                    'name' => $row['exam_type_name']
                ];
            }
        }
    
        // Reset array keys (to make it a clean array, not an associative map)
        $result = array_values(array_map(function($institute) {
            $institute['Country'] = array_values($institute['Country']);
            $institute['visaType'] = array_values($institute['visaType']);
            $institute['Course Details'] = array_values($institute['Course Details']);
            $institute['Exam Type'] = array_values($institute['Exam Type']);
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
    
    
    public function filter_foreign_education_post()
{
    $data = $this->post();

    // Base where condition - only active entries
    $wheres = ["foreign_education.status = 1"];

    // Dynamic joins
    $join_tables = [
        'f_courses jointype left' => 'f_courses.id = foreign_education.course_id',
        'foreign_education_countries jointype left' => 'foreign_education_countries.foreign_education_id = foreign_education.id',
        'country jointype left' => 'country.id = foreign_education_countries.country_id',
        'foreign_education_visa_types jointype left' => 'foreign_education_visa_types.foreign_education_id = foreign_education.id',
        'visa_type jointype left' => 'visa_type.id = foreign_education_visa_types.visa_type_id',
        'foreign_education_exam_types jointype left' => 'foreign_education_exam_types.foreign_education_id = foreign_education.id',
        'exam_type jointype left' => 'exam_type.id = foreign_education_exam_types.exam_type_id',
        'city jointype left' => 'city.id = foreign_education.city',
    ];
    

    // Filters from input
    $course = isset($data['course']) ? trim($data['course']) : '';
    $city = isset($data['city']) ? trim($data['city']) : '';
    $visaType = isset($data['visa_type']) ? trim($data['visa_type']) : '';
    $examType = isset($data['exam_type']) ? trim($data['exam_type']) : '';
    $country = isset($data['country']) ? trim($data['country']) : '';

    // Course filter (partial match or NULL)
    if ($course !== '') {
        $wheres[] = "(f_courses.name LIKE '%" . $this->db->escape_like_str($course) . "%' OR foreign_education.course_id IS NULL)";
    }

    // City filter (exact match or NULL)
    if ($city !== '') {
        $wheres[] = "(city.name = '" . $this->db->escape_str($city) . "' OR foreign_education.city IS NULL)";
    }

    // Visa Type filter (exact match or NULL)
    if ($visaType !== '') {
        $wheres[] = "(visa_type.name = '" . $this->db->escape_str($visaType) . "' OR foreign_education_visa_types.visa_type_id IS NULL)";
    }

    // Exam Type filter (exact match or NULL)
    if ($examType !== '') {
        $wheres[] = "(exam_type.name = '" . $this->db->escape_str($examType) . "' OR foreign_education_exam_types.exam_type_id IS NULL)";
    }

    // Country filter (exact match or NULL)
    if ($country !== '') {
        $wheres[] = "(country.name = '" . $this->db->escape_str($country) . "' OR foreign_education_countries.country_id IS NULL)";
    }

    // Combine where string with AND
    $wherestring = implode(" AND ", $wheres);

    // Select fields
    $fields = [
        'foreign_education.id',
        'foreign_education.consultancy_name',
        'city.name as city_name',
        'foreign_education.nearby_area',
        'foreign_education.mou_present',
        'foreign_education.whats_app_number',
        'foreign_education.establishment_year',
        'TIMESTAMPDIFF(YEAR, foreign_education.establishment_year, CURDATE()) as total_years',
        'foreign_education.institute_url',
        'GROUP_CONCAT(DISTINCT country.name) as country',
        'GROUP_CONCAT(DISTINCT visa_type.name) as visa_types',
        'GROUP_CONCAT(DISTINCT f_courses.name) as courses',
        'GROUP_CONCAT(DISTINCT exam_type.name) as exam_types'
    ];

    // Group by foreign_education.id because of GROUP_CONCAT
    $params = [
        'table' => 'foreign_education',
        'fields' => $fields,
        'wherestring' => $wherestring,
        'join_tables' => $join_tables,
        'groupby' => 'foreign_education.id',
    ];

    $edu_list = $this->General_model->get_query_data($params);

    if (!empty($edu_list)) {
        // Process to convert concatenated strings to arrays
        foreach ($edu_list as &$item) {
            $item['country'] = $item['country'] ? explode(',', $item['country']) : [];
            $item['visa_types'] = $item['visa_types'] ? explode(',', $item['visa_types']) : [];
            $item['courses'] = $item['courses'] ? explode(',', $item['courses']) : [];
            $item['exam_types'] = $item['exam_types'] ? explode(',', $item['exam_types']) : [];
        }

        $response['message'] = $this->lang->line('success');
        $response['code'] = REST_Controller::HTTP_OK;
        $response['data'] = $edu_list;
    } else {
        $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
        $response['message'] = $this->lang->line('no_record_found');
    }

    $this->response($response, 200);
}

    
    

    


}