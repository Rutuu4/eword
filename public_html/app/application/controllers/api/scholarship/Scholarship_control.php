<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Scholarship_control extends REST_Controller {

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
     

    public function scholarship_list_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           
                $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
                $per_page   = $page * PRODUCT_PAGINATION_SIZE;

                $wherestring    = "scholarship_loan_details.status=1 order by display_order ASC";
                $fields=['scholarship_loan_details.id,scholarship_loan_details.name,scholarship_loan_details.short_details'];
                $params = array(
                'table'         => TBL_SCHOLARSHIP_LOAN_DETAILS.' as scholarship_loan_details',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                 "num"           => PRODUCT_PAGINATION_SIZE,
                 "offset"        => $per_page
            );
            $porductList = $this->General_model->get_query_data($params);  
            $cntParams = array(
                    'table'         => TBL_SCHOLARSHIP_LOAN_DETAILS.' as scholarship_loan_details',
                    'fields'        => $fields,
                    'wherestring'   => !empty($wherestring)?$wherestring:'',
                    'compare_type'  => '=',
                    "totalrow"      => '1',
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

             
                   
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
     public function scholarship_details_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           
           
            $scholarship_id=$data['scholarship_id'];
            $wherestring    = "scholarship_loan_details.id=$scholarship_id";
            $params = array(
                 'table'         => TBL_SCHOLARSHIP_LOAN_DETAILS.' as scholarship_loan_details',
                 'wherestring'   => !empty($wherestring)?$wherestring:'',

            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {
                        for ($i=0; $i < count($porductList); $i++) 
                        {
                                $porductList[$i]['pdf_file']     = check_file_exists($porductList[$i]['pdf_file']);

                        }    

                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK;
                            $response['data']       = $porductList;

            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
            
                   
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
        

}