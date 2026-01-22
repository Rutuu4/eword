<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Project_control extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        include(substr($this->config->item('base_path'), 0, FOLDER_LENGHT) . '/include/database.php');
        $this->wp_db = $this->load->database('wp_db', TRUE);
        $this->load->helper('common_helper');
        foreach (globalVars() as $key => $value) {
            if (is_array(${$value})) {
                for ($i = 1; $i <= count(${$value}); $i++) {
                    $final[$value][$i] = ${$value}[$i];
                }
            } else {
                $final[$value] = ${$value};
            }
        }
        $this->globalVars = $final;
    }

    public function project_list_post()
    {
        $data = $this->post();

        // ✅ Pagination (same logic as foreign_education_list_post)
        $page  = !empty($data['page_no']) ? (int)$data['page_no'] - 1 : 0;
        $offset = $page * PRODUCT_PAGINATION_SIZE;
        $limit  = PRODUCT_PAGINATION_SIZE;

        // ✅ Base condition
        $wherestring = "project_and_internship.status = 1";

        // ✅ Fields
        $fields = [
            'project_and_internship.id',
            'project_and_internship.consultancy_name',
            'project_and_internship.city_id AS city_id',
            'city_project.name AS city_name',
            'project_and_internship.nearby_area',
            'project_and_internship.mou_is_present',
            'project_and_internship.whatsapp_number',
            'project_and_internship.institute_web_url',
            'p_courses.id AS course_id',
            'p_courses.name AS course_name',
            'project_and_internship.job_type'
        ];

        // ✅ Paginated records query
        $params = [
            'table'       => 'project_and_internship',
            'fields'      => $fields,
            'wherestring' => $wherestring,
            'compare_type' => '=',
            'join_type'   => 'left',
            'join_tables' => [
                'project_and_internship_courses' => 'project_and_internship_courses.project_and_internship_id = project_and_internship.id',
                'p_courses'                      => 'p_courses.id = project_and_internship_courses.course_id',
                'city_project'                           => 'city_project.id = project_and_internship.city_id',
            ],
            'orderby' => 'project_and_internship.consultancy_name',
            'order'   => 'ASC'
            // 'num'         => $limit,
            // 'offset'      => $offset
        ];

        $raw_list = $this->General_model->get_query_data($params);

        // ✅ Total count (without pagination)
        // $countParams = [
        //     'table'       => 'project_and_internship',
        //     'fields'      => ['project_and_internship.id'],
        //     'wherestring' => $wherestring,
        //     'compare_type' => '=',
        //     'totalrow'    => '1'
        // ];
        // $total = $this->General_model->get_query_data($countParams);

        // ✅ Format grouped result
        $formatted_list = [];
        foreach ($raw_list as $row) {
            $id = $row['id'];
            if (!isset($formatted_list[$id])) {
                $formatted_list[$id] = [
                    'id'                   => $id,
                    'Name'                 => $row['consultancy_name'],
                    'City'                 => $row['city_name'],
                    'Near By Area'         => $row['nearby_area'],
                    'MOU'                  => (bool)$row['mou_is_present'],
                    'whatsappNumber'       => $row['whatsapp_number'],
                    'web application link' => $row['institute_web_url'],
                    'Job Type'           => !empty($row['job_type']) ? explode(',', $row['job_type']) : [],

                    'Category Details'     => []
                ];
            }

            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Category Details'][$row['course_id']] = [
                    'id'   => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
        }

        $result = array_values(array_map(function ($item) {
            $item['Category Details'] = array_values($item['Category Details']);
            return $item;
        }, $formatted_list));

        // ✅ Final response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            // $response['total_page'] = ceil($total / PRODUCT_PAGINATION_SIZE);
            $response['data'] = $result;
        } else {
            $response = [
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found'),
            ];
        }

        $this->response($response, 200);
    }



    public function project_filter_post()
    {
        $data = $this->post();

        // ✅ Pagination
        $page  = !empty($data['page_no']) ? (int)$data['page_no'] - 1 : 0;
        $offset = $page * PRODUCT_PAGINATION_SIZE;
        $limit  = PRODUCT_PAGINATION_SIZE;

        // ✅ Filters
        $wheres = ["project_and_internship.status = 1"];

        $class_mode = isset($data['job_type']) ? trim($data['job_type']) : '';
        $course     = isset($data['course']) ? trim($data['course']) : '';
        $city       = isset($data['city']) ? trim($data['city']) : '';

        if ($class_mode !== '') {
            $job_type = $this->db->escape_str($class_mode);
            $wheres[] = "(FIND_IN_SET('{$job_type}', project_and_internship.job_type) 
                  OR project_and_internship.job_type IS NULL)";
        }


        if ($course !== '') {
            $wheres[] = "(p_courses.name LIKE '%" . $this->db->escape_like_str($course) . "%' OR project_and_internship_courses.course_id IS NULL)";
        }

        if ($city !== '') {
            $wheres[] = "(city_project.name = '" . $this->db->escape_str($city) . "' OR project_and_internship.city_id IS NULL)";
        }

        $wherestring = implode(' AND ', $wheres);

        $fields = [
            'project_and_internship.id',
            'project_and_internship.consultancy_name',
            'project_and_internship.city_id AS city_id',
            'city_project.name AS city_name',
            'project_and_internship.nearby_area',
            'project_and_internship.mou_is_present',
            'project_and_internship.whatsapp_number',
            'project_and_internship.institute_web_url',
            'p_courses.id AS course_id',
            'p_courses.name AS course_name',
            'project_and_internship.job_type'
        ];

        $params = [
            'table'       => 'project_and_internship',
            'fields'      => $fields,
            'wherestring' => $wherestring,
            'compare_type' => '=',
            'join_type'   => 'left',
            'join_tables' => [
                'project_and_internship_courses' => 'project_and_internship_courses.project_and_internship_id = project_and_internship.id',
                'p_courses'                      => 'p_courses.id = project_and_internship_courses.course_id',
                'city_project'                           => 'city_project.id = project_and_internship.city_id',
            ],
            'groupby'     => 'project_and_internship.id',
            'orderby' => 'project_and_internship.consultancy_name',
            'order'   => 'ASC'
            // 'limit'       => $limit,
            // 'offset'      => $offset
        ];

        // ✅ Get paginated records
        $raw_list = $this->General_model->get_query_data($params);

        // ✅ Total count without limit
        // $countParams = $params;
        // unset($countParams['limit'], $countParams['offset']);
        // $total_records = $this->General_model->get_query_data($countParams);
        // $total_count = is_array($total_records) ? count($total_records) : 0;

        // ✅ Format result
        $formatted_list = [];
        foreach ($raw_list as $row) {
            $id = $row['id'];
            if (!isset($formatted_list[$id])) {
                $formatted_list[$id] = [
                    'id'                   => $id,
                    'Name'                 => $row['consultancy_name'],
                    'City'                 => $row['city_name'],
                    'Near By Area'         => $row['nearby_area'],
                    'MOU'                  => (bool)$row['mou_is_present'],
                    'whatsappNumber'       => $row['whatsapp_number'],
                    'web application link' => $row['institute_web_url'],
                    'Job Type'           => !empty($row['job_type']) ? explode(',', $row['job_type']) : [],
                    'Category Details'       => []
                ];
            }

            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Category Details'][$row['course_id']] = [
                    'id'   => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
        }

        $result = array_values(array_map(function ($item) {
            $item['Category Details'] = array_values($item['Category Details']);
            return $item;
        }, $formatted_list));

        // ✅ Response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            // $response['total_page'] = ceil($total_count / PRODUCT_PAGINATION_SIZE);
            $response['data'] = $result;
        } else {
            $response = [
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found'),
            ];
        }

        $this->response($response, 200);
    }


    public function course_list_get()
    {

        $params = [
            'table' => 'p_courses',
            'fields' => ['id', 'name'],
            'wherestring' => 'status=1',
            'orderby' => 'name',
            'orderdirection' => 'ASC'
        ];

        $course_list = $this->General_model->get_query_data($params);

        if (!empty($course_list)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['data'] = $course_list;
        } else {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        $this->response($response, 200);
    }
    function uuid_v4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
    public function p_project_application_post()
    {
        $data = $this->post();

        // ✅ Validate only name and phoneNumber as required
        if (empty($data['name']) || empty($data['phoneNumber'])) {
            $response = [
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => "Name and phone number are required."
            ];
            return $this->response($response, 200);
        }

        // 🚫 Removed email uniqueness check

        // ✅ Prepare insert data (optional fields handled with null fallback)
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phoneNumber'],
            'domain' => $data['domain'] ?? null,
            'job_type' => $data['courseType'] ?? null,
            'project_and_internship_id' => $data['projectPlacementId'] ?? null,
            'whatsapp_number' => $data['whatsappNumber'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // ✅ Insert into database
        $insert_id = $this->General_model->insert('p_student_application', $insertData);

        if ($insert_id) {
            $projectInternshipName = 'Generated'; // fallback

            if (!empty($data['projectPlacementId'])) {
                $fe = $this->db
                    ->select('consultancy_name')
                    ->from('project_and_internship')
                    ->where('id', $data['projectPlacementId'])
                    ->get()
                    ->row();

                if ($fe && !empty($fe->consultancy_name)) {
                    $projectInternshipName = "To " . $fe->consultancy_name;
                }
            }
            // ✅ Send WhatsApp Message
            if (!empty($data['phoneNumber'])) {
                $excludeKeys = ['whatsappNumber', 'id'];

                $messageData = array_diff_key($data, array_flip($excludeKeys));

                $messageText = "*Student Lead {$projectInternshipName} From E World Education*\n\n"
                    . "Hello,\n\n"
                    . "A new student inquiry has been submitted to you through  *E World Education*\n\n"
                    . "*Student Details:*\n\n";

                foreach ($messageData as $key => $value) {

                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }

                    $label = ucwords(str_replace(
                        ['_', '-'],
                        ' ',
                        preg_replace('/([a-z])([A-Z])/', '$1 $2', $key)
                    ));

                    $messageText .= "{$label}: {$value}\n";
                }

                $messageText .= "Please review the details and get in touch with the student.\n"
                    . "Thank You.\n\n"
                    . "*From*\n"
                    . "*E World Education*";
                $mou_phone_number = get_phone_number_by_type('internship');

                // ================================
                // ✅ INSERT INTO wp_messages
                // ================================
                $wpMessageData = [
                    'message_id'   => $this->uuid_v4(),
                    'phone_number' => $data['whatsappNumber'],
                    'mou_phone_number' => $mou_phone_number,
                    'message_text' => $messageText,
                    'message_type' => 'text',
                    'status'       => 'queued',
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];

                $this->wp_db->insert('wp_messages', $wpMessageData);
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