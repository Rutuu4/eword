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
    public function job_application_post()
    {
        $data = $this->post();

        // ✅ Validate required fields
        if (empty($data['name']) || empty($data['phoneNumber'])) {
            return $this->response([
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => "Name and contact number are required."
            ], 200);
        }

        // ✅ Prepare data
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phoneNumber'],
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

        $insert_id = $this->General_model->insert('job_application', $insertData);

        if ($insert_id) {

            // ✅ SEND WHATSAPP MESSAGE (same pattern as foreign education)


            return $this->response([
                'code' => REST_Controller::HTTP_OK,
                'message' => "Job application submitted successfully. Please upload your resume.",
                'data' => ['application_id' => $insert_id]
            ], 200);
        }

        return $this->response([
            'code' => REST_Controller::HTTP_INTERNAL_ERROR,
            'message' => "Failed to submit job application."
        ], 200);
    }


    // public function job_application_resume_post()
    // {
    //     // header("Content-Type: application/json; charset=UTF-8");
    //     // ✅ Get job application ID from query params (?id=123)
    //     $job_id = $this->input->get('id'); // use input->get() instead of $this->get()

    //     // ✅ Validate job application ID
    //     if (empty($job_id) || !is_numeric($job_id)) {
    //         return $this->response([
    //             'code' => REST_Controller::HTTP_BAD_REQUEST,
    //             'message' => "Invalid or missing job application ID."
    //         ], 200);
    //     }

    //     // ✅ Check if job application exists using General_model
    //     $checkApplication = $this->General_model->get_query_data([
    //         'table' => 'job_application',
    //         'condition' => ['id' => $job_id],
    //         'num' => 1
    //     ]);

    //     if (empty($checkApplication)) {
    //         return $this->response([
    //             'code' => REST_Controller::HTTP_NOT_FOUND,
    //             'message' => "Job application not found."
    //         ], 200);
    //     }

    //     // ✅ Validate resume file
    //     if (empty($_FILES['resumeFile']['name'])) {
    //         return $this->response([
    //             'code' => REST_Controller::HTTP_BAD_REQUEST,
    //             'message' => "Resume file is required."
    //         ], 200);
    //     }

    //     $allowed_types = ['pdf', 'doc', 'docx'];
    //     $file_ext = strtolower(pathinfo($_FILES['resumeFile']['name'], PATHINFO_EXTENSION));
    //     $file_size = $_FILES['resumeFile']['size'];

    //     if (!in_array($file_ext, $allowed_types)) {
    //         return $this->response([
    //             'code' => REST_Controller::HTTP_BAD_REQUEST,
    //             'message' => "Invalid file type. Only PDF, DOC, and DOCX are allowed."
    //         ], 200);
    //     }

    //     if ($file_size > 5 * 1024 * 1024) { // 5 MB
    //         return $this->response([
    //             'code' => REST_Controller::HTTP_BAD_REQUEST,
    //             'message' => "File size must be less than 5MB."
    //         ], 200);
    //     }

    //     // ✅ Upload path setup
    //     $upload_path = FCPATH . 'uploads/resumes/';
    //     if (!is_dir($upload_path)) {
    //         mkdir($upload_path, 0777, true);
    //     }

    //     $new_filename = 'resume_' . time() . '.' . $file_ext;
    //     $target_file = $upload_path . $new_filename;

    //     if (!move_uploaded_file($_FILES['resumeFile']['tmp_name'], $target_file)) {
    //         return $this->response([
    //             'code' => REST_Controller::HTTP_INTERNAL_ERROR,
    //             'message' => "Failed to upload resume file."
    //         ], 200);
    //     }

    //     $resume_file_path = 'uploads/resumes/' . $new_filename;
    //     log_message('info', 'Resume uploaded successfully: ' . print_r($_FILES, true));
    //     log_message('info', 'Saved file path: ' . $resume_file_path);
    //     // ✅ Update resume file using General_model
    //     $this->General_model->update('job_application', [
    //         'resume_file' => $resume_file_path,
    //         'updated_at' => date('Y-m-d H:i:s')
    //     ], ['id' => $job_id]);

    //     return $this->response([
    //         'code' => REST_Controller::HTTP_OK,
    //         'message' => "Resume uploaded successfully.",
    //         'data' => [
    //             'job_application_id' => $job_id,
    //             'resume_file' => $resume_file_path
    //         ]
    //     ], 200);
    // }

    public function job_application_resume_post()
    {
        try {
            // 1) Safe runtime limits (helps with bigger files)
            ini_set('upload_max_filesize', '50M');
            ini_set('post_max_size', '50M');
            ini_set('max_execution_time', '300');
            ini_set('max_input_time', '300');
            ini_set('memory_limit', '256M');

            // 2) Get job application ID from query param ?id=30
            $job_id = $this->input->get('id');
            if (empty($job_id) || !is_numeric($job_id)) {
                return $this->response([
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'status' => false,
                    'message' => "Invalid or missing job application ID."
                ], REST_Controller::HTTP_OK); // you are returning 200 always
            }

            // 3) Check if job exists
            $checkApplication = $this->General_model->get_query_data([
                'table' => 'job_application',
                'condition' => ['id' => $job_id],
                'num' => 1
            ]);

            if (empty($checkApplication)) {
                return $this->response([
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'status' => false,
                    'message' => "Job application not found."
                ], REST_Controller::HTTP_OK);
            }
            $job = $checkApplication[0];
            // 4) Check file present
            if (empty($_FILES['resumeFile']['name'])) {
                return $this->response([
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'status' => false,
                    'message' => "Resume file is required or upload failed (maybe too large)."
                ], REST_Controller::HTTP_OK);
            }

            // 5) Check PHP upload error
            if ($_FILES['resumeFile']['error'] !== UPLOAD_ERR_OK) {
                $errorMessage = $this->fileUploadErrorMessage($_FILES['resumeFile']['error']);
                return $this->response([
                    'code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR, // ✅ your constant
                    'status' => false,
                    'message' => "File upload error: " . $errorMessage
                ], REST_Controller::HTTP_OK);
            }

            // 6) Validate extension
            $file_ext = strtolower(pathinfo($_FILES['resumeFile']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['pdf', 'doc', 'docx'];
            if (!in_array($file_ext, $allowed_ext)) {
                return $this->response([
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'status' => false,
                    'message' => "Invalid file type. Only PDF, DOC, DOCX allowed."
                ], REST_Controller::HTTP_OK);
            }

            // 7) Validate size (5MB)
            $file_size = $_FILES['resumeFile']['size'];
            if ($file_size > 5 * 1024 * 1024) {
                return $this->response([
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'status' => false,
                    'message' => "File size must be less than 5MB."
                ], REST_Controller::HTTP_OK);
            }

            // 8) Validate MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['resumeFile']['tmp_name']);
            finfo_close($finfo);

            $allowed_mimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            if (!in_array($mime, $allowed_mimes)) {
                return $this->response([
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'status' => false,
                    'message' => "Invalid MIME type. Only PDF, DOC, and DOCX allowed."
                ], REST_Controller::HTTP_OK);
            }

            // 9) Prepare upload dir
            $upload_path = FCPATH . 'uploads/resumes/';
            if (!is_dir($upload_path)) {
                if (!mkdir($upload_path, 0777, true)) {
                    return $this->response([
                        'code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                        'status' => false,
                        'message' => "Failed to create upload directory."
                    ], REST_Controller::HTTP_OK);
                }
            }

            // 10) Move file
            $new_filename = 'resume_' . time() . '.' . $file_ext;
            $target_file = $upload_path . $new_filename;

            if (!move_uploaded_file($_FILES['resumeFile']['tmp_name'], $target_file)) {
                return $this->response([
                    'code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                    'status' => false,
                    'message' => "Failed to move uploaded file. Check folder permissions."
                ], REST_Controller::HTTP_OK);
            }

            // 11) Save to DB
            $resume_file_path = 'uploads/resumes/' . $new_filename;

            $this->General_model->update('job_application', [
                'resume_file' => $resume_file_path,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $job_id]);
            // ======================================================
            // ✅ SEND WHATSAPP MESSAGE AFTER RESUME UPLOAD
            // ======================================================
            $jobcompany_whatsapp_number = $job['company_whatsapp_number'];


            if (!empty($jobcompany_whatsapp_number)) {

                // 🧾 Build data array EXACT like submit API
                $messageData = [
                    'name' => $job['name'],
                    'email' => $job['email'],
                    'phoneNumber' => $job['phone_number'],
                    'yearOfExperience' => $job['year_of_experience'],
                    'relevantExperience' => $job['relevant_experience'],
                    'roleApplyingFor' => $job['role_applying_for'],
                    'currentCTC' => $job['current_ctc'],

                    'expectedCTC' => $job['expected_ctc']
                    // 'resumeUrl' => base_url($resume_file_path)
                ];
                $jobPlacementName = 'Generated'; // fallback
                if (!empty($job['company_id'])) {

                    $fe = $this->db
                        ->select('company_name')
                        ->from('job_placements')
                        ->where('id', $job['company_id'])
                        ->get()
                        ->row();

                    if ($fe && !empty($fe->company_name)) {
                        $jobPlacementName = "To " . $fe->company_name;
                    }
                }

                // 📝 Build message (FORMAT UNCHANGED)
                $messageText = "*Student Lead  {$jobPlacementName} From E World Education*\n\n"
                    . "Hello,\n\n"
                    . "A new student inquiry has been submitted to you through  *E World Education*\n\n"
                    . "*Student Details:*\n\n";

                foreach ($messageData as $key => $value) {

                    if (empty($value)) {
                        continue;
                    }

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
                $mou_phone_number = get_phone_number_by_type('job');
                // ✅ Insert into wp_messages
                $wpMessageData = [
                    'message_id'   => $this->uuid_v4(),
                    'phone_number' => $jobcompany_whatsapp_number,
                    'mou_phone_number' => $mou_phone_number,
                    'website_link' => base_url($resume_file_path),
                    'message_text' => $messageText,
                    'message_type' => 'text',
                    'status'       => 'queued',
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];

                $this->wp_db->insert('wp_messages', $wpMessageData);

                if ($this->wp_db->affected_rows() == 0) {
                    log_message('error', 'WP Message insert failed: ' . json_encode($wpMessageData));
                }
            }


            // 12) Final success
            return $this->response([
                'code' => REST_Controller::HTTP_OK,
                'status' => true,
                'message' => "Resume uploaded successfully.",
                'data' => [
                    'job_application_id' => $job_id,
                    'resume_file' => base_url($resume_file_path),
                    'mime_type' => $mime,
                    'file_size_kb' => round($file_size / 1024, 2)
                ]
            ], REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            // catch any unhandled error
            return $this->response([
                'code' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'status' => false,
                'message' => "Unexpected server error: " . $e->getMessage()
            ], REST_Controller::HTTP_OK);
        }
    }

    /**
     * ✅ Converts PHP file upload error codes into readable messages
     */
    private function fileUploadErrorMessage($error_code)
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds upload_max_filesize limit (php.ini).',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds MAX_FILE_SIZE directive in HTML form.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk. Check permissions.',
            UPLOAD_ERR_EXTENSION  => 'File upload stopped by a PHP extension.'
        ];

        return $errors[$error_code] ?? 'Unknown upload error.';
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
            'city_job.name AS city_job_name',
            'job_placements.nearby_area',
            'job_placements.salary',
            'job_placements.required_experience',
            'job_placements.is_mou',
            'job_placements.whatsapp_number',
            'job_placements.company_website',
            'j_openings.id AS opening_id',
            'j_openings.position_name'

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
                'city_job'                    => 'city_job.id = job_placements.city_id',
            ],
            'orderby' => 'job_placements.company_name',
            'order'   => 'ASC'
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
                    'Salary'       => $row['salary'],
                    'required_experience'       => $row['required_experience'],
                    'MOU'               => (bool)$row['is_mou'],
                    'WhatsApp Number'   => $row['whatsapp_number'],
                    'Company Website'   => $row['company_website'],
                    'Openings'          => []
                ];
            }

            if (!empty($row['opening_id'])) {
                $formatted_list[$id]['Openings'][$row['opening_id']] = [
                    'id'                 => $row['opening_id'],
                    'position_name'      => $row['position_name']

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
            $wheres[] = "(city_job.name LIKE '%" . $this->db->escape_like_str($city) . "%' OR job_placements.city_id IS NULL)";
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
            'city_job.name AS city_name',
            'job_placements.nearby_area',
            'job_placements.salary',
            'job_placements.required_experience',
            'job_placements.is_mou',
            'job_placements.whatsapp_number',
            'job_placements.company_website',
            'j_openings.id AS opening_id',
            'j_openings.position_name'

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
                'city_job'                    => 'city_job.id = job_placements.city_id'
            ],
            'groupby'     => 'job_placements.id',
            'orderby' => 'job_placements.company_name',
            'order'   => 'ASC'
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
                    'Salary'              => $row['salary'],
                    'required_experience' => $row['required_experience'],
                    'MOU'                => (bool)$row['is_mou'],
                    'WhatsApp Number'    => $row['whatsapp_number'],
                    'Company Website'    => $row['company_website'],
                    'Openings'           => []
                ];
            }

            if (!empty($row['opening_id'])) {
                $formatted_list[$id]['Openings'][$row['opening_id']] = [
                    'id'                  => $row['opening_id'],
                    'position_name'       => $row['position_name']

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
            'fields'         => ['id', 'position_name'],
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
