<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Job_control extends REST_Controller
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

    public function job_application_post()
    {
        $data = $this->post();

        // ✅ Validate only required fields: name and contact number
        if (empty($data['name']) || empty($data['phoneNumber'])) {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = "Name and contact number are required.";
            return $this->response($response, 200);
        }

        // ✅ If email provided, check for duplicate
        if (!empty($data['email'])) {
            $existing = $this->db->get_where('job_application', ['email' => $data['email']])->row();
            if (!empty($existing)) {
                $response['code'] = REST_Controller::HTTP_CONFLICT;
                $response['message'] = "Email is already registered.";
                return $this->response($response, 200);
            }
        }

        // ✅ Prepare insert data (optional fields handled gracefully)
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phoneNumber'],
            'resume_file' => $data['resumeFile'] ?? null,
            'year_of_experience' => $data['yearofExperience'] ?? null,
            'relevant_experience' => $data['relevantExperience'] ?? null,
            'role_applying_for' => $data['roleApplyingFor'] ?? null,
            'current_ctc' => $data['currentCTC'] ?? null,
            'expected_ctc' => $data['expectedCTC'] ?? null,
            'company_id' => $data['companyId'] ?? null,
            'company_whatsapp_number' => $data['companyWhatsappNumber'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // ✅ Insert into database
        $insert_id = $this->General_model->insert('job_application', $insertData);

        if ($insert_id) {
            // Optional WhatsApp notification
            if (!empty($data['companyWhatsappNumber'])) {
                $msg = "Hello *{$data['name']}* 👋,\n\n"
                    . "Your job application has been successfully received.\n"
                    . "Our HR team will contact you shortly. ✅";

                if (isset($this->twilio_lib)) {
                    $this->twilio_lib->send_project_message(
                        $data['companyWhatsappNumber'],
                        $data['name'],
                        'Job Application',
                        $msg
                    );
                }
            }

            $response = [
                'code' => REST_Controller::HTTP_OK,
                'message' => "Job application submitted successfully.",
                'data' => ['application_id' => $insert_id]
            ];
        } else {
            $response = [
                'code' => REST_Controller::HTTP_INTERNAL_ERROR,
                'message' => "Failed to submit job application."
            ];
        }

        return $this->response($response, 200);
    }


    public function job_placement_list_post()
    {
        $data = $this->post();

        // ✅ Pagination setup
        $page  = !empty($data['page_no']) ? (int)$data['page_no'] - 1 : 0;
        $offset = $page * PRODUCT_PAGINATION_SIZE;
        $limit  = PRODUCT_PAGINATION_SIZE;

        // ✅ Base condition
        $wherestring = "job_placements.status = 1";

        // ✅ Select fields
        $fields = [
            'job_placements.id',
            'job_placements.company_name',
            'job_placements.city_id',
            'city.name AS city_name',
            'job_placements.nearby_area',
            'job_placements.is_mou',
            'job_placements.whatsapp_number',
            'job_placements.company_website',
            'j_openings.id AS opening_id',
            'j_openings.position_name',
            'j_openings.salary',
            'j_openings.required_experience'
        ];

        // ✅ Main data query
        $params = [
            'table'        => 'job_placements',
            'fields'       => $fields,
            'wherestring'  => $wherestring,
            'compare_type' => '=',
            'join_type'    => 'left',
            'join_tables'  => [
                'job_placements_openings' => 'job_placements_openings.job_placement_id = job_placements.id',
                'j_openings'              => 'j_openings.id = job_placements_openings.opening_id',
                'city'                    => 'city.id = job_placements.city_id',
            ]
            // 'num'          => $limit,
            // 'offset'       => $offset
        ];

        $raw_list = $this->General_model->get_query_data($params);

        // ✅ Total count
        // $countParams = [
        //     'table'        => 'job_placements',
        //     'fields'       => ['job_placements.id'],
        //     'wherestring'  => $wherestring,
        //     'compare_type' => '=',
        //     'totalrow'     => '1'
        // ];
        // $total = $this->General_model->get_query_data($countParams);

        // ✅ Format response (Group by job placement)
        $formatted_list = [];
        foreach ($raw_list as $row) {
            $id = $row['id'];

            if (!isset($formatted_list[$id])) {
                $formatted_list[$id] = [
                    'id'                => $id,
                    'Company Name'      => $row['company_name'],
                    'City'              => $row['city_name'],
                    'Nearby Area'       => $row['nearby_area'],
                    'MOU'               => (bool)$row['is_mou'],
                    'WhatsApp Number'   => $row['whatsapp_number'],
                    'Company Website'   => $row['company_website'],
                    'Openings'          => []
                ];
            }

            if (!empty($row['opening_id'])) {
                $formatted_list[$id]['Openings'][$row['opening_id']] = [
                    'id'                 => $row['opening_id'],
                    'position_name'      => $row['position_name'],
                    'salary'             => $row['salary'],
                    'required_experience' => $row['required_experience']
                ];
            }
        }

        $result = array_values(array_map(function ($item) {
            $item['Openings'] = array_values($item['Openings']);
            return $item;
        }, $formatted_list));

        // ✅ Final response
        if (!empty($result)) {
            $response['message']     = $this->lang->line('success');
            $response['code']        = REST_Controller::HTTP_OK;
            // $response['total_page']  = ceil($total / PRODUCT_PAGINATION_SIZE);
            $response['data']        = $result;
        } else {
            $response = [
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found'),
            ];
        }

        $this->response($response, 200);
    }
    public function job_placement_filter_post()
    {
        $data = $this->post();

        // ✅ Pagination
        $page  = !empty($data['page_no']) ? (int)$data['page_no'] - 1 : 0;
        $offset = $page * PRODUCT_PAGINATION_SIZE;
        $limit  = PRODUCT_PAGINATION_SIZE;

        // ✅ Filters
        $wheres = ["job_placements.status = 1"];

        $city           = isset($data['city']) ? trim($data['city']) : '';
        $company_name   = isset($data['company_name']) ? trim($data['company_name']) : '';
        $position_name  = isset($data['position_name']) ? trim($data['position_name']) : '';

        if ($city !== '') {
            $wheres[] = "(city.name LIKE '%" . $this->db->escape_like_str($city) . "%' OR job_placements.city_id IS NULL)";
        }

        if ($company_name !== '') {
            $wheres[] = "(job_placements.company_name LIKE '%" . $this->db->escape_like_str($company_name) . "%')";
        }

        if ($position_name !== '') {
            $wheres[] = "(j_openings.position_name LIKE '%" . $this->db->escape_like_str($position_name) . "%' OR j_openings.position_name IS NULL)";
        }

        $wherestring = implode(' AND ', $wheres);

        // ✅ Fields
        $fields = [
            'job_placements.id',
            'job_placements.company_name',
            'job_placements.city_id',
            'city.name AS city_name',
            'job_placements.nearby_area',
            'job_placements.is_mou',
            'job_placements.whatsapp_number',
            'job_placements.company_website',
            'j_openings.id AS opening_id',
            'j_openings.position_name',
            'j_openings.salary',
            'j_openings.required_experience'
        ];

        $params = [
            'table'       => 'job_placements',
            'fields'      => $fields,
            'wherestring' => $wherestring,
            'compare_type' => '=',
            'join_type'   => 'left',
            'join_tables' => [
                'job_placements_openings' => 'job_placements_openings.job_placement_id = job_placements.id',
                'j_openings'              => 'j_openings.id = job_placements_openings.opening_id',
                'city'                    => 'city.id = job_placements.city_id'
            ],
            'groupby'     => 'job_placements.id'
            // 'limit'       => $limit,
            // 'offset'      => $offset
        ];

        // ✅ Get paginated records
        // ✅ Get paginated records
        $raw_list = $this->General_model->get_query_data($params);

        // ✅ Total count without limit
        // $countParams = $params;
        // unset($countParams['limit'], $countParams['offset']);
        // $total_records = $this->General_model->get_query_data($countParams);
        // $total_count = is_array($total_records) ? count($total_records) : 0;

        // ✅ Format response
        $formatted_list = [];
        foreach ($raw_list as $row) {
            $id = $row['id'];

            if (!isset($formatted_list[$id])) {
                $formatted_list[$id] = [
                    'id'                 => $id,
                    'Company Name'       => $row['company_name'],
                    'City'               => $row['city_name'],
                    'Nearby Area'        => $row['nearby_area'],
                    'MOU'                => (bool)$row['is_mou'],
                    'WhatsApp Number'    => $row['whatsapp_number'],
                    'Company Website'    => $row['company_website'],
                    'Openings'           => []
                ];
            }

            if (!empty($row['opening_id'])) {
                $formatted_list[$id]['Openings'][$row['opening_id']] = [
                    'id'                  => $row['opening_id'],
                    'position_name'       => $row['position_name'],
                    'salary'              => $row['salary'],
                    'required_experience' => $row['required_experience']
                ];
            }
        }

        $result = array_values(array_map(function ($item) {
            $item['Openings'] = array_values($item['Openings']);
            return $item;
        }, $formatted_list));

        // ✅ Response
        if (!empty($result)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            // $response['total_page'] = ceil($total_count / $limit);
            $response['data'] = $result;
        } else {
            $response = [
                'code'    => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('no_record_found'),
            ];
        }

        $this->response($response, 200);
    }
    public function openings_list_get()
    {
        $params = [
            'table'          => 'j_openings',
            'fields'         => ['id', 'position_name', 'salary', 'required_experience'],
            'wherestring'    => '1=1',
            'orderby'        => 'position_name',
            'orderdirection' => 'ASC'
        ];

        $opening_list = $this->General_model->get_query_data($params);

        if (!empty($opening_list)) {
            $response['message'] = $this->lang->line('success');
            $response['code'] = REST_Controller::HTTP_OK;
            $response['data'] = $opening_list;
        } else {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        $this->response($response, 200);
    }
}
