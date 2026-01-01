<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Education_control extends REST_Controller
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
        $this->globalVars         = $final;
    }

    public function foreign_education_list_post()
    {
        $data = $this->post();

        // ✅ Pagination setup
        $page = !empty($data['page_no']) ? (int)$data['page_no'] - 1 : 0;
        $offset = $page * PRODUCT_PAGINATION_SIZE; // same logic as course_list_post
        $limit = PRODUCT_PAGINATION_SIZE;

        // ✅ Base where condition
        $wherestring = "foreign_education.status=1";

        // ✅ Fields
        $fields = [
            'foreign_education.id',
            'foreign_education.consultancy_name',
            'foreign_education.establishment_year',
            // 'foreign_education.dob',
            'TIMESTAMPDIFF(YEAR, foreign_education.establishment_year, CURDATE()) AS total_years',
            'foreign_education.city AS city_id',
            'city_education.name AS city_name',
            'foreign_education.nearby_area',
            'foreign_education.mou_present',
            'foreign_education.whats_app_number',
            'foreign_education.institute_url',
            'foreign_education.course_ids',
            'country.id AS country_id',
            'country.name AS country_name',
            'visa_type.id AS visa_type_id',
            'visa_type.name AS visa_type_name',
            'exam_type.id AS exam_type_id',
            'exam_type.name AS exam_type_name'
        ];

        // ✅ Query for paginated data
        $params = [
            'table'         => 'foreign_education',
            'fields'        => $fields,
            'wherestring'   => $wherestring,
            'compare_type'  => '=',
            'join_type'     => 'left',
            'join_tables'   => [
                'foreign_education_countries' => 'foreign_education_countries.foreign_education_id = foreign_education.id',
                'country' => 'country.id = foreign_education_countries.country_id',
                'foreign_education_visa_types' => 'foreign_education_visa_types.foreign_education_id = foreign_education.id',
                'visa_type' => 'visa_type.id = foreign_education_visa_types.visa_type_id',
                'foreign_education_exam_types' => 'foreign_education_exam_types.foreign_education_id = foreign_education.id',
                'exam_type' => 'exam_type.id = foreign_education_exam_types.exam_type_id',
                'city_education' => 'city_education.id = foreign_education.city',
            ],
            'orderby' => 'foreign_education.consultancy_name',
            'order'   => 'ASC'
            // 'num'           => $limit,
            // 'offset'        => $offset
        ];

        $raw_list = $this->General_model->get_query_data($params);
        // ✅ Query for total count (without pagination)
        // $countParams = [
        //     'table'         => 'foreign_education',
        //     'fields'        => ['foreign_education.id'],
        //     'wherestring'   => $wherestring,
        //     'compare_type'  => '=',
        //     'totalrow'      => '1'
        // ];
        // $total = $this->General_model->get_query_data($countParams);

        // ✅ Grouping result (same logic as before)
        $formatted_list = [];
        foreach ($raw_list as $row) {
            $id = $row['id'];

            if (!isset($formatted_list[$id])) {
                // 🔹 fetch multiple courses based on comma-separated course_ids
                $courseDetails = [];
                if (!empty($row['course_ids'])) {
                    $courseIds = explode(',', $row['course_ids']);
                    $courseIds = array_filter($courseIds);
                    if (!empty($courseIds)) {
                        $inIds = implode(',', array_map('intval', $courseIds));
                        $courses = $this->db->query("SELECT id, name FROM f_courses WHERE id IN ($inIds)")->result_array();
                        foreach ($courses as $c) {
                            $courseDetails[] = [
                                'id' => $c['id'],
                                'name' => $c['name']
                            ];
                        }
                    }
                }

                $formatted_list[$id] = [
                    'id' => $id,
                    'Name' => $row['consultancy_name'],
                    // 'DOB' => $row['dob'],
                    'City' => $row['city_name'],
                    'Near By Area' => $row['nearby_area'],
                    'MOU' => (bool)$row['mou_present'],
                    'whatsappNumber' => $row['whats_app_number'],
                    'year' => $row['establishment_year'],
                    'total' => $row['total_years'],
                    'web application link' => $row['institute_url'],
                    'Country' => [],
                    'visaType' => [],
                    'Course Details' => $courseDetails,
                    'Exam Type' => []
                ];
            }

            if (!empty($row['country_id'])) {
                $formatted_list[$id]['Country'][$row['country_id']] = [
                    'id' => $row['country_id'],
                    'name' => $row['country_name']
                ];
            }

            if (!empty($row['visa_type_id'])) {
                $formatted_list[$id]['visaType'][$row['visa_type_id']] = [
                    'id' => $row['visa_type_id'],
                    'name' => $row['visa_type_name']
                ];
            }

            if (!empty($row['exam_type_id'])) {
                $formatted_list[$id]['Exam Type'][$row['exam_type_id']] = [
                    'id' => $row['exam_type_id'],
                    'name' => $row['exam_type_name']
                ];
            }
        }

        $result = array_values(array_map(function ($institute) {
            $institute['Country'] = array_values($institute['Country']);
            $institute['visaType'] = array_values($institute['visaType']);
            $institute['Exam Type'] = array_values($institute['Exam Type']);
            return $institute;
        }, $formatted_list));

        // ✅ Response
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

        // ================================
        // ✅ BASE CONDITION
        // ================================
        $wheres = ["foreign_education.status = 1"];

        // ================================
        // ✅ EXTRACT FILTER INPUT
        // ================================
        $city     = isset($data['city']) ? trim($data['city']) : '';
        $visa     = isset($data['visa_type']) ? trim($data['visa_type']) : '';
        $exam     = isset($data['exam_type']) ? trim($data['exam_type']) : '';
        $country  = isset($data['country']) ? trim($data['country']) : '';
        $course   = isset($data['course']) ? trim($data['course']) : '';

        // ================================
        // ✅ SAME LOGIC AS TUITION FILTER
        // ================================

        if ($city !== '') {
            $city = $this->db->escape_str($city);
            $wheres[] = "(city_education.name = '{$city}' OR foreign_education.city IS NULL)";
        }

        if ($visa !== '') {
            $visa = $this->db->escape_str($visa);
            $wheres[] = "(visa_type.name = '{$visa}' OR foreign_education_visa_types.visa_type_id IS NULL)";
        }

        if ($exam !== '') {
            $exam = $this->db->escape_str($exam);
            $wheres[] = "(exam_type.name = '{$exam}' OR foreign_education_exam_types.exam_type_id IS NULL)";
        }

        if ($country !== '') {
            $country = $this->db->escape_str($country);
            $wheres[] = "(country.name = '{$country}' OR foreign_education_countries.country_id IS NULL)";
        }

        if ($course !== '') {
            $courseLike = $this->db->escape_like_str($course);
            $wheres[] = "(f_courses.name LIKE '%{$courseLike}%' OR foreign_education.course_ids IS NULL)";
        }

        $wherestring = implode(" AND ", $wheres);

        // ================================
        // ✅ JOINS
        // ================================
        $join_tables = [
            'foreign_education_countries jointype left' => 'foreign_education_countries.foreign_education_id = foreign_education.id',
            'country jointype left' => 'country.id = foreign_education_countries.country_id',

            'foreign_education_visa_types jointype left' => 'foreign_education_visa_types.foreign_education_id = foreign_education.id',
            'visa_type jointype left' => 'visa_type.id = foreign_education_visa_types.visa_type_id',

            'foreign_education_exam_types jointype left' => 'foreign_education_exam_types.foreign_education_id = foreign_education.id',
            'exam_type jointype left' => 'exam_type.id = foreign_education_exam_types.exam_type_id',

            'city_education jointype left' => 'city_education.id = foreign_education.city',
            'f_courses jointype left' => 'FIND_IN_SET(f_courses.id, foreign_education.course_ids)'
        ];

        // ================================
        // ✅ FIELDS
        // ================================
        $fields = [
            'foreign_education.id',
            'foreign_education.consultancy_name',
            'foreign_education.city AS city_id',
            'city_education.name AS city_name',
            'foreign_education.nearby_area',
            'foreign_education.mou_present',
            'foreign_education.whats_app_number',
            'foreign_education.establishment_year',
            'foreign_education.course_ids',
            'TIMESTAMPDIFF(YEAR, foreign_education.establishment_year, CURDATE()) AS total_years',
            'foreign_education.institute_url',

            'GROUP_CONCAT(DISTINCT CONCAT(country.id, ":", country.name)) AS countries',
            'GROUP_CONCAT(DISTINCT CONCAT(visa_type.id, ":", visa_type.name)) AS visa_types',
            'GROUP_CONCAT(DISTINCT CONCAT(exam_type.id, ":", exam_type.name)) AS exam_types'
        ];

        // ================================
        // ✅ QUERY
        // ================================
        $params = [
            'table'       => 'foreign_education',
            'fields'      => $fields,
            'wherestring' => $wherestring,
            'join_tables' => $join_tables,
            'groupby'     => 'foreign_education.id',
            'orderby'     => 'foreign_education.consultancy_name',
            'order'       => 'ASC'
        ];

        $edu_list = $this->General_model->get_query_data($params);

        // ================================
        // ✅ FORMAT RESULT (FIX APPLIED)
        // ================================
        $formatted = [];

        foreach ($edu_list as $row) {

            // 🔴 IMPORTANT FIX: skip NULL fake row
            if (empty($row['id'])) {
                continue;
            }

            $courseDetails = [];
            if (!empty($row['course_ids'])) {
                $ids = implode(',', array_map('intval', explode(',', $row['course_ids'])));
                $courses = $this->db->query("SELECT id, name FROM f_courses WHERE id IN ($ids)")->result_array();
                foreach ($courses as $c) {
                    $courseDetails[] = [
                        'id'   => $c['id'],
                        'name' => $c['name']
                    ];
                }
            }

            $formatted[] = [
                'id'                   => $row['id'],
                'Name'                 => $row['consultancy_name'],
                'City'                 => $row['city_name'],
                'Near By Area'         => $row['nearby_area'],
                'MOU'                  => (bool)$row['mou_present'],
                'whatsappNumber'       => $row['whats_app_number'],
                'year'                 => $row['establishment_year'],
                'total'                => $row['total_years'],
                'web application link' => $row['institute_url'],
                'Country'              => $this->format_id_name_pairs($row['countries']),
                'visaType'             => $this->format_id_name_pairs($row['visa_types']),
                'Course Details'       => $courseDetails,
                'Exam Type'            => $this->format_id_name_pairs($row['exam_types']),
            ];
        }

        // ================================
        // ✅ RESPONSE
        // ================================
        if (!empty($formatted)) {
            $this->response([
                'code'    => REST_Controller::HTTP_OK,
                'message' => $this->lang->line('success'),
                'data'    => $formatted
            ], 200);
        } else {
            $this->response([
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found')
            ], 200);
        }
    }





    /**
     * ✅ Helper function to format "1:Name,2:Name" -> [{"id":1,"name":"Name"}]
     */
    private function format_id_name_pairs($string)
    {
        $pairs = [];
        if (!empty($string)) {
            $items = explode(',', $string);
            foreach ($items as $item) {
                list($id, $name) = explode(':', $item);
                $pairs[] = ['id' => (int)$id, 'name' => $name];
            }
        }
        return $pairs;
    }







    public function course_list_get()
    {

        $params = [
            'table' => 'f_courses',
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

    public function f_student_application_post()
    {
        $data = $this->post();

        // ✅ Validate required fields
        if (empty($data['name']) || empty($data['phoneNumber'])) {
            $response = [
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => "Name and phone number are required."
            ];
            return $this->response($response, 200);
        }

        // ✅ Validate DOB format (optional but recommended)
        if (!empty($data['dob'])) {
            $dob = date('Y-m-d', strtotime($data['dob']));
        } else {
            $dob = null;
        }

        // ❌ Removed email uniqueness check — duplicates and null are allowed

        // ✅ Prepare insert data
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null, // can be duplicate or null
            'phone_number' => $data['phoneNumber'],
            'dob' => $dob,
            'course_for_applying' => $data['courseForApplying'] ?? null,
            'exam_preference' => $data['examPreference'] ?? null,
            'preferred_country' => $data['preferredCountry'] ?? null,
            'visa_type' => $data['visaType'] ?? null,
            'foreign_education_id' => $data['foreignEducationId'] ?? null,
            'whatsapp_number' => $data['whatsAppNumber'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // ✅ Insert into database
        $insert_id = $this->General_model->insert('f_student_application', $insertData);

        if ($insert_id) {
            $foreignEducationName = 'E World Education'; // fallback

            if (!empty($data['foreignEducationId'])) {
                $fe = $this->db
                    ->select('consultancy_name')
                    ->from('foreign_education')
                    ->where('id', $data['foreignEducationId'])
                    ->get()
                    ->row();

                if ($fe && !empty($fe->consultancy_name)) {
                    $foreignEducationName = $fe->consultancy_name;
                }
            }
            // ✅ Send WhatsApp/SMS if phone number exists
            if (!empty($data['phoneNumber'])) {
                // ❌ Fields to exclude from message
                $excludeKeys = ['whatsAppNumber', 'foreignEducationId', 'id'];

                // 🧹 Remove excluded fields
                $messageData = array_diff_key($data, array_flip($excludeKeys));

                // 📝 Build readable message
                $messageText = "*Student Lead To Safal Academy From {$foreignEducationName}*\n\n"
                    . "Hello,\n\n"
                    . "A new student inquiry has been submitted to you through  *{$foreignEducationName}*\n\n"
                    . "*Student Details:*\n\n";

                foreach ($messageData as $key => $value) {

                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }

                    // Convert camelCase / snake_case to readable label
                    $label = ucwords(str_replace(['_', '-'], ' ', preg_replace('/([a-z])([A-Z])/', '$1 $2', $key)));

                    $messageText .= "{$label}: {$value}\n";
                }

                // Get WhatsApp number dynamically based on type
                $mou_phone_number = get_phone_number_by_type('foreign');

                $messageText .= "Please review the details and get in touch with the student.\n"
                    . "Thank You.\n\n"
                    . "*From*\n"
                    . "*{$foreignEducationName}*";

                $wpMessageData = [
                    'message_id'   => $this->uuid_v4(), // function below
                    'phone_number' => $data['whatsAppNumber'],
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
    public function country_get()
    {
        $data = $this->get();

        $params = array(
            'table'         => 'country',
            'where'   => array(
                'status' => 1
            ),
            'orderby'       => 'country.name ASC', // Assuming 'city_name' is the column for city names
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
    public function visa_type_get()
    {
        $data = $this->get();

        $params = array(
            'table'         => 'visa_type',
            'where'   => array(
                'status' => 1
            ),
            'orderby'       => 'visa_type.name ASC', // Assuming 'city_name' is the column for city names
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
    public function exam_type_get()
    {
        $data = $this->get();

        $params = array(
            'table'         => 'exam_type',
            'where'   => array(
                'status' => 1
            ),
            'orderby'       => 'exam_type.name ASC', // Assuming 'city_name' is the column for city names
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
}
