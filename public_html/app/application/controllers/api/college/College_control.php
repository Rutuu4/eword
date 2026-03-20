<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class College_control extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        include(substr($this->config->item('base_path'), 0, FOLDER_LENGHT) . '/include/database.php');
        $this->wp_db = $this->load->database('wp_db', TRUE);
        $this->load->helper('common_helper');
        $this->load->helper('college_whatsapp');
        $this->load->helper('whatsapp_number');
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


    public function college_university_type_list_post()
    {
        $data = $this->post();

        $wherestring    = "status=1";
        $params = array(
            'table'         => TBL_COLLEGE_UNIVERSITY_TYPE . ' as m_college_university_type',
            'wherestring'   => !empty($wherestring) ? $wherestring : '',
            'compare_type'  => '=',
        );
        $prayer_list = $this->General_model->get_query_data($params);
        //prd($prayer_list);
        if (!empty($prayer_list)) {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $prayer_list;
        } else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        $this->response($response, 200);
    }

    public function college_university_list_post()
    {
        $data = $this->post();
        $user_id = $data['user_id'];

        $page       = !empty($data['page_no']) ? $data['page_no'] - 1 : '1';
        $per_page   = $page * PRODUCT_PAGINATION_SIZE;

        $college_university_type_id = $data['college_university_type_id'];

        $wherestring = "college_university_details.status=1";
        $wherestring .= " AND college_university_details.college_university_type_id='$college_university_type_id'";

        if (!empty($data['city_id'])) {
            $city_id = $data['city_id'];
            $wherestring .= " AND college_university_details.city_id='$city_id'";
        }

        if (!empty($data['main_courses_id'])) {
            $main_courses_id = $data['main_courses_id'];
            $wherestring .= " AND courses_details.main_courses_id='$main_courses_id'";
        }

        if (!empty($data['extra_course_id'])) {
            $extra_course_id = $data['extra_course_id'];
            $wherestring .= " AND courses_details.extra_course_id='$extra_course_id'";
        }

        if (!empty($data['course_id'])) {
            $wherestring .= " AND ccm.course_id = '" . $data['course_id'] . "'";
        }

        // $wherestring .= " GROUP BY college_university_details.id";

        if ($data['college_university_type_id'] == 1 || $data['college_university_type_id'] == 2) {
            // $wherestring .= " ORDER BY
            //                     CASE
            //                     WHEN college_university_details.name REGEXP '^[A-Za-z]' THEN 0
            //                     ELSE 1
            //                     END,
            //                     college_university_details.name ASC";
        }

        // ✅ Main fields + GROUP_CONCAT for course/sub-main course info
        $fields = [
            'college_university_details.id',
            'college_university_details.name',
            'college_university_details.is_mou',
            'college_university_details.whatsapp_number',
            'college_university_details.website_link',
            'm_city.name AS city_name',
            'college_university_details.remark AS courese_list_name',

            // ✅ EXACT SAME FORMAT AS YOUR OUTPUT
            'GROUP_CONCAT(DISTINCT courses_details.id) AS course_ids',
            'GROUP_CONCAT(DISTINCT courses_details.name) AS course_names',

            'GROUP_CONCAT(DISTINCT m_exrta_course.id) AS sub_main_course_ids',
            'GROUP_CONCAT(DISTINCT m_exrta_course.name) AS sub_main_course_names'
        ];

        $params = array(
            'table' => TBL_COLLEGE_UNIVERSITY_DETAILS . ' AS college_university_details',
            'fields' => $fields,
            'wherestring' => $wherestring,
            'num' => PRODUCT_PAGINATION_SIZE,
            'offset' => $per_page,
            'join_type' => 'left',

            // ✅ IMPORTANT
            'group_by' => 'college_university_details.id',

            'order_by' => "CASE
        WHEN college_university_details.name REGEXP '^[A-Za-z]' THEN 0
        ELSE 1
    END,
    college_university_details.name ASC",

            'join_tables' => array(
                TBL_CITY . ' AS m_city' => 'm_city.id = college_university_details.city_id',

                // ✅ CORRECT JOIN (ONLY ONCE)
                TBL_COLLEGE_COURSE_MAP . ' AS ccm' => 'ccm.college_id = college_university_details.id',

                TBL_COURSES_DETAILS . ' AS courses_details' => 'courses_details.id = ccm.course_id',

                TBL_EXRTA_COURSE . ' AS m_exrta_course' => 'm_exrta_course.id = courses_details.extra_course_id',
            ),
        );

        $porductList = $this->General_model->get_query_data($params);
        //prd($porductList);
        $cntParams = array(
            'table' => TBL_COLLEGE_UNIVERSITY_DETAILS . ' as college_university_details',
            'fields' => 'COUNT(DISTINCT college_university_details.id) as total',
            'wherestring' => $wherestring,
            'join_type' => 'left',

            'join_tables' => array(
                TBL_COLLEGE_COURSE_MAP . ' AS ccm' => 'ccm.college_id = college_university_details.id',
                TBL_COURSES_DETAILS . ' AS courses_details' => 'courses_details.id = ccm.course_id',
            ),
        );
        $totalProduct = $this->General_model->get_query_data($cntParams);

        if (!empty($totalProduct)) {
            $total_count = $totalProduct[0]['total'];   // 🔥 important
            $total_page = ceil($total_count / PRODUCT_PAGINATION_SIZE);
        }
        if (!empty($porductList)) {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['total_page']     = isset($total_page) ? $total_page : '1';
            $response['data']       = $porductList;
        } else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        $this->response($response, 200);
    }

    public function college_university_list_without_pagination_post()
    {
        $data = $this->post();
        $user_id = $data['user_id'];



        $college_university_type_id = $data['college_university_type_id'];


        $wherestring = "college_university_details.status=1";
        $wherestring .= " AND college_university_details.college_university_type_id='$college_university_type_id'";

        if (!empty($data['city_id'])) {
            $wherestring .= " AND college_university_details.city_id='" . $data['city_id'] . "'";
        }

        if (!empty($data['main_courses_id'])) {
            $wherestring .= " AND courses_details.main_courses_id='" . $data['main_courses_id'] . "'";
        }

        if (!empty($data['extra_course_id'])) {
            $wherestring .= " AND courses_details.extra_course_id='" . $data['extra_course_id'] . "'";
        }

        if (!empty($data['course_id'])) {
            $wherestring .= " AND FIND_IN_SET('" . $data['course_id'] . "', college_university_details.course_ids)";
        }



        // $wherestring    .= " GROUP BY college_university_details.id";

        // $wherestring     .= " ORDER BY
        //                                     CASE
        //                                         WHEN college_university_details.name REGEXP '^[઀-૿]' THEN 0
        //                                         ELSE 1
        //                                     END,
        //                                     CONVERT(college_university_details.name USING utf8mb4) ASC";

        $fields         = ['college_university_details.name,college_university_details.website_link,m_city.name AS city_name,college_university_details.course_ids'];

        $params = array(
            'table'         => TBL_COLLEGE_UNIVERSITY_DETAILS . ' as college_university_details',
            'fields'        => [
                'college_university_details.name',
                'college_university_details.website_link',
                'm_city.name AS city_name',
                'college_university_details.course_ids'
            ],
            'wherestring'   => $wherestring,
            'join_type'     => 'left',

            // ✅ CORRECT PLACE
            'group_by'      => 'college_university_details.id',

            // ✅ CORRECT PLACE
            'order_by'      => "CASE
        WHEN college_university_details.name REGEXP '^[઀-૿]' THEN 0
        ELSE 1
    END,
    CONVERT(college_university_details.name USING utf8mb4) ASC",

            'join_tables'   => array(
                TBL_CITY . ' as m_city' => 'm_city.id = college_university_details.city_id',

                // ✅ optimized join
                TBL_COLLEGE_COURSE_MAP . ' AS ccm' => 'ccm.college_id = college_university_details.id',
                TBL_COURSES_DETAILS . ' AS courses_details' => 'courses_details.id = ccm.course_id',
            ),
        );
        $porductList = $this->General_model->get_query_data($params);
        //prd($porductList);
        if (!empty($porductList)) {
            $response['message']    = $this->lang->line('success');
            $response['code']       = REST_Controller::HTTP_OK;
            $response['data']       = $porductList;
        } else {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
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
    public function college_application_post()
    {
        $data = $this->post();

        // ✅ Validate only name and contact number as required
        if (empty($data['name']) || empty($data['contact_number'])) {
            $response = [
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => "Name and contact number are required."
            ];
            return $this->response($response, 200);
        }

        if (!empty($data['collegeId'])) {
            $fe = $this->db
                ->select('whatsapp_number')
                ->from('college_university_details')
                ->where('id', $data['collegeId'])
                ->get()
                ->row();

            if ($fe && !empty($fe->whatsapp_number)) {
                $whatsAppNumber = $fe->whatsapp_number ?? null;
            }
        }

        // ✅ Prepare insert data
        $insertData = [
            'name' => $data['name'],
            'contact_number' => $data['contact_number'],
            'course_type' => $data['course_type'] ?? null,
            'sub_course_type' => $data['sub_course_type'] ?? null,
            'results_type' => $data['results_type'] ?? null,
            'results_value' => $data['results_value'] ?? null,
            'passing_year' => $data['passing_year'] ?? null,
            'whatsAppNumber' => $whatsAppNumber ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'college_id' => $data['collegeId'] ?? null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // ✅ Insert application
        $insert_id = $this->General_model->insert('college_application_form', $insertData);

        if ($insert_id) {
            $collegeName = ''; // fallback

            if (!empty($data['collegeId'])) {
                $fe = $this->db
                    ->select('name')
                    ->from('college_university_details')
                    ->where('id', $data['collegeId'])
                    ->get()
                    ->row();

                if ($fe && !empty($fe->name)) {
                    $collegeName = $fe->name;
                }
            }
            // ================================
            // ✅ BUILD MESSAGE & INSERT
            // ================================
            if (!empty($data['contact_number'])) {

                $excludeKeys = ['whatsAppNumber', 'id'];
                $messageData = array_diff_key($data, array_flip($excludeKeys));

                $studentData = "";

                foreach ($data as $key => $value) {

                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }

                    $label = ucwords(str_replace('_', ' ', $key));

                    $studentData .= "{$label}: {$value}, ";
                }

                $studentData = rtrim($studentData, ", ");
                $whatsAppNumber = format_whatsapp_number($whatsAppNumber);
                $value1    = $collegeName;
                $name      = $data['name'] ?? '-';
                $email     = $data['email'] ?? '-';
                $phoneno   = $data['contact_number'] ?? '-';
                $course_type   = $data['course_type'] ?? '-';
                $sub_course_type   = $data['sub_course_type'] ?? '-';
                $results_type   = $data['results_type'] ?? '-';
                // $collegeid = $data['collegeId'] ?? '';

                send_whatsapp_template(
                    $whatsAppNumber,
                    $value1,
                    $name,
                    $email,
                    $phoneno,
                    $course_type,
                    $sub_course_type,
                    $results_type
                );
                $mou_phone_number = get_phone_number_by_type('college');
                if ($mou_phone_number) {
                    send_whatsapp_template(
                        format_whatsapp_number($mou_phone_number),
                        $value1,
                        $name,
                        $email,
                        $phoneno,
                        $course_type,
                        $sub_course_type,
                        $results_type
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
