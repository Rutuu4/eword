<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Tuition_control extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        include(substr($this->config->item('base_path'), 0, FOLDER_LENGHT) . '/include/database.php');
        foreach (globalVars() as $key => $value) {
            if (is_array(${$value})) {
                for ($i = 1; $i <= count(${$value}); $i++) {
                    $final[$value][$i] = ${$value}[$i];
                }
            } else {
                $final[$value] = ${$value};
            }
        }
        $this->globalVars         = $final;
    }
    public function tuition_list_post()
    {
        $data = $this->post();

        // ✅ Pagination
        $page  = isset($data['page']) ? (int)$data['page'] : 1;
        $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $wherestring = "tuition_and_training.status = 1";

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
            'table'       => 'tuition_and_training',
            'fields'      => $fields,
            'wherestring' => $wherestring,
            'compare_type' => '=',
            'join_type'   => 'left',
            'join_tables' => [
                'tuition_and_training_courses' => 'tuition_and_training_courses.tuition_and_training_id = tuition_and_training.id',
                't_courses'                    => 't_courses.id = tuition_and_training_courses.course_id',
                'city'                         => 'city.id = tuition_and_training.city',
            ],
            'groupby'     => 'tuition_and_training.id',
            'limit'       => $limit,
            'offset'      => $offset
        ];

        // ✅ Get paginated records
        $total = $this->General_model->get_query_data($params);

        // ✅ Total count without limit
        $countParams = $params;
        unset($countParams['limit'], $countParams['offset']);
        $total_records = $this->General_model->get_query_data_count($countParams);

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
                    'whatsappNumber'       => $row['whats_app_number'],
                    'web application link' => $row['institute_web_url'],
                    'Class Type'           => $row['class_type'],
                    'Course Details'       => []
                ];
            }

            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Course Details'][$row['course_id']] = [
                    'id'   => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
        }

        $result = array_values(array_map(function ($item) {
            $item['Course Details'] = array_values($item['Course Details']);
            return $item;
        }, $formatted_list));

        // ✅ Response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['total_page'] = ceil($total / PRODUCT_PAGINATION_SIZE);
            $response['data'] = $result;
        } else {
            $response = [
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found')
            ];
        }

        $this->response($response, 200);
    }

    public function tuition_filter_post()
    {
        $data = $this->post();

        // ✅ Pagination
        $page  = isset($data['page']) ? (int)$data['page'] : 1;
        $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
        $offset = ($page - 1) * $limit;

        // ✅ Filters
        $wheres = ["tuition_and_training.status = 1"];

        $class_mode = isset($data['class_type']) ? trim($data['class_type']) : '';
        $course     = isset($data['course']) ? trim($data['course']) : '';
        $city       = isset($data['city']) ? trim($data['city']) : '';

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

        // ✅ Fields
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
            'table'       => 'tuition_and_training',
            'fields'      => $fields,
            'wherestring' => $wherestring,
            'compare_type' => '=',
            'join_type'   => 'left',
            'join_tables' => [
                'tuition_and_training_courses' => 'tuition_and_training_courses.tuition_and_training_id = tuition_and_training.id',
                't_courses'                    => 't_courses.id = tuition_and_training_courses.course_id',
                'city'                         => 'city.id = tuition_and_training.city',
            ],
            'groupby'     => 'tuition_and_training.id',
            'limit'       => $limit,
            'offset'      => $offset
        ];

        // ✅ Get paginated records
        $raw_list = $this->General_model->get_query_data($params);

        // ✅ Total count without limit
        $countParams = $params;
        unset($countParams['limit'], $countParams['offset']);
        $total_records = $this->General_model->get_query_data_count($countParams);

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
                    'whatsappNumber'       => $row['whats_app_number'],
                    'web application link' => $row['institute_web_url'],
                    'Class Type'           => $row['class_type'],
                    'Course Details'       => []
                ];
            }

            if (!empty($row['course_id'])) {
                $formatted_list[$id]['Course Details'][$row['course_id']] = [
                    'id'   => $row['course_id'],
                    'name' => $row['course_name']
                ];
            }
        }

        $result = array_values(array_map(function ($item) {
            $item['Course Details'] = array_values($item['Course Details']);
            return $item;
        }, $formatted_list));

        // ✅ Response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['total_page'] = ceil($total / PRODUCT_PAGINATION_SIZE);
            $response['data'] = $result;
        } else {
            $response = [
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found')
            ];
        }

        $this->response($response, 200);
    }

    public function course_list_get()
    {

        $params = [
            'table' => 't_courses',
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
    public function t_tuition_application_post()
    {
        $data = $this->post();

        // Validate required fields
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['phoneNumber']) ||
            empty($data['courseForApplying']) ||
            empty($data['tuitionTrainingId'])
        ) {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = "Required fields are missing.";
            return $this->response($response, 200);
        }

        // Prepare insert data
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'],
            'course_applying' => $data['courseForApplying'],
            'tuition_and_training_id' => $data['tuitionTrainingId'],
            'class_type' => $data['preferredClassType'] ?? null,
            'whatsapp_number' => $data['whatsAppNumber'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert into database
        $insert_id = $this->General_model->insert('t_student_application', $insertData);

        if ($insert_id) {
            // If WhatsApp number exists, send message
            if (!empty($data['phoneNumber'])) {
                $msg = "Hello *{$data['name']}* 👋,\n\n"
                    . "Your tuition/training application for *{$data['courseForApplying']}* has been successfully received.\n"
                    . "Our academic team will contact you soon with the next steps. 📚\n\n"
                    . "Thank you for choosing *Eword Education* 🙌";

                $this->twilio_lib->send_project_message(
                    $data['phoneNumber'],
                    $data['name'],
                    'Tuition & Training Application',
                    $msg
                );
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